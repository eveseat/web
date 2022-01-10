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
 * Class MarketOrderTypeScope.
 *
 * This is a filter scope for market tables.
 * It will restrict returned data based on order type.
 *
 * @package Seat\Web\Http\DataTables\Scopes\Filters
 */
class MarketOrderTypeScope implements DataTableScope
{
    /**
     * @var array
     */
    private $order_type = [];

    /**
     * MarketOrderTypeScope constructor.
     *
     * @param  array|null  $order_type
     */
    public function __construct(?array $order_type)
    {
        $this->order_type = $order_type ?: [];
    }

    /**
     * Apply a query scope.
     *
     * @param  \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder  $query
     * @return mixed
     */
    public function apply($query)
    {
        if (in_array(0, $this->order_type))
            return $query->where(function ($sub_query) {
                $sub_query->whereIn('is_buy_order', $this->order_type)
                    ->orWhereNull('is_buy_order');
            });

        return $query->whereIn('is_buy_order', $this->order_type);
    }
}
