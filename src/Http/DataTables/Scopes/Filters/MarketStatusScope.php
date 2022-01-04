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

namespace Seat\Web\Http\DataTables\Scopes\Filters;

use Yajra\DataTables\Contracts\DataTableScope;

/**
 * Class MarketStatusScope.
 *
 * This is a filter scope for market tables.
 * It will restrict returned data based on status.
 *
 * @package Seat\Web\Http\DataTables\Scopes\Filters
 */
class MarketStatusScope implements DataTableScope
{
    /**
     * @var array
     */
    private $status = [];

    /**
     * MarketStatusScope constructor.
     *
     * @param  array|null  $status
     */
    public function __construct(?array $status)
    {
        $this->status = $status ?: [];
    }

    /**
     * Apply a query scope.
     *
     * @param  \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder  $query
     * @return mixed
     */
    public function apply($query)
    {
        return $query->where(function ($sub_query) {
            if (in_array('pending', $this->status)) {
                $sub_query->where(function ($query) {
                    $query->where('volume_remain', '>', 0)
                        ->whereRaw('DATE_ADD(issued, INTERVAL duration DAY) >= NOW()');
                });
            }

            if (in_array('expired', $this->status)) {
                $sub_query->orWhere(function ($query) {
                    $query->where('volume_remain', '>', 0)
                        ->whereRaw('DATE_ADD(issued, INTERVAL duration DAY) < NOW()');
                });
            }

            if (in_array('completed', $this->status)) {
                $sub_query->orWhere(function ($query) {
                    $query->where('volume_remain', 0)
                        ->whereRaw('DATE_ADD(issued, INTERVAL duration DAY) < NOW()');
                });
            }
        });
    }
}
