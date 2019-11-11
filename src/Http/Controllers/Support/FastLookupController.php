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
        })->get();

        return response()->json([
            'results' => $groups->map(function ($group) {
                $main_character = $group->main_character;

                if (is_null($main_character))
                    $main_character = $group->users->first();

                $characters = $group->users->reject(function ($user) use ($main_character) {
                    return $user->id === $main_character->character_id;
                });

                $characters->prepend($main_character);

                return [
                    'id'           => $group->id,
                    'text'         => $characters->pluck('name'),
                    'type'         => 'character',
                    'character_id' => $characters->map(function ($character) {
                        return property_exists($character, 'id') ? $character->id : $character->character_id;
                    }),
                    'img'          => $characters->map(function ($character) {
                        $entity_id = property_exists($character, 'id') ? $character->id : $character->character_id;

                        return img('characters', 'portrait', $entity_id, 64, ['class' => 'img-circle eve-icon small-icon'], false);
                    }),
                ];
            }),
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

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEntities(Request $request)
    {

        $this->validate($request, [
            'type' => 'in:characters,corporations,alliances',
            'q'    => 'required',
        ]);

        $characters_query = null;
        $corporations_query = null;
        $alliances_query = null;

        if (in_array($request->query('type', ''), ['', 'characters']))
            $characters_query = CharacterInfo::select('name')
                ->selectRaw('character_id as entity_id')
                ->selectRaw('"character" as entity_type')
                ->where('name', 'like', '%' . $request->query('q', '') . '%')
                ->getQuery();

        if (in_array($request->query('type', ''), ['', 'corporations']))
            $corporations_query = CorporationInfo::select('name')
                ->selectRaw('corporation_id as entity_id')
                ->selectRaw('"corporation" as entity_type')
                ->where('name', 'like', '%' . $request->query('q', '') . '%')
                ->getQuery();

        if (in_array($request->query('type', ''), ['', 'alliances']))
            $alliances_query = Alliance::select('name')
                ->selectRaw('alliance_id as entity_id')
                ->selectRaw('"alliance" as entity_type')
                ->where('name', 'like', '%' . $request->query('q', '') . '%')
                ->getQuery();

        if (! is_null($characters_query))
            $union = $characters_query;

        if (! is_null($corporations_query))
            $union->union($corporations_query);
        else
            $union = $corporations_query;

        if (! is_null($alliances_query))
            $union->union($alliances_query);
        else
            $union = $alliances_query;

        return response()->json([
            'results' => $union->get()->map(function ($entity, $key) {
                switch ($entity->entity_type) {
                    case 'corporation':
                        $img_type = 'corporations';
                        break;
                    case 'alliance':
                        $img_type = 'alliances';
                        break;
                    default:
                        $img_type = 'characters';
                }

                return [
                    'id'   => $entity->entity_id,
                    'text' => $entity->name,
                    'type' => $entity->entity_type,
                    'img'  => img(
                        $img_type,
                        $img_type == 'characters' ? 'portrait' : 'logo',
                        $entity->entity_id,
                        64,
                        ['class' => 'img-circle eve-icon small-icon'],
                        false),
                ];
            }),
        ]);
    }
}
