<?php
/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015, 2016, 2017, 2018, 2019  Leon Jacobs
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

namespace Seat\Web\Http\DataTables\Common\Military;

use Yajra\DataTables\Services\DataTable;

/**
 * Class AbstractFittingDataTable
 *
 * @package Seat\Web\Http\DataTables\Common\Military
 */
abstract class AbstractFittingDataTable extends DataTable
{
    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function ajax()
    {
        return datatables()
            ->eloquent($this->applyScopes($this->query()))
            ->addColumn('type', function ($row) {
                return view('web::partials.type', ['type_id' => $row->ship->typeID, 'type_name' => $row->ship->typeName]);
            })
            ->addColumn('items', function ($row) {
                return $row->items->count();
            })
            ->addColumn('hull_estimated_value', function ($row) {
                return number($row->ship->price->adjusted_price);
            })
            ->addColumn('fitting_estimated_value', function ($row) {
                return number($row->estimated_price);
            })
            ->editColumn('action', function ($row) {
                return view('web::common.fittings.buttons.insurance', ['type_id' => $row->ship->typeID]);
            })
            ->rawColumns(['type', 'action'])
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
            ->addAction();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public abstract function query();

    /**
     * @return array
     */
    public function getColumns()
    {
        return [
            ['data' => 'name', 'title' => trans('web::fitting.name')],
            ['data' => 'type', 'title' => trans('web::fitting.type')],
            ['data' => 'items', 'title' => trans('web::fitting.items')],
            ['data' => 'hull_estimated_value', 'title' => trans('web::fitting.hull_estimated_value')],
            ['data' => 'fitting_estimated_value', 'title' => trans('web::fitting.fitting_estimated_value')],
        ];
    }
}
