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

namespace Seat\Web\Http\DataTables\Corporation\Industrial\Blueprints\Columns;

use Illuminate\Database\Eloquent\Model;
use Seat\Web\Http\DataTables\Common\AbstractColumn;

/**
 * Class Station.
 *
 * @package Seat\Web\Http\DataTables\Corporation\Industrial\Blueprints\Columns
 */
class Station extends AbstractColumn
{
    /**
     * Draw a column cell.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $row
     * @return string;
     */
    public function draw(Model $row)
    {
        // in case the location is in station range -> use station relation.
        if ($row->location_id < 64000000)
            return $row->station->name;

        // the blueprint is inside a container - from a station
        if ($row->container->container->location_type == 'station')
            return $row->container->container->station->name;

        // the blueprint is inside a container - from a structure
        if ($row->container->container->location_type == 'other')
            return $row->container->container->structure->name;

        // the blueprint is inside a division - from a station
        if ($row->container->location_type == 'station')
            return $row->container->station->name;

        // the blueprint is inside a division - from a structure
        if ($row->container->location_type == 'other')
            return $row->container->structure->name;

        // the blueprint is somewhere around a structure (ie: POS)
        return $row->structure->name;
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder  $query
     * @param  string  $keyword
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    public function search($query, string $keyword)
    {
        // search item directly inside station or structure having same name
        $query->whereHas('station', function ($station) use ($keyword) {
            $station->whereRaw('name LIKE ?', ["%{$keyword}%"]);
        })->orWhereHas('structure', function ($structure) use ($keyword) {
            $structure->whereRaw('name LIKE ?', ["%{$keyword}%"]);
        });

        $query->orWhereHas('container.station', function ($station) use ($keyword) {
            $station->whereRaw('name LIKE ?', ["%{$keyword}%"]);
        })->orWhereHas('container.structure', function ($structure) use ($keyword) {
            $structure->whereRaw('name LIKE ?', ["%{$keyword}%"]);
        });

        $query->orWhereHas('container.container.station', function ($station) use ($keyword) {
            $station->whereRaw('name LIKE ?', ["%{$keyword}%"]);
        })->orWhereHas('container.container.structure', function ($structure) use ($keyword) {
            $structure->whereRaw('name LIKE ?', ["%{$keyword}%"]);
        });

        return $query;
    }
}
