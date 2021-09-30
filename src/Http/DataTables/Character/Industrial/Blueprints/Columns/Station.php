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

namespace Seat\Web\Http\DataTables\Character\Industrial\Blueprints\Columns;

use Illuminate\Database\Eloquent\Model;
use Seat\Web\Http\DataTables\Common\AbstractColumn;

/**
 * Class Station.
 *
 * @package Seat\Web\Http\DataTables\Character\Industrial\Blueprints\Columns
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

        // in case the flag is Hangar, item might be in a container or a structure.
        if ($row->location_flag == 'Hangar') {

            // container is inside a station - use station relation.
            if ($row->container->location_type == 'station')
                return $row->container->station->name;

            // container is inside a structure - use structure relation.
            if ($row->container->location_type == 'other')
                return $row->container->structure->name;

            // item is inside a structure - use structure relation.
            return $row->structure->name;
        }

        if ($row->location_flag == 'FleetHangar') {

            // the blueprint is inside a container
            if ($row->container->location_flag == 'FleetHangar') {

                // the container is inside a station - use station relation
                if ($row->container->container->location_type == 'station')
                    return $row->container->container->station->name;

                // the container is inside a structure - use structure relation
                return $row->container->container->structure->name;
            }
        }

        return $row->location_id;
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder  $query
     * @param  string  $keyword
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    public function search($query, string $keyword)
    {
        // blueprint is directly put inside the station / structure
        $query->whereHas('station', function ($sub_query) use ($keyword) {
            $sub_query->whereRaw('name LIKE ?', ["%{$keyword}%"]);
        })->orWhereHas('structure', function ($sub_query) use ($keyword) {
            $sub_query->whereRaw('name LIKE ?', ["%{$keyword}%"]);
        });

        // blueprint is stored inside a ship or a container
        $query->orWhereHas('container.station', function ($sub_query) use ($keyword) {
            $sub_query->whereRaw('name LIKE ?', ["%{$keyword}%"]);
        })->orWhereHas('container.structure', function ($sub_query) use ($keyword) {
            $sub_query->whereRaw('name LIKE ?', ["%{$keyword}%"]);
        });

        // blueprint is stored inside a container - which is inside a ship
        $query->orWhereHas('container.container.station', function ($sub_query) use ($keyword) {
            $sub_query->whereRaw('name LIKE ?', ["%{$keyword}%"]);
        })->orWhereHas('container.container.structure', function ($sub_query) use ($keyword) {
            $sub_query->whereRaw('name LIKE ?', ["%{$keyword}%"]);
        });

        return $query;
    }
}
