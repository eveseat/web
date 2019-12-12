<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015, 2016, 2017, 2018, 2019  Leon Jacobs
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
use Seat\Web\Models\User;

/**
 * Class FastLookupController.
 * @package Seat\Web\Http\Controllers\Support
 */
class FastLookupController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUsers(Request $request)
    {
        $users = User::with('characters')->where('name', 'like', ["%{$request->query('q', '')}%"])->get();

        return response()->json([
            'results' => $users->map(function ($user) {

                $characters = $user->characters->sortBy(function ($character, $key) use ($user) {
                    if ($character->character_id == $user->main_character_id)
                        return -1;

                    return $character->name;
                });

                $images_array = $characters->map(function ($character) {
                    return img('characters', 'portrait', $character->character_id, 64, ['class' => 'img-circle eve-icon small-icon'], false);
                })->flatten();

                if ($characters->isEmpty())
                    $images_array = [img('characters', 'portrait', 0, 64, ['class' => 'img-circle eve-icon small-icon'], false)];

                return [
                    'id' => $user->id,
                    'type' => 'characters',
                    'text' => $user->name,
                    'href' => route('character.view.sheet', ['character_id' => $user->main_character_id]),
                    'character_id' => $user->main_character_id,
                    'img' => $images_array,
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
