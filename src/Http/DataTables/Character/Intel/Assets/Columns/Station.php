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

namespace Seat\Web\Http\DataTables\Character\Intel\Assets\Columns;

use Illuminate\Database\Eloquent\Model;
use Seat\Web\Http\DataTables\Common\AbstractColumn;

/**
 * Class Station.
 *
 * @package Seat\Web\Http\DataTables\Character\Intel\Assets\Columns
 */
class Station extends AbstractColumn
{
    /**
     * Draw a column cell.
     *
     * @param \Illuminate\Database\Eloquent\Model $row
     * @return string;
     */
    public function draw(Model $row)
    {
        if ($row->location_type == 'station')
            return $row->station->name;

        if ($row->location_type == 'item') {
            if (in_array($row->location_flag, ['Deliveries', 'Hangar'])) {
                if ($row->container->location_flag == 'AssetSafety')
                    return $row->container->station->name;

                return $row->structure->name;
            }

            if ($row->location_flag == 'Unlocked') {
                if ($row->container->location_flag == 'Hangar') {
                    switch ($row->container->location_type) {
                        case 'station':
                            return $row->container->station->name;
                        case 'item':
                            return $row->container->structure->name;
                    }
                }
            }

            if ($row->location_flag == 'AssetSafety')
                return $row->structure->name;

            // assume item is fit on a ship
            return $row->container->name ?: trans('web::seat.unknown');
        }

        if ($row->location_type == 'other') {
            if ($row->location_flag == 'Hangar') {
                if ($row->container->location_flag == 'AssetSafety')
                    return $row->container->station->name;

                return $row->structure->name;
            }

            if ($row->container->location_flag == 'Hangar') {
                if ($row->container->location_type == 'station')
                    return $row->container->station->name;

                return $row->container->structure->name;
            }
        }

        return sprintf('%d (%d)', $row->item_id, $row->location_id);
    }

    /**
     * Search in a column cell.
     *
     * @param \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder $query
     * @param string $keyword
     * @return \Illuminate\Database\Query\Builder
     */
    public function search($query, string $keyword)
    {
        //
        // first level of search - the item is a root level
        //
        $query->whereHas('station', function ($station) use ($keyword) {
            $station->whereRaw('name LIKE ?', ["%{$keyword}%"]);
        })->orWhereHas('structure', function ($structure) use ($keyword) {
            $structure->whereRaw('name LIKE ?', ["%{$keyword}%"]);
        });
        //
        // second level of search - the item is inside a container
        //
        $query->orWhereHas('container', function ($container) use ($keyword) {
            $container->whereRaw('name LIKE ?', ["%{$keyword}%"]);
        })->orWhereHas('container.station', function ($station) use ($keyword) {
            $station->whereRaw('name LIKE ?', ["%{$keyword}%"]);
        })->orWhereHas('container.structure', function ($structure) use ($keyword) {
            $structure->whereRaw('name LIKE ?', ["%{$keyword}%"]);
        });

        return $query;
    }
}
