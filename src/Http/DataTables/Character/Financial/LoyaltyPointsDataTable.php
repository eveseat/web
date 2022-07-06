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

namespace Seat\Web\Http\DataTables\Character\Financial;

use Seat\Eveapi\Models\Character\CharacterLoyaltyPoints;
use Yajra\DataTables\Services\DataTable;

/**
 * Class MarketDataTable.
 *
 * @package Seat\Web\Http\DataTables\Character\Financial
 */
class LoyaltyPointsDataTable extends DataTable
{
    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return CharacterLoyaltyPoints::with('character', 'corporation');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function ajax()
    {
        return datatables()
            ->eloquent($this->applyScopes($this->query()))
            ->editColumn('character', function ($row) {
                return view('web::partials.character', ['character' => $row->character])->render();
            })
            ->editColumn('corporation', function ($row) {
                return view('web::partials.corporation', ['corporation' => $row->corporation])->render();
            })
            ->editColumn('amount', function ($row) {
                return number($row->amount, 0);
            })
            ->addColumn('fuzzworks', function ($row) {
                return view('web::character.partials.fuzzwork-lp-prices', ['corporation' => $row->corporation])->render();
            })
            ->rawColumns(['character','corporation', 'amount', 'fuzzworks'])
            ->make(true);
    }

    /**
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return parent::html()
            ->columns($this->getColumns())
            ->postAjax();
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return [
            ['data' => 'character', 'title' => trans('web::seat.character_name'),'sortable'=>false,'name'=>'character.name'],
            ['data' => 'corporation', 'title' => trans('web::seat.corporation_name'),'sortable'=>false,'name'=>'corporation.name'],
            ['data' => 'amount', 'title' => trans('web::seat.loyalty_points'),'searchable'=>false],
            ['data' => 'fuzzworks', 'title' => trans('web::seat.loyalty_point_prices'),'sortable'=>false,'searchable'=>false],
        ];
    }
}
