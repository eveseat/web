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

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Seat\Eveapi\Models\Alliances\Alliance;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Eveapi\Models\Corporation\CorporationRole;
use Seat\Eveapi\Models\Corporation\CorporationTitle;
use Seat\Eveapi\Models\Sde\Constellation;
use Seat\Eveapi\Models\Sde\InvType;
use Seat\Eveapi\Models\Sde\Region;
use Seat\Eveapi\Models\Sde\SolarSystem;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Models\User;

/**
 * Class FastLookupController.
 *
 * @package Seat\Web\Http\Controllers\Support
 */
class FastLookupController extends Controller
{
    const SKILL_CATEGORY_ID = 16;

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUsers(Request $request)
    {
        $users = User::with('characters')
            ->standard()
            ->where('name', 'like', ["%{$request->query('q', '')}%"]);

        if ($request->exists('exclude')) {
            $users = $users->where('id', '<>', $request->query('exclude'));
        }

        $users = $users->get();

        return response()->json([
            'results' => $users->map(function ($user) {

                $characters = $user->characters->sortBy(function ($character, $key) use ($user) {
                    if ($character->character_id == $user->main_character_id)
                        return -1;

                    return $character->name;
                });

                $images_array = $characters->map(function ($character) {
                    $attributes = [
                        'class' => 'img-circle eve-icon small-icon',
                        'data-link' => route('character.view.sheet', ['character' => $character]),
                        'data-name' => $character->name,
                    ];

                    return img('characters', 'portrait', $character->character_id, 64, $attributes, false);
                })->flatten();

                if ($characters->isEmpty()) {
                    $attributes = [
                        'class' => 'img-circle eve-icon small-icon',
                        'data-link' => route('character.view.sheet', ['character' => $user->main_character]),
                        'data-name' => $user->name,
                    ];

                    $images_array = [
                        img('characters', 'portrait', $user->main_character_id, 64, $attributes, false),
                    ];
                }

                return [
                    'id' => $user->id,
                    'type' => 'characters',
                    'text' => $user->name,
                    'href' => route('character.view.sheet', ['character' => $user->main_character]),
                    'character_id' => $user->main_character_id,
                    'img' => $images_array,
                ];
            }),
        ]);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTitles(Request $request)
    {
        if ($request->query('_type', 'query') == 'find') {
            $title = CorporationTitle::find($request->query('q', 1));

            return response()->json([
                'id'   => $title->id,
                'text' => sprintf('%s (%s)', strip_tags($title->name), $title->corporation->name),
            ]);
        }

        $titles = CorporationTitle::with('corporation')
            ->where('name', 'like', '%' . $request->query('q', '') . '%')
            ->orderBy('name')
            ->get()
            ->map(function ($title) {
                return [
                    'id'   => $title->id,
                    'text' => sprintf('%s (%s)', strip_tags($title->name), $title->corporation->name),
                ];
            });

        return response()->json([
            'results' => $titles,
        ]);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCharacters(Request $request)
    {
        if ($request->query('_type', 'query') == 'find') {
            $character = CharacterInfo::find($request->query('q', 0));

            return response()->json([
                'id' => $character->character_id,
                'text' => $character->name,
            ]);
        }

        $characters = CharacterInfo::where('name', 'like', '%' . $request->query('q', '') . '%')
            ->orderBy('name')
            ->get()
            ->map(function ($character, $key) {
                return [
                    'id' => $character->character_id,
                    'text' => $character->name,
                ];
            });

        return response()->json([
            'results' => $characters,
        ]);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCorporations(Request $request)
    {
        if ($request->query('_type', 'query') == 'find') {
            $corporation = CorporationInfo::find($request->query('q', 0));

            return response()->json([
                'id' => $corporation->corporation_id,
                'text' => $corporation->name,
            ]);
        }

        $corporations = CorporationInfo::where('name', 'like', '%' . $request->query('q', '') . '%')
            ->orderBy('name')
            ->get()
            ->map(function ($corporation, $key) {
                return [
                    'id' => $corporation->corporation_id,
                    'text' => $corporation->name,
                ];
            });

        return response()->json([
            'results' => $corporations,
        ]);

    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAlliances(Request $request)
    {
        if ($request->query('_type', 'query') == 'find') {
            $alliance = Alliance::find($request->query('q', 0));

            return response()->json([
                'id' => $alliance->alliance_id,
                'text' => $alliance->name,
            ]);
        }

        $alliances = Alliance::where('name', 'like', '%' . $request->query('q', '') . '%')
            ->orderBy('name')
            ->get()
            ->map(function ($alliance, $key) {
                return [
                    'id' => $alliance->alliance_id,
                    'text' => $alliance->name,
                ];
            });

        return response()->json([
            'results' => $alliances,
        ]);

    }

    /**
     * @param  Request  $request
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

    /***
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getItems(Request $request)
    {
        if ($request->query('_type', 'query') == 'find') {
            $item = InvType::find($request->query('q', 0));

            return response()->json([
                'id' => $item->typeID,
                'text' => $item->typeName,
            ]);
        }

        $items = InvType::where('typeName', 'like', '%' . $request->query('q', '') . '%')
            ->where('published', true);

        if (! empty($request->query('market_filters', [])))
            $items = $items->whereIn('marketGroupID', $request->query('market_filters'));

        $items = $items->orderBy('typeName')
            ->get()
            ->map(function ($item, $key) {
                return [
                    'id'   => $item->typeID,
                    'text' => $item->typeName,
                    'img'  => img('types', 'icon', $item->typeID, 32, ['class' => 'img-circle eve-icon small-icon'], false),
                ];
            });

        return response()->json([
            'results' => $items,
        ]);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSkills(Request $request)
    {
        if ($request->query('_type', 'query') == 'find') {
            $skill = InvType::find($request->query('q', 0));

            return response()->json([
                'id' => $skill->typeID,
                'text' => $skill->typeName,
            ]);
        }

        $skills = InvType::whereHas('group', function (Builder $query) {
                $query->where('categoryID', self::SKILL_CATEGORY_ID);
            })
            ->where('typeName', 'like', '%' . $request->query('q', '') . '%')
            ->where('published', true)
            ->orderBy('typeName')
            ->get()
            ->map(function ($skill, $key) {
                return [
                    'id' => $skill->typeID,
                    'text' => $skill->typeName,
                ];
            });

        return response()->json([
            'results' => $skills,
        ]);
    }

    /***
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRegions(Request $request)
    {
        if ($request->query('_type', 'query') == 'find') {
            $region = Region::find($request->query('q', 0));

            if (is_null($region)) {
                return response()->json();
            }

            return response()->json([
                'id'   => $region->region_id,
                'text' => $region->name,
            ]);
        }

        $regions = Region::whereRaw('name LIKE ?', ["%{$request->query('q', '')}%"])
            ->select('region_id', 'name')
            ->orderBy('name')
            ->get()
            ->map(function ($region) {
                return [
                    'id'   => $region->region_id,
                    'text' => $region->name,
                ];
            });

        return response()->json([
            'results' => $regions,
        ]);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getConstellations(Request $request)
    {
        if ($request->query('_type', 'query') == 'find') {
            $constellation = Constellation::find($request->query('q', 0));

            if (is_null($constellation)) {
                return response()->json();
            }

            return response()->json([
                'id'   => $constellation->constellation_id,
                'text' => $constellation->name,
            ]);
        }

        $constellations = Constellation::whereRaw('name LIKE ?', ["%{$request->query('q', '')}%"]);

        if ($request->query('region_filter', 0) != 0)
            $constellations->where('region_id', intval($request->query('region_filter')));

        $constellations = $constellations->select('constellation_id', 'name')
            ->orderBy('name')
            ->get()
            ->map(function ($constellation) {
                return [
                    'id'   => $constellation->constellation_id,
                    'text' => $constellation->name,
                ];
            });

        return response()->json([
            'results' => $constellations,
        ]);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getScopes(Request $request)
    {
        $scopes = config('eveapi.scopes', []);

        if ($request->query('_type', 'query') == 'find') {
            if (in_array($request->query('q', ''), $scopes))
                return response()->json([
                    'id' => $request->query('q', ''),
                    'text' => $request->query('q', ''),
                ]);

            return response()->json();
        }

        if (! empty($request->query('q', '')))
            $scopes = array_filter($scopes, function ($scope) use ($request) {
                return strpos($scope, strtolower($request->query('q'))) !== false;
            });

        $scopes = collect($scopes)->map(function ($scope) {
            return [
                'id' => $scope,
                'text' => $scope,
            ];
        })->values();

        return response()->json([
            'results' => $scopes,
        ]);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSystems(Request $request)
    {
        if ($request->query('_type', 'query') == 'find') {
            $system = SolarSystem::find($request->query('q', 0));

            if (is_null($system)) {
                return response()->json();
            }

            return response()->json([
                'id'   => $system->system_id,
                'text' => $system->name,
            ]);
        }

        $systems = SolarSystem::whereRaw('name LIKE ?', ["%{$request->query('q', '')}%"]);

        if ($request->query('region_filter', 0) != 0)
            $systems = $systems->where('region_id', intval($request->query('region_filter')));

        if ($request->query('constellation_filter', 0) != 0)
            $systems = $systems->where('constellation_id', intval($request->query('constellation_filter')));

        $systems = $systems->select('system_id', 'name')
            ->orderBy('name')
            ->get()
            ->map(function ($system) {
                return [
                    'id'       => $system->system_id,
                    'text'     => $system->name,
                ];
            });

        return response()->json([
            'results' => $systems,
        ]);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCorporationRoles(Request $request)
    {
        $roles = CorporationRole::where('type', 'roles')
            ->whereRaw('role LIKE ?', ["%{$request->query('q', '')}%"]);

        $roles = $roles->select('role')
            ->distinct('role')
            ->orderBy('role')
            ->get()
            ->map(function ($role) {
                return [
                    'id'       => $role->role,
                    'text'     => $role->role,
                ];
            });

        return response()->json([
            'results' => $roles,
        ]);
    }
}
