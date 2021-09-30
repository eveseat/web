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

namespace Seat\Web\Http\Validation;

use Illuminate\Foundation\Http\FormRequest;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Corporation\CorporationInfo;

/**
 * Class StandingsExistingElementAdd.
 *
 * @package Seat\Web\Http\Validation
 */
class StandingsExistingElementAdd extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'id'          => 'required|exists:standings_profiles,id',
            'character'   => 'nullable|in:' .
                $this->getAllCharactersWithAffiliations()->pluck('character_id')->implode(','),
            'corporation' => 'nullable|in:' .
                $this->getAllCorporationsWithAffiliationsAndFilters()->pluck('corporation_id')->implode(','),
        ];
    }

    /**
     * Query the database for characters, keeping filters,
     * permissions and affiliations in mind.
     *
     * @return \Illuminate\Support\Collection
     */
    private function getAllCharactersWithAffiliations()
    {
        return $this->addCharacterPermissionScope(CharacterInfo::select('character_id'), 'character.sheet')
            ->get();
    }

    /**
     * Return the corporations for which a user has access.
     *
     * @return \Illuminate\Support\Collection
     */
    private function getAllCorporationsWithAffiliationsAndFilters()
    {
        return $this->addCorporationPermissionScope(CorporationInfo::select('corporation_id'), 'corporation.summary')
            ->get();
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $ability
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function addCharacterPermissionScope($query, string $ability)
    {
        if (auth()->user()->isAdmin())
            return $query;

        // collect metadata related to required permission
        $permissions = auth()->user()->roles()->with('permissions')->get()
            ->pluck('permissions')
            ->flatten()
            ->filter(function ($permission) use ($ability) {
                return $permission->title == $ability;
            });

        // in case at least one permission is granted without restrictions, return all
        if ($permissions->filter(function ($permission) { return ! $permission->hasFilters(); })->isNotEmpty())
            return $query;

        // extract entity ids and group by entity type
        $map = $permissions->map(function ($permission) {
            $filters = json_decode($permission->pivot->filters);

            return [
                'characters'   => collect($filters->character ?? [])->pluck('id')->toArray(),
                'corporations' => collect($filters->corporation ?? [])->pluck('id')->toArray(),
                'alliances'    => collect($filters->alliance ?? [])->pluck('id')->toArray(),
            ];
        });

        // collect all corporations ID
        return $query->where(function ($sub_query) use ($map) {
            $character_ids = array_merge(
                auth()->user()->associatedCharacterIds(),
                $map->pluck('characters')->flatten()->toArray(),
            );

            $sub_query->whereHas('affiliation', function ($affiliation) use ($map) {
                $affiliation->whereIn('corporation_id', $map->pluck('corporations')->flatten()->toArray());
                $affiliation->orWhereIn('alliance_id', $map->pluck('alliances')->flatten()->toArray());
            });

            $sub_query->orWhereIn('character_id', $character_ids);
        });
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $ability
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function addCorporationPermissionScope($query, string $ability)
    {
        if (auth()->user()->isAdmin())
            return $query;

        // collect metadata related to required permission
        $permissions = auth()->user()->roles()->with('permissions')->get()
            ->pluck('permissions')
            ->flatten()
            ->filter(function ($permission) use ($ability) {
                return $permission->title == $ability;
            });

        // in case at least one permission is granted without restrictions, return all
        if ($permissions->filter(function ($permission) { return ! $permission->hasFilters(); })->isNotEmpty())
            return $query;

        // extract entity ids and group by entity type
        $map = $permissions->map(function ($permission) {
            $filters = json_decode($permission->pivot->filters);

            return [
                'characters'   => collect($filters->character ?? [])->pluck('id')->toArray(),
                'corporations' => collect($filters->corporation ?? [])->pluck('id')->toArray(),
                'alliances'    => collect($filters->alliance ?? [])->pluck('id')->toArray(),
            ];
        });

        // collect at least user owned characters
        $owned_range = auth()->user()->associatedCharacterIds();

        // collect all characters ID
        $characters_range = array_merge($owned_range, $map->pluck('characters')->flatten()->toArray());

        // collect all corporations ID
        $corporations_range = $map->pluck('corporations')->flatten()->toArray();

        // collect all alliances ID
        $alliances_range = $map->pluck('alliances')->flatten()->toArray();

        return $query->where(function ($sub_query) use ($characters_range, $corporations_range, $alliances_range) {
            return $sub_query->whereIn('ceo_id', $characters_range)
                ->orWhereIn('alliance_id', $alliances_range)
                ->orWhereIn('corporation_id', $corporations_range);
        });
    }
}
