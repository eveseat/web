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

namespace Seat\Web\Http\DataTables\Corporation\Industrial\Blueprints;

use Seat\Eveapi\Models\Corporation\CorporationBlueprint;
use Seat\Eveapi\Models\Corporation\CorporationDivision;
use Seat\Web\Http\DataTables\Common\IColumn;
use Seat\Web\Http\DataTables\Common\Industrial\AbstractBlueprintDataTable;
use Seat\Web\Http\DataTables\Corporation\Industrial\Blueprints\Columns\LocationFlag;
use Seat\Web\Http\DataTables\Corporation\Industrial\Blueprints\Columns\Station;

/**
 * Class DataTable.
 *
 * @package Seat\Web\Http\DataTables\Corporation\Industrial
 */
class DataTable extends AbstractBlueprintDataTable
{
    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return CorporationBlueprint::with('container', 'station', 'structure', 'type')
            ->select('type_id', 'location_flag', 'location_id', 'quantity', 'time_efficiency', 'material_efficiency', 'runs');
    }

    /**
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return parent::html()
            ->postAjax([
                'data' => 'function(d) { d.filters = {}; $("[data-filter-field].dt-filters.active").each(function (i, e) { var a = $(e); var field = a.data("filter-field"); var value = a.data("filter-value"); if (! d.filters[field]) { d.filters[field] = []; } d.filters[field].push(value); }); }',
            ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getDivisions()
    {
        $divisions = CorporationDivision::where('type', 'hangar')
            ->where('corporation_id', request()->route()->parameter('corporation')->corporation_id)
            ->get();

        return $divisions;
    }

    /**
     * @param  self  $table
     * @return \Seat\Web\Http\DataTables\Common\IColumn
     */
    protected function getLocationFlagColumn($table): IColumn
    {
        return new LocationFlag($table);
    }

    /**
     * @param  self  $table
     * @return \Seat\Web\Http\DataTables\Common\IColumn
     */
    protected function getStationColumn($table): IColumn
    {
        return new Station($table);
    }
}
