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

namespace Seat\Web\Http\DataTables\Common\Military;

use Yajra\DataTables\Services\DataTable;

/**
 * Class AbstractFittingDataTable.
 *
 * @package Seat\Web\Http\DataTables\Common\Military
 */
abstract class AbstractFittingDataTable extends DataTable
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
            ->editColumn('ship.typeName', function ($row) {
                return view('web::partials.type', [
                    'type_id' => $row->ship->typeID,
                    'type_name' => $row->ship->typeName,
                    'variation' => $row->ship->group->categoryID == 9 ? 'bpc' : 'icon',
                ])->render();
            })
            ->addColumn('items', function ($row) {
                return $row->items->count();
            })
            ->addColumn('hull_estimated_value', function ($row) {
                return number($row->ship->price->average);
            })
            ->addColumn('fitting_estimated_value', function ($row) {
                return number($row->fitting_estimated_price);
            })
            ->addColumn('full_estimated_value', function ($row) {
                return number($row->estimated_price);
            })
            ->editColumn('action', function ($row) {
                $detail_parameters = [
                    'fitting_id' => $row->fitting_id,
                ];

                if (isset($row->corporation_id))
                    $detail_parameters['corporation_id'] = $row->corporation_id;

                if (isset($row->character_id))
                    $detail_parameters['character_id'] = $row->character_id;

                return
                    view('web::common.fittings.buttons.insurance', ['type_id' => $row->ship->typeID])->render() . ' ' .
                    view('web::common.fittings.buttons.detail', $detail_parameters)->render() . ' ' .
                    view('web::common.fittings.buttons.export', ['data_export' => $row->toEve()])->render();
            })
            ->filterColumn('items', function ($query, $keyword) {
                $query->whereHas('items', function ($sub_query) use ($keyword) {
                    $sub_query->whereHas('type', function ($type_query) use ($keyword) {
                        $type_query->whereRaw('typeName LIKE ?', ["%$keyword%"]);
                    });
                });
            })
            ->rawColumns(['ship.typeName', 'action'])
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
            ['data' => 'name', 'title' => trans('web::fitting.name')],
            ['data' => 'ship.typeName', 'title' => trans('web::fitting.type')],
            ['data' => 'items', 'title' => trans('web::fitting.items'), 'orderable' => false],
            ['data' => 'hull_estimated_value', 'title' => trans('web::fitting.hull_estimated_value'), 'searchable' => false, 'orderable' => false],
            ['data' => 'fitting_estimated_value', 'title' => trans('web::fitting.fitting_estimated_value'), 'searchable' => false, 'orderable' => false],
            ['data' => 'full_estimated_value', 'title' => trans('web::fitting.full_estimated_value'), 'searchable' => false, 'orderable' => false],
        ];
    }
}
