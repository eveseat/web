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

use Yajra\DataTables\Services\DataTable;

/**
 * Class AbstractMiningDataTable.
 *
 * @package Seat\Web\Http\DataTables\Common\Industrial
 */
abstract class AbstractMiningDataTable extends DataTable
{
    /**
     * @return \Yajra\DataTables\DataTableAbstract|\Yajra\DataTables\EloquentDataTable
     */
    public function data()
    {
        return datatables()
            ->eloquent($this->applyScopes($this->query()))
            ->editColumn('date', function ($row) {
                return view('web::partials.date', ['datetime' => $row->date])->render();
            })
            ->editColumn('quantity', function ($row) {
                return number($row->quantity);
            })
            ->editColumn('action', function ($row) {
                return view('web::common.minings.buttons.details', compact('row'))->render();
            })
            ->editColumn('solar_system.name', function ($row) {
                return view('web::partials.system', ['system' => $row->solar_system->name, 'security' => $row->solar_system->security])->render();
            })
            ->editColumn('type.typeName', function ($row) {
                return view('web::partials.type', ['type_id' => $row->type->typeID, 'type_name' => $row->type->typeName])->render();
            })
            ->addColumn('volume', function ($row) {
                return number($row->type->volume * $row->quantity);
            })
            ->addColumn('estimated_value', function ($row) {
                return number($row->type->price->average * $row->quantity);
            })
            ->filterColumn('volume', function ($query, $keyword) {
                return $query->whereHas('type', function ($sub_query) use ($keyword) {
                    return $sub_query->whereRaw('(volume * quantity) LIKE ?', ["%$keyword%"]);
                });
            })
            ->filterColumn('estimated_value', function ($query, $keyword) {
                return $query->whereHas('type.price', function ($sub_query) use ($keyword) {
                    return $sub_query->whereRaw('(average * quantity) LIKE ?', ["%$keyword%"]);
                });
            });
    }

    /**
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->postAjax()
            ->columns($this->getColumns())
            ->addAction()
            ->parameters([
                'drawCallback' => 'function() { $("[data-toggle=tooltip]").tooltip(); }',
            ])
            ->orderBy(0, 'desc');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    abstract public function query();

    /**
     * @return array
     */
    public function getColumns()
    {
        return [
            ['data' => 'date', 'title' => trans('web::mining.date')],
            ['data' => 'solar_system.name', 'title' => trans('web::mining.system')],
            ['data' => 'type.typeName', 'title' => trans('web::mining.ore')],
            ['data' => 'quantity', 'title' => trans('web::mining.quantity')],
            ['data' => 'volume', 'title' => trans('web::mining.volume'), 'orderable' => false],
            ['data' => 'estimated_value', 'title' => trans('web::mining.estimated_value'), 'orderable' => false],
        ];
    }
}
