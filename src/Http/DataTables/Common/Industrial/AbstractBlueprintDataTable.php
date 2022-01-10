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

namespace Seat\Web\Http\DataTables\Common\Industrial;

use Seat\Web\Http\DataTables\Common\IColumn;
use Yajra\DataTables\Services\DataTable;

/**
 * Class AbstractBlueprintDataTable.
 *
 * @package Seat\Web\Http\DataTables\Common\Industrial
 */
abstract class AbstractBlueprintDataTable extends DataTable
{
    /**
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function ajax()
    {
        $station_column = $this->getStationColumn($this);
        $location_column = $this->getLocationFlagColumn($this);

        return datatables()
            ->eloquent($this->applyScopes($this->query()))
            ->editColumn('type.typeName', function ($row) {
                return view('web::partials.type', [
                    'type_id' => $row->type->typeID,
                    'type_name' => $row->type->typeName,
                    'variation' => $row->quantity == -1 ? 'bp' : 'bpc',
                ])->render();
            })
            ->editColumn('location_flag', $location_column)
            ->editColumn('quantity', function ($row) {
                if ($row->quantity < 0)
                    return 1;

                return $row->quantity;
            })
            ->editColumn('runs', function ($row) {
                if ($row->runs == -1)
                    return '<span class="badge badge-primary">original</span>';

                return $row->runs;
            })
            ->addColumn('station', $station_column)
            ->filterColumn('location_flag', $location_column)
            ->filterColumn('station', $station_column)
            ->setRowClass(function ($row) {
                if (in_array('AssetSafety', [$row->location_flag]))
                    return 'table-danger';

                if (in_array('CorpDeliveries', [$row->location_flag]))
                    return 'table-warning';

                return '';
            })
            ->rawColumns(['type.typeName', 'runs'])
            ->make(true);
    }

    /**
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->postAjax()
            ->columns($this->getColumns())
            ->orderBy(0, 'asc')
            ->addTableClass('table-striped table-hover');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    abstract public function query();

    /**
     * @param  self  $table
     * @return \Seat\Web\Http\DataTables\Common\IColumn
     */
    abstract protected function getLocationFlagColumn($table): IColumn;

    /**
     * @param  self  $table
     * @return \Seat\Web\Http\DataTables\Common\IColumn
     */
    abstract protected function getStationColumn($table): IColumn;

    /**
     * @return array
     */
    public function getColumns()
    {
        return [
            ['data' => 'type.typeName', 'title' => trans_choice('web::seat.type', 1)],
            ['data' => 'location_flag', 'title' => trans_choice('web::assets.division', 1)],
            ['data' => 'quantity', 'title' => trans('web::seat.quantity')],
            ['data' => 'time_efficiency', 'title' => trans('web::industry.time_efficiency')],
            ['data' => 'material_efficiency', 'title' => trans('web::industry.material_efficiency')],
            ['data' => 'runs', 'title' => trans('web::industry.runs')],
            ['data' => 'station', 'title' => trans('web::assets.station_or_structure'), 'orderable' => false],
        ];
    }
}
