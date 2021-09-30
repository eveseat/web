<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2021 Leon Jacobs
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
use Illuminate\Support\Collection;
use Seat\Eveapi\Models\Alliances\Alliance;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Eveapi\Models\Universe\UniverseName;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Models\User;

/**
 * Class ResolveController.
 *
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
     * The collection to return after resolving the submitted ids.
     *
     * @var Collection
     */
    protected $response;

    /**
     * ResolveController constructor.
     */
    public function __construct()
    {
        $this->response = collect();
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function resolveIdsToNames(Request $request)
    {

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

                // Filter out ids that are System items
                // see: https://gist.github.com/a-tal/5ff5199fdbeb745b77cb633b7f4400bb
                if ($id <= 10000)
                    return false;

                // Next, filter the ids we have in the cache, setting
                // the appropriate response values as we go along.
                if ($cached_entry = cache($this->prefix . $id)) {

                    $this->response[$id] = $cached_entry;

                    // Remove this as a valid id, we already have the value we want.
                    return false;
                }

                // We don't have this id in the cache. Return it
                // so that we can update it later.
                return true;
            })
            ->pipe(function ($collection) {
                return $collection->when($collection->isNotEmpty(), function ($ids) {
                    return $this->resolveFactionIDs($ids);
                });
            })
            ->pipe(function ($collection) {
                return $collection->when($collection->isNotEmpty(), function ($ids) {
                    return $this->resolveInternalUniverseIDs($ids);
                });
            })
            ->pipe(function ($collection) {
                return $collection->when($collection->isNotEmpty(), function ($ids) {
                    return $this->resolveInternalCharacterIDs($ids);
                });
            })
            ->pipe(function ($collection) {
                return $collection->when($collection->isNotEmpty(), function ($ids) {
                    return $this->resolveInternalCorporationIDs($ids);
                });
            })
            ->pipe(function ($collection) {
                return $collection->when($collection->isNotEmpty(), function ($ids) {
                    return $this->resolveInternalAllianceIDs($ids);
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

    /**
     * Resolve received sets of ids with the help of chrFactions table
     * map the resolved names, cache the results and return unresolved ids.
     *
     * @param  \Illuminate\Support\Collection  $ids
     * @return \Illuminate\Support\Collection collection of ids that were unable to be resolved within this function
     */
    private function resolveFactionIDs(Collection $ids)
    {

        // universe resolver is not working on factions at this time
        // retrieve them from SDE and remove them from collection
        $names = UniverseName::whereIn('entity_id', $ids->flatten()->toArray())
            ->get()
            ->map(function ($entity) {
                return collect([
                    'id'       => $entity->entity_id,
                    'name'     => $entity->name,
                    'category' => $entity->category,
                ]);
            });

        return $this->cacheIDsAndReturnUnresolvedIDs($names, $ids);
    }

    /**
     * Resolve received sets of ids with the help of universe_names table
     * map the resolved names, cache the results and return unresolved ids.
     *
     * @param  \Illuminate\Support\Collection  $ids
     * @return \Illuminate\Support\Collection collection of ids that were unable to be resolved within this function
     */
    private function resolveInternalUniverseIDs(Collection $ids)
    {
        // resolve names that are already in SeAT
        // no unnecessary api calls the request can be resolved internally.
        $names = UniverseName::whereIn('entity_id', $ids->flatten()->toArray())
            ->get()
            ->map(function ($entity) {
                return collect([
                    'id' => $entity->entity_id,
                    'name' => $entity->name,
                    'category' =>$entity->category,
                ]);
            });

        return $this->cacheIDsAndReturnUnresolvedIDs($names, $ids);
    }

    /**
     * Resolve received sets of ids with the help of character_infos table
     * map the resolved names, cache the results and return unresolved ids.
     *
     * @param  \Illuminate\Support\Collection  $ids
     * @return \Illuminate\Support\Collection collection of ids that were unable to be resolved within this function
     */
    private function resolveInternalCharacterIDs(Collection $ids)
    {

        // resolve names that are already in SeAT
        // no unnecessary api calls the request can be resolved internally.
        $names = CharacterInfo::whereIn('character_id', $ids->flatten()->toArray())
            ->get()
            ->map(function ($character) {
                return collect([
                    'id' => $character->character_id,
                    'name' => $character->name,
                    'category' => 'character',
                ]);
            });

        return $this->cacheIDsAndReturnUnresolvedIDs($names, $ids);
    }

    /**
     * Resolve received sets of ids with the help of corporation_infos table
     * map the resolved names, cache the results and return unresolved ids.
     *
     * @param  \Illuminate\Support\Collection  $ids
     * @return \Illuminate\Support\Collection collection of ids that were unable to be resolved within this function
     */
    private function resolveInternalCorporationIDs(Collection $ids)
    {

        // resolve names that are already in SeAT
        // no unnecessary api calls the request can be resolved internally.
        $names = CorporationInfo::whereIn('corporation_id', $ids->flatten()->toArray())
            ->get()
            ->map(function ($corporation) {
                return collect([
                    'id' => $corporation->corporation_id,
                    'name' => $corporation->name,
                    'category' => 'corporation',
                ]);
            });

        return $this->cacheIDsAndReturnUnresolvedIDs($names, $ids);
    }

    /**
     * @param  \Illuminate\Support\Collection  $ids
     * @return \Illuminate\Support\Collection
     */
    private function resolveInternalAllianceIDs(Collection $ids)
    {

        // resolve names that are already in SeAT
        // no unnecessary api calls if the request can be resolved internally.
        $names = Alliance::whereIn('alliance_id', $ids->flatten()->toArray())
            ->get()
            ->map(function ($alliance) {
                return collect([
                    'id'       => $alliance->alliance_id,
                    'name'     => $alliance->name,
                    'category' => 'alliance',
                ]);
            });

        return $this->cacheIDsAndReturnUnresolvedIDs($names, $ids);
    }

    /**
     * Resolve given set of ids with the help of eseye client and ESI
     * using a boolean algorithm if one of the ids in the collection of ids
     * is invalid.
     * If name could be resolved, save the name to universe_names table.
     *
     * @param  \Illuminate\Support\Collection  $ids
     * @param  \Seat\Eseye\Eseye  $eseye
     */
    private function resolveIDsfromESI(Collection $ids, $eseye)
    {

        // Finally, grab outstanding ids and resolve their names
        // using Esi.

        try {
            $eseye->setVersion('v3');
            $eseye->setBody($ids->flatten()->toArray());
            $names = $eseye->invoke('post', '/universe/names/');

            collect($names)->each(function ($name) {

                // Cache the name resolution for this id for a long time.
                cache([$this->prefix . $name->id => $name->name], carbon()->addCentury());
                $this->response[$name->id] = $name->name;

                UniverseName::firstOrCreate([
                    'entity_id' => $name->id,
                ], [
                    'name'      => $name->name,
                    'category'  => $name->category,
                ]);

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

    /**
     * Cache and save resolved IDs. Return unresolved collection of ids.
     *
     * @param  \Illuminate\Support\Collection  $names  resolved names
     * @param  \Illuminate\Support\Collection  $ids
     * @return \Illuminate\Support\Collection unresolved collection of ids
     */
    private function cacheIDsAndReturnUnresolvedIDs(Collection $names, Collection $ids): Collection
    {
        $names->each(function ($name) {

            cache([$this->prefix . $name['id'] => $name['name']], carbon()->addCentury());
            $this->response[$name['id']] = $name['name'];

            UniverseName::firstOrCreate([
                'entity_id' => $name['id'],
            ], [
                'name'      => $name['name'],
                'category'  => $name['category'],
            ]);
        });

        $ids = $ids->filter(function ($id) use ($names) {

            return ! $names->contains('id', $id);
        });

        return $ids;

    }

    /**
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resolveMainCharacter(Request $request)
    {

        // Grab the ids from the request for processing
        collect(explode(',', $request->ids))->map(function ($id) {

            // Convert them all to integers
            return (int) $id;

        })->each(function ($chunk) {

            $character = User::find($chunk)->main_character;

            $this->response[$chunk] = view('web::partials.character', compact('character'))->render();
        });

        return response()->json($this->response);

    }
}
