<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015, 2016, 2017, 2018  Leon Jacobs
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

namespace Seat\Web\Http\Controllers\Support;

use Illuminate\Http\Request;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Eveapi\Models\Sde\ChrFaction;
use Seat\Web\Http\Controllers\Controller;

/**
 * Class ResolveController.
 * @package Seat\Web\Http\Controllers\Support
 */
class ResolveController extends Controller
{
    /**
     * The prefix used for name_ids in the cache.
     *
     * @var string
     */
    protected $prefix = 'name_id:';

    protected $response;

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function resolveIdsToNames(Request $request)
    {

        // Init the initial return array
        $response = collect();

        // Resolve the Esi client library from the IoC
        $eseye = app('esi-client')->get();

        // Grab the ids from the request for processing
        collect(explode(',', $request->ids))
            ->map(function ($id) {

            // Convert them all to integers
            return (int) $id;
            })
            ->unique()
            ->filter(function ($id) {

                // Next, filter the ids we have in the cache, setting
                // the appropriate response values as we go along.
                if ($cached_entry = cache($this->prefix . $id)) {

                    $this->response[$id] = $cached_entry;

                    // Remove this as a valid id, we already have the value we want.
                    return false;
                }

                // Filter out ids that are System items
                // see: https://gist.github.com/a-tal/5ff5199fdbeb745b77cb633b7f4400bb
                if ($id <= 10000)
                    return false;

                // We don't have this id in the cache. Return it
                // so that we can update it later.
                return true;
            })
            ->pipe(function ($collection) {
                return $collection->when($collection->isNotEmpty(),function ($ids) {
                    return $this->resolveFactionIDs($ids);
                });
            })
            ->pipe(function ($collection) {
                return $collection->when($collection->isNotEmpty(),function ($ids) {
                    return $this->resolveCharacterIDsFromSeat($ids);
                });
            })
            ->pipe(function ($collection) {
                return $collection->when($collection->isNotEmpty(),function ($ids) {
                    return $this->resolveCorporationIDsFromSeat($ids);
                });
            })
            ->chunk(1000)
            ->each(function ($chunk) use ($eseye) {

                // quick break if no more IDs must be resolve by ESI
                if ($chunk->isEmpty())
                    return;

                $this->resolveIDsfromESI($chunk, $eseye);

            });

        return response()->json($this->response);
    }

    private function resolveFactionIDs($ids)
    {

        // universe resolver is not working on factions at this time
        // retrieve them from SDE and remove them from collection
        // TODO CCP WIP : https://github.com/ccpgames/esi-issues/issues/736
        $names = ChrFaction::whereIn('factionID', $ids->flatten()->toArray())
            ->get();

        collect($names)->each(function ($name){

            cache([$this->prefix . $name->factionID => $name->factionName], carbon()->addCentury());
            $this->response[$name->factionID] = $name->factionName;

        });

        $ids = $ids->filter(function ($id) use ($names) {

            return ! $names->contains('factionID', $id);
        });

        return $ids;
    }

    private function resolveCharacterIDsFromSeat($ids)
    {

        // resolve names that are already in SeAT
        // no unnecessary api calls the request can be resolved internally.
        $names = CharacterInfo::whereIn('character_id', $ids->flatten()->toArray())
            ->get();

        collect($names)->each(function ($name) {

            cache([$this->prefix . $name->character_id => $name->name], carbon()->addCentury());
            $this->response[$name->character_id] = $name->name;

        });

        $ids = $ids->filter(function ($id) use ($names) {

            return ! $names->contains('character_id', $id);
        });

        return $ids;
    }

    private function resolveCorporationIDsFromSeat($chunk)
    {

        // resolve names that are already in SeAT
        // no unnecessary api calls the request can be resolved internally.
        $names = CorporationInfo::whereIn('corporation_id', $chunk->flatten()->toArray())
            ->get();

        collect($names)->each(function ($name) {

            cache([$this->prefix . $name->corporation_id => $name->name], carbon()->addCentury());
            $this->response[$name->corporation_id] = $name->name;

        });

        $chunk = $chunk->filter(function ($id) use ($names) {

            return ! $names->contains('corporation_id', $id);
        });

        return $chunk;
    }

    private function resolveIDsfromESI($ids, $eseye)
    {

        // Finally, grab outstanding ids and resolve their names
        // using Esi.

        try {
            $eseye->setVersion('v2');
            $eseye->setBody($ids->flatten()->toArray());
            $names = $eseye->invoke('post', '/universe/names/');

            collect($names)->each(function ($name) {

                // Cache the name resolution for this id for a long time.
                cache([$this->prefix . $name->id => $name->name], carbon()->addCentury());
                $this->response[$name->id] = $name->name;

            });

        } catch (\Exception $e) {
            // If this fails split the ids in half and try to self referential resolve the half_chunks
            // until all possible resolvable ids has processed.
            if ($ids->count() === 1) {
                // return a singleton unresolvable id as 'unknown'
                $this->response[$ids->first()] = trans('web::seat.unknown');
            } else {
                //split the chunk in two
                $half = ceil($ids->count() / 2);
                //keep on processing the halfs independently,
                //ideally one of the halfs will process just perfect
                $ids->chunk($half)->each(function ($half_chunk) use ($eseye) {

                    //this is a selfrefrencial call.
                    $this->resolveIDsfromESI($half_chunk, $eseye);
                });
            }

        }

    }
}
