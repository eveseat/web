<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2022 Leon Jacobs
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
use Yajra\DataTables\Contracts\DataTableScope;

/**
 * Class AllianceScope.
 *
 * This is a generic scope for corporation tables.
 * It will restrict returned data based on both requested corporation ID.
 *
 * @package Seat\Web\Http\DataTables\Scopes
 */
class AllianceScope implements DataTableScope
{
    /**
     * @var string
     */
    private $ability;

    /**
     * @var array
     */
    private $requested_alliances;

    /**
     * AllianceScope constructor.
     *
     * @param  string|null  $ability
     * @param  int[]|null  $corporation_ids
     */
    public function __construct(?string $ability = null, ?array $alliance_ids = null)
    {
        $this->ability = $ability;
        $this->requested_alliances = $alliance_ids;
    }

    /**
     * Apply a query scope.
     *
     * @param  \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder  $query
     * @return mixed
     */
    public function apply($query)
    {
        // extract querying table (from)
        $table = $query->getQuery()->from;

        if ($this->requested_alliances != null) {
            $alliance_ids = collect($this->requested_alliances)->filter(function ($item) {
                return Gate::allows($this->ability, [$item]);
            });

            return $alliance_ids->count() == count($this->requested_alliances) ?
                $query->whereIn($table . '.alliance_id', $this->requested_alliances) :
                $query->whereIn($table . '.alliance_id', []);
        }

        if (auth()->user()->isAdmin())
            return $query;

        // collect metadata related to required permission
        $permissions = auth()->user()->roles()->with('permissions')->get()
            ->pluck('permissions')
            ->flatten()
            ->filter(function ($permission) {
                if (empty($this->ability))
                    return strpos($permission->title, 'alliance.') === 0;

                return $permission->title == $this->ability;
            });

        // in case at least one permission is granted without restrictions, return all
        if ($permissions->filter(function ($permission) { return ! $permission->hasFilters(); })->isNotEmpty())
            return $query;

        // extract entity ids and group by entity type
        $map = $permissions->map(function ($permission) {
            $filters = json_decode($permission->pivot->filters);

            return [
                'alliances'    => collect($filters->alliance ?? [])->pluck('id')->toArray(),
            ];
        });

        $alliance_ids = $map->pluck('alliances')->flatten()->toArray();

        return $query->whereIntegerInRaw($table . '.alliance_id', $alliance_ids);
    }
}
