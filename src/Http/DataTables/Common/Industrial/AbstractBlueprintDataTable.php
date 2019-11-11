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

namespace Seat\Web\Http\DataTables\Common\Industrial;

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
     * @throws \Exception
     */
    public function ajax()
    {
        return $this->ajaxDataResponse()
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
            ->addTableClass('table-striped table-hover');
    }

    /**
     * @return \Yajra\DataTables\DataTableAbstract|\Yajra\DataTables\EloquentDataTable
     */
    public function ajaxDataResponse()
    {
        return datatables()
            ->eloquent($this->applyScopes($this->query()))
            ->editColumn('type.typeName', function ($row) {
                return view('web::partials.type', [
                    'type_id' => $row->type->typeID,
                    'type_name' => $row->type->typeName,
                    'variation' => $row->quantity == -1 ? 'bp' : 'bpc',
                ]);
            })
            ->editColumn('location_flag', function ($row) {
                return preg_replace('([A-Z])', ' $0', $row->location_flag);
            })
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
            ->rawColumns(['runs']);
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
            'type.typeName',
            'location_flag',
            'quantity',
            'time_efficiency',
            'material_efficiency',
            'runs',
        ];
    }
}
