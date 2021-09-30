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

namespace Seat\Web\Http\DataTables\Scopes\Filters;

use Yajra\DataTables\Contracts\DataTableScope;

/**
 * ConstellationScope.
 *
 * Filters DataTable data by constellationID
 */
class ConstellationScope implements DataTableScope
{
    /**
     * @var int
     */
    private $constellation_id;

    /**
     * ConstellationScope constructor.
     *
     * @param  int  $constellation_id
     */
    public function __construct(int $constellation_id) {
        $this->constellation_id = $constellation_id;
    }

    /**
     * Apply a query scope.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    public function apply($query) {
        return $query->whereHas('moon', function ($sub_query) {
            $sub_query->where('moons.constellation_id', $this->constellation_id);
        });
    }
}
