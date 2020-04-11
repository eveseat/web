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

namespace Seat\Web\Http\DataTables\Common\Industrial;

use Yajra\DataTables\Services\DataTable;

/**
 * Class AbstractIndustryDataTable.
 *
 * @package Seat\Web\Http\DataTables\Common\Industrial
 */
abstract class AbstractIndustryDataTable extends DataTable
{
    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function ajax()
    {
        return datatables()
            ->eloquent($this->applyScopes($this->query()))
            ->editColumn('start_date', function ($row) {
                return view('web::partials.date', ['datetime' => $row->start_date]);
            })
            ->editColumn('end_date', function ($row) {
                return view('web::partials.date', ['datetime' => $row->end_date]);
            })
            ->editColumn('runs', function ($row) {
                return number($row->runs, 0);
            })
            ->addColumn('location', function ($row) {
                return $row->location->name;
            })
            ->addColumn('activity', function ($row) {
                return $row->activity->activityName;
            })
            ->addColumn('blueprint', function ($row) {
                return view('web::partials.type', ['type_id' => $row->blueprint->typeID, 'type_name' => $row->blueprint->typeName]);
            })
            ->addColumn('product', function ($row) {
                return view('web::partials.type', ['type_id' => $row->product->typeID, 'type_name' => $row->product->typeName]);
            })
            ->filterColumn('location', function ($query, $keyword) {
                return $query->whereHas('location', function ($sub_query) use ($keyword) {
                    return $sub_query->whereRaw('name LIKE ?', ["%$keyword%"]);
                });
            })
            ->filterColumn('activity', function ($query, $keyword) {
                return $query->whereHas('activity', function ($sub_query) use ($keyword) {
                    return $sub_query->whereRaw('activityName LIKE ?', ["%$keyword%"]);
                });
            })
            ->filterColumn('blueprint', function ($query, $keyword) {
                return $query->whereHas('blueprint', function ($sub_query) use ($keyword) {
                    return $sub_query->whereRaw('typeName LIKE ?', ["%$keyword%"]);
                });
            })
            ->filterColumn('product', function ($query, $keyword) {
                return $query->whereHas('product', function ($sub_query) use ($keyword) {
                    return $sub_query->whereRaw('typeName LIKE ?', ["%$keyword%"]);
                });
            })
            ->rawColumns(['start_date', 'end_date', 'blueprint', 'product'])
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
            ->parameters([
                'drawCallback' => 'function() { $("[data-toggle=tooltip]").tooltip(); }',
            ]);
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
            ['data' => 'start_date', 'title' => trans('web::industry.start')],
            ['data' => 'end_date', 'title' => trans('web::industry.end')],
            ['data' => 'location', 'title' => trans('web::industry.location')],
            ['data' => 'activity', 'title' => trans('web::industry.activity')],
            ['data' => 'runs', 'title' => trans('web::industry.runs')],
            ['data' => 'blueprint', 'title' => trans('web::industry.blueprint')],
            ['data' => 'product', 'title' => trans('web::industry.product')],
        ];
    }
}
