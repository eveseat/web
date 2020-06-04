<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2020 Leon Jacobs
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
use Seat\Eveapi\Models\Alliances\Alliance;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Models\Group;

/**
 * Class FastLookupController.
 * @package Seat\Web\Http\Controllers\Support
 */
class FastLookupController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGroups(Request $request)
    {

        $groups = Group::whereHas('users', function ($query) use ($request) {
            $query->where('name', 'like', '%' . $request->query('q', '') . '%');
        })->get()->map(function ($group, $key) {
            return [
                'id' => $group->id,
                'text' => $group->users->map(function ($user) { return $user->name; })->implode(', '),
            ];
        });

        return response()->json([
            'results' => $groups,
        ]);

    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCharacters(Request $request)
    {
        $characters = CharacterInfo::where('name', 'like', '%' . $request->query('q', '') . '%')->get()->map(function ($char, $key) {
            return [
                'id' => $char->character_id,
                'text' => $char->name,
            ];
        });

        return response()->json([
            'results' => $characters,
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCorporations(Request $request)
    {

        $corporations = CorporationInfo::where('name', 'like', '%' . $request->query('q', '') . '%')->get()->map(function ($corp, $key) {
            return [
                'id' => $corp->corporation_id,
                'text' => $corp->name,
            ];
        });

        return response()->json([
            'results' => $corporations,
        ]);

    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAlliances(Request $request)
    {

        $alliance = Alliance::where('name', 'like', '%' . $request->query('q', '') . '%')->get()->map(function ($alli, $key) {
            return [
                'id' => $alli->alliance_id,
                'text' => $alli->name,
            ];
        });

        return response()->json([
            'results' => $alliance,
        ]);

    }
}
