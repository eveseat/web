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

use Yajra\DataTables\Contracts\DataTableScope;

/**
 * Class CorporationAssetDivisionsScope.
 *
 * @package Seat\Web\Http\DataTables\Scopes
 */
class CorporationAssetDivisionsScope implements DataTableScope
{
    /**
     * @var array
     */
    private $divisions = [];

    /**
     * CorporationAssetDivisionsScope constructor.
     *
     * @param  array  $divisions
     */
    public function __construct(array $divisions)
    {
        $this->divisions = $divisions;
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|mixed
     */
    public function apply($query)
    {
        $location_flags = ['AssetSafety', 'CorpDeliveries'];

        foreach ($this->divisions as $division_id) {
            array_push($location_flags, sprintf('CorpSAG%d', $division_id));
        }

        return $query->whereIn('location_flag', $location_flags);
    }
}
