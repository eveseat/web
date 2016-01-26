<?php
/*
This file is part of SeAT

Copyright (C) 2015, 2016  Leon Jacobs

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/

namespace Seat\Web\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Cache;
use Illuminate\Http\Request;

/**
 * Class ResolveController
 * @package Seat\Web\Http\Controllers\Support
 */
class ResolveController extends Controller
{

    /**
     * The prefix used for name_ids in the cache
     *
     * @var string
     */
    protected $prefix = 'name_id';

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function resolveIdsToNames(Request $request)
    {

        $ids = array_unique(explode(',', $request->ids));

        // Init the initial return array
        $response = [];

        // Populate any entries from the cache
        foreach ($ids as $id) {

            if (Cache::has($this->prefix . $id)) {

                $response[$id] = Cache::get($this->prefix . $id);
                unset($ids[$id]);
            }
        }

        // Call the EVE API for any outstanding ids that need
        // resolution
        if (!empty($ids)) {

            $pheal = app()
                ->make('Seat\Eveapi\Helpers\PhealSetup')
                ->getPheal();

            foreach (array_chunk($ids, 30) as $id_chunk) {

                $names = $pheal->eveScope->CharacterName([
                    'ids' => implode(',', $id_chunk)]);

                foreach ($names->characters as $result) {

                    Cache::forever(
                        $this->prefix . $result->characterID, $result->name);
                    $response[$result->characterID] = $result->name;
                }

            }
        }

        return response()->json($response);
    }
}
