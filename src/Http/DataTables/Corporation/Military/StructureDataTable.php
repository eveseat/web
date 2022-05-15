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

namespace Seat\Web\Http\DataTables\Corporation\Military;

use Seat\Eveapi\Models\Corporation\CorporationStructure;
use Yajra\DataTables\Services\DataTable;

/**
 * Class StructureDataTable.
 *
 * @package Seat\Web\Http\DataTables\Corporation\Military
 */
class StructureDataTable extends DataTable
{
    /**
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function ajax()
    {
        return datatables()
            ->eloquent($this->applyScopes($this->query()))
            ->editColumn('type.typeName', function ($row) {
                return view('web::partials.type', ['type_id' => $row->type->typeID, 'type_name' => $row->type->typeName])->render();
            })
            ->editColumn('state', function ($row) {
                return ucfirst(str_replace('_', ' ', $row->state));
            })
            ->editColumn('fuel_expires', function ($row) {
                if ($row->fuel_expires)
                    return view('web::partials.date', ['datetime' => $row->fuel_expires])->render();

                return trans('web::seat.low_power');
            })
            ->editColumn('reinforce_hour', function ($row) {
                return view('web::corporation.structures.partials.reinforcement', compact('row'))->render();
            })
            ->editColumn('services', function ($row) {
                return view('web::corporation.structures.partials.services', compact('row'))->render();
            })
            ->editColumn('action', function ($row) {
                return view('web::corporation.structures.buttons.action', compact('row'))->render();
            })
            ->filterColumn('services', function ($query, $keyword) {
                $query->whereHas('services', function ($sub_query) use ($keyword) {
                    return $sub_query->whereRaw('name LIKE ?', ["%$keyword%"]);
                });
            })
            ->rawColumns(['action', 'type.typeName', 'fuel_expires', 'offline_estimate', 'reinforce_hour', 'services'])
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
            ->addAction()
            ->addTableClass('table-striped table-hover')
            ->parameters([
                'drawCallback' => 'function() { $("[data-toggle=tooltip]").tooltip(); }',
            ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return CorporationStructure::with('info', 'type', 'solar_system', 'services');
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return [
            ['data' => 'type.typeName', 'title' => trans_choice('web::seat.type', 1)],
            ['data' => 'solar_system.name', 'title' => trans('web::seat.location')],
            ['data' => 'info.name', 'title' => trans_choice('web::seat.name', 1)],
            ['data' => 'state', 'title' => trans('web::seat.state')],
            ['data' => 'fuel_expires', 'title' => trans('web::seat.offline')],
            ['data' => 'reinforce_hour', 'title' => trans('web::seat.reinforce_week_hour')],
            ['data' => 'services', 'title' => trans_choice('web::seat.services', 0), 'orderable' => false],
        ];
    }
}
