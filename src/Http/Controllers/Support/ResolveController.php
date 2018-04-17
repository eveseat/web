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
     * @throws \Illuminate\Container\EntryNotFoundException
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

            // quick break if no more IDs must be resolve by ESI
            if ($chunk->count() == 0)
                return;

            // Finally, grab outstanding ids and resolve their names
            // using Esi.

            $eseye->setVersion('v2');
            $eseye->setBody($chunk->flatten()->toArray());
            $names = $eseye->invoke('post', '/universe/names/');

            collect($names)->each(function ($name) use (&$response) {

                // Cache the name resolution for this id for a long time.
                cache([$this->prefix . $name->id => $name->name], carbon()->addCentury());

                $response[$name->id] = $name->name;
            });
        });

        // Grr. Without this, arbitrary things will get replaced as
        // #System in the UI. Infuriating to say the least.
        $response->forget(0);

        return response()->json($response);
    }
}
