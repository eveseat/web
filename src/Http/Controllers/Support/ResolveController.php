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
        collect(explode(',', $request->ids))->map(function ($id) {

            // Convert them all to integers
            return (int) $id;

        })->unique()->filter(function ($id) use (&$response) {

            // Next, filter the ids we have in the cache, setting
            // the appropriate response values as we go along.
            if ($cached_entry = cache($this->prefix . $id)) {

                $response[$id] = $cached_entry;

                // Remove this as a valid id, we already have the value we want.
                return false;
            }

            // We don't have this id in the cache. Return it
            // so that we can update it later.
            return true;

        })->chunk(1000)->each(function ($chunk) use (&$response, $eseye) {

            $chunk = $this->resolveFactionIDs($chunk, $response);

            // quick break if no more IDs must be resolve by ESI
            if ($chunk->count() == 0)
                return;

            $chunk = $this->resolveCharacterIDsFromSeat($chunk, $response);

            // quick break if no more IDs must be resolve by ESI
            if ($chunk->count() == 0)
                return;

            $chunk = $this->resolveCorporationIDsFromSeat($chunk, $response);

            // quick break if no more IDs must be resolve by ESI
            if ($chunk->count() == 0)
                return;

            $this->resolveIDsfromESI($chunk, $response, $eseye);

        });

        // Grr. Without this, arbitrary things will get replaced as
        // #System/Corporation in the UI. Infuriating to say the least.
        $response->forget([0, 2]);

        return response()->json($response);
    }

    private function resolveFactionIDs($chunk, $response)
    {

        // universe resolver is not working on factions at this time
        // retrieve them from SDE and remove them from collection
        // TODO CCP WIP : https://github.com/ccpgames/esi-issues/issues/736
        $names = ChrFaction::whereIn('factionID', $chunk->flatten()->toArray())
            ->get();

        collect($names)->each(function ($name) use (&$response) {

            cache([$this->prefix . $name->factionID => $name->factionName], carbon()->addCentury());
            $response[$name->factionID] = $name->factionName;

        });

        $chunk = $chunk->filter(function ($id) use ($names) {

            return ! $names->contains('factionID', $id);
        });

        return $chunk;
    }

    private function resolveCharacterIDsFromSeat($chunk, $response)
    {

        // resolve names that are already in SeAT
        // no unnecessary api calls the request can be resolved internally.
        $names = CharacterInfo::whereIn('character_id', $chunk->flatten()->toArray())
            ->get();

        collect($names)->each(function ($name) use (&$response) {

            cache([$this->prefix . $name->character_id => $name->name], carbon()->addCentury());
            $response[$name->character_id] = $name->name;

        });

        $chunk = $chunk->filter(function ($id) use ($names) {

            return ! $names->contains('character_id', $id);
        });

        return $chunk;
    }

    private function resolveCorporationIDsFromSeat($chunk, $response)
    {

        // resolve names that are already in SeAT
        // no unnecessary api calls the request can be resolved internally.
        $names = CorporationInfo::whereIn('corporation_id', $chunk->flatten()->toArray())
            ->get();

        collect($names)->each(function ($name) use (&$response) {

            cache([$this->prefix . $name->corporation_id => $name->name], carbon()->addCentury());
            $response[$name->corporation_id] = $name->name;

        });

        $chunk = $chunk->filter(function ($id) use ($names) {

            return ! $names->contains('corporation_id', $id);
        });

        return $chunk;
    }

    private function resolveIDsfromESI($ids, $response, $eseye)
    {

        // Finally, grab outstanding ids and resolve their names
        // using Esi.

        try {
            $eseye->setVersion('v2');
            $eseye->setBody($ids->flatten()->toArray());
            $names = $eseye->invoke('post', '/universe/names/');

            collect($names)->each(function ($name) use (&$response) {

                // Cache the name resolution for this id for a long time.
                cache([$this->prefix . $name->id => $name->name], carbon()->addCentury());
                $response[$name->id] = $name->name;

            });

        } catch (\Exception $e) {
            // If this fails split the ids in half and try to self referential resolve the half_chunks
            // until all possible resolvable ids has processed.
            if ($ids->count() === 1) {
                // return a singleton unresolvable id as 'unknown'
                $response[$ids->first()] = trans('web::seat.unknown');
            } else {
                //split the chunk in two
                $half = ceil($ids->count() / 2);
                //keep on processing the halfs independently,
                //ideally one of the halfs will process just perfect
                $ids->chunk($half)->each(function ($half_chunk) use ($response, $eseye) {

                    //this is a selfrefrencial call.
                    $this->resolveIDsfromESI($half_chunk, $response, $eseye);
                });
            }

        }

    }
}
