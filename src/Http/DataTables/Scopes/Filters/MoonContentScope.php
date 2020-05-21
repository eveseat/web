<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2020 Leon Jacobs
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
 * RegionScope
 * 
 * Filters DataTable data by regionID
 */
class MoonContentScope implements DataTableScope
{

    const UBIQUITOUS = 2396;
    const COMMON = 2397;
    const UNCOMMON = 2398;
    const RARE = 2400;
    const EXCEPTIONAL = 2401;

    public function __construct($moonContents, $inclusive)
    {
        $this->moonContents = $moonContents;
        $this->inclusive = $inclusive;
    }

    /**
     * Apply a query scope
     * 
     * @param \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $query
     * @return mixed
     **/
    public function apply($query) {
        if (!empty($this->moonContents)) {
            $query->whereHas('moon_contents', function ($subQuery) {
                foreach ($this->moonContents as $oreType) {
                    switch ($oreType) {
                        case 'ubiquitous' : {
                            $subQuery->whereHas('type', function ($type) {
                                $type->where('marketGroupID', self::UBIQUITOUS);
                            });
                            break;
                        }
                        case 'common' : {
                            $subQuery->whereHas('type', function ($type) {
                                $type->where('marketGroupID', self::COMMON);
                            });
                            break;
                        }
                        case 'uncommon' : {
                            $subQuery->whereHas('type', function ($type) {
                                $type->where('marketGroupID', self::UNCOMMON);
                            });
                            break;
                        }
                        case 'rare' : {
                            $subQuery->whereHas('type', function ($type) {
                                $type->where('marketGroupID', self::RARE);
                            });
                            break;
                        }
                        case 'exceptional' : {
                            $subQuery->whereHas('type', function ($type) {
                                $type->where('marketGroupID', self::EXCEPTIONAL);
                            });
                            break;
                        }
                    }
                }
            });
        }
        return $query;
    }
}