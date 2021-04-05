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

namespace Seat\Web\Http\DataTables\Scopes;

use Illuminate\Support\Facades\Gate;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Eveapi\Models\Corporation\CorporationRole;
use Yajra\DataTables\Contracts\DataTableScope;

/**
 * Class CorporationScope.
 *
 * This is a generic scope for corporation tables.
 * It will restrict returned data based on both requested corporation ID.
 *
 * @package Seat\Web\Http\DataTables\Scopes
 */
class CorporationScope implements DataTableScope
{
    /**
     * @var string
     */
    private $ability;

    /**
     * @var array
     */
    private $requested_corporations;

    /**
     * CorporationScope constructor.
     *
     * @param string|null $ability
     * @param int[]|null $corporation_ids
     */
    public function __construct(?string $ability = null, ?array $corporation_ids = null)
    {
        $this->ability = $ability;
        $this->requested_corporations = $corporation_ids;
    }

    /**
     * Apply a query scope.
     *
     * @param \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $query
     * @return mixed
     */
    public function apply($query)
    {
        // extract querying table (from)
        $table = $query->getQuery()->from;

        if ($this->requested_corporations != null) {
            $corporation_ids = collect($this->requested_corporations)->filter(function ($item) {
                return Gate::allows($this->ability, [$item]);
            });

            return $corporation_ids->count() == count($this->requested_corporations) ?
                $query->whereIn($table . '.corporation_id', $this->requested_corporations) :
                $query->whereIn($table . '.corporation_id', []);
        }

        if (auth()->user()->isAdmin())
            return $query;

        // collect metadata related to required permission
        $permissions = auth()->user()->roles()->with('permissions')->get()
            ->pluck('permissions')
            ->flatten()
            ->filter(function ($permission) {
                if (empty($this->ability))
                    return strpos($permission->title, 'corporation.') === 0;

                return $permission->title == $this->ability;
            });

        // in case at least one permission is granted without restrictions, return all
        if ($permissions->filter(function ($permission) { return ! $permission->hasFilters(); })->isNotEmpty())
            return $query;

        // extract entity ids and group by entity type
        $map = $permissions->map(function ($permission) {
            $filters = json_decode($permission->pivot->filters);

            return [
                'corporations' => collect($filters->corporation ?? [])->pluck('id')->toArray(),
                'alliances'    => collect($filters->alliance ?? [])->pluck('id')->toArray(),
            ];
        });

        $owner_range = CorporationInfo::whereIn('ceo_id', auth()->user()->associatedCharacterIds())
            ->select('corporation_id')->get()->pluck('corporation_id')->toArray();

        $corporations_range = $map->pluck('corporations')->flatten()->toArray();

        $alliances_range = CorporationInfo::whereIn('alliance_id', $map->pluck('alliances')->flatten()->toArray())
            ->select('corporation_id')->get()->pluck('corporation_id')->toArray();

        $associated_corporations = auth()->user()->characters->load('affiliation')->pluck('affiliation.corporation_id')->values()->toArray();
        $director_range = CorporationRole::whereIn('corporation_id', $associated_corporations)->whereIn('character_id', auth()->user()->associatedCharacterIds())
            ->where('role', 'Director')->where('type', 'roles')
            ->pluck('corporation_id')->values()->toArray();

        // merge all collected characters IDs in a single array and apply filter
        $corporation_ids = array_merge($owner_range, $corporations_range, $alliances_range, $director_range);

        return $query->whereIn($table . '.corporation_id', $corporation_ids);
    }
}
