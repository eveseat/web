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

namespace Seat\Web\Http\DataTables\Tools;

use Seat\Eveapi\Models\Market\MarketOrder;
use Seat\Web\Http\Controllers\Tools\MarketController;
use Yajra\DataTables\Services\DataTable;

/**
 * Class MoonsDataTable.
 *
 * @package Seat\Web\Http\DataTables\Tools
 */
class MarketOrderDataTable extends DataTable
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return datatables()
            ->eloquent($this->applyScopes($this->query()))
            ->editColumn('solar_system', function ($row) {
                return view('web::partials.location', ['location' => $row]);
            })
            ->editColumn('location_id', function ($row) {
                return view('web::partials.building', ['building' => $row]);
            })
            ->editColumn('expiry', function ($row) {
                return carbon($row->issued)->addDays($row->duration);
            })
            ->make(true);
    }

    /**
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        $default_item = MarketController::DEFAULT_ITEM;

        return $this->builder()
            ->dom('Brtip')
            ->columns($this->getColumns())
            ->parameters([
                'order' => [
                    2, // here is the column number
                    'asc'
                ]
            ])
            ->postAjax([
                'data' => 'function (d) {
                    d.type_id = $("#dt-item-selector").val() === null ? '.$default_item.' : $("#dt-item-selector").val();
                    d.sell_orders = $("#sellOrdersRadio").prop("checked")
                }',
            ]);
    }

    /**
     * @return mixed
     */
    public function query()
    {
        return MarketOrder::with("type","station","solar_system","structure");
    }

    /**
     * @return array
     */
    public function getColumns() {
        return [
            ['data' => 'solar_system', 'title' => "System"],
            ['data' => 'volume_remaining', 'title' => "Quantity"],
            ['data' => 'price', 'title' => "Price"],
            ['data' => 'location_id','title'=>'Location'],
            ['data' => 'expiry','title'=>'Expiry'],
        ];
    }
}
