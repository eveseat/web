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

namespace Seat\Web\Http\DataTables\Corporation\Intel\Assets\Columns;

use Illuminate\Database\Eloquent\Model;
use Seat\Eveapi\Models\Assets\CorporationAsset;
use Seat\Web\Http\DataTables\Common\AbstractColumn;

/**
 * Class Station.
 *
 * @package Seat\Web\Http\DataTables\Corporation\Intel\Assets\Columns
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
        if ($row->location_type == 'station')
            return $row->station->name;

        // in case item has CorpSAG flag, this mean it is inside root division level
        //
        // alternatively, stuff can be come from an office and being in safety
        // in such case, we move to the station relation instead the parent container
        if (in_array($row->location_flag, ['CorpSAG1', 'CorpSAG2', 'CorpSAG3', 'CorpSAG4', 'CorpSAG5', 'CorpSAG6', 'CorpSAG7', 'CorpDeliveries']))
            return $this->getLocationNameFromDivision($row);

        if ($row->location_type == 'other' && in_array($row->location_flag, ['AutoFit', 'Unlocked'])) {

            // in case item has CorpSAG flag, this mean it is inside root division level
            //
            // alternatively, stuff can be come from an office and being in safety
            // in such case, we move to the station relation instead the parent container
            if (in_array($row->container->location_flag, ['CorpSAG1', 'CorpSAG2', 'CorpSAG3', 'CorpSAG4', 'CorpSAG5', 'CorpSAG6', 'CorpSAG7']))
                return $this->getLocationNameFromDivision($row->container);

            if (in_array($row->container->location_flag, ['CorpDeliveries', 'AssetSafety']))
                return $row->container->station->name;
        }

        if ($row->location_type == 'solar_system')
            return $row->name;

        return sprintf('%d (%d)', $row->item_id, $row->location_id);
    }

    /**
     * Search in a column cell.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder  $query
     * @param  string  $keyword
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
        // second level of search - the item is inside a division
        //
        $query->orWhereHas('container', function ($container) use ($keyword) {
            $container->whereRaw('name LIKE ?', ["%{$keyword}%"]);
        })->orWhereHas('container.station', function ($station) use ($keyword) {
            $station->whereRaw('name LIKE ?', ["%{$keyword}%"]);
        })->orWhereHas('container.structure', function ($structure) use ($keyword) {
            $structure->whereRaw('name LIKE ?', ["%{$keyword}%"]);
        });
        //
        // last level of search - the item is inside a container
        //
        $query->orWhereHas('container.container', function ($container) use ($keyword) {
            $container->whereRaw('name LIKE ?', ["%{$keyword}%"]);
        })->orWhereHas('container.container.station', function ($station) use ($keyword) {
            $station->whereRaw('name LIKE ?', ["%{$keyword}%"]);
        })->orWhereHas('container.container.structure', function ($structure) use ($keyword) {
            $structure->whereRaw('name LIKE ?', ["%{$keyword}%"]);
        });

        return $query;
    }

    /**
     * Return station or structure name from an asset which is inside a division.
     *
     * @param  \Seat\Eveapi\Models\Assets\CorporationAsset  $item
     * @return string
     */
    private function getLocationNameFromDivision(CorporationAsset $item)
    {
        // item > division > office > station / structure
        if (in_array($item->container->location_flag, ['Impounded', 'OfficeFolder'])) {
            if ($item->container->location_type == 'station')
                return $item->container->station->name;

            if ($item->container->location_flag == 'Impounded' && $item->container->location_type == 'other')
                return $item->container->structure->name;

            if ($item->container->location_flag == 'OfficeFolder' && $item->container->location_type == 'item')
                return $item->container->structure->name;

            return $item->container->container->name;
        }

        // item > division > safety > station
        if ($item->container->location_flag == 'AssetSafety')
            return $item->container->station->name;

        if ($item->location_flag == 'CorpDeliveries') {
            switch ($item->location_type) {
                case 'other':
                case 'item':
                    return $item->structure->name;
                case 'station':
                    return $item->station->name;
            }
        }

        return sprintf('%d (%d)', $item->item_id, $item->location_id);
    }
}
