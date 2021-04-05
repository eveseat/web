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
 * Class ContractTypeScope.
 *
 * This is a filter scope for contract tables.
 * It will restrict returned data based on type.
 *
 * @package Seat\Web\Http\DataTables\Scopes\Filters
 */
class ContractTypeScope implements DataTableScope
{
    /**
     * @var array
     */
    private $status = [];

    /**
     * ContractTypeScope constructor.
     *
     * @param array|null $type
     */
    public function __construct(?array $type)
    {
        $this->status = $type ?: [];
    }

    /**
     * Apply a query scope.
     *
     * @param \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $query
     * @return mixed
     */
    public function apply($query)
    {
        return $query->whereHas('detail', function ($sub_query) {
            return $sub_query->whereIn('type', $this->status);
        });
    }
}
