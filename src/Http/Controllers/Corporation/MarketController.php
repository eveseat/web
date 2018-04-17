<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015, 2016, 2017, 2018  Leon Jacobs
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

namespace Seat\Web\Http\Controllers\Corporation;

use Seat\Services\Repositories\Corporation\Market;
use Seat\Services\Repositories\Eve\EveRepository;
use Seat\Web\Http\Controllers\Controller;
use Yajra\Datatables\Facades\Datatables;

/**
 * Class MarketController.
 * @package Seat\Web\Http\Controllers\Corporation
 */
class MarketController extends Controller
{
    use EveRepository;
    use Market;

    /**
     * @param $corporation_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getMarket(int $corporation_id)
    {

        return view('web::corporation.market');
    }

    /**
     * @param int $corporation_id
     *
     * @return mixed
     */
    public function getMarketData(int $corporation_id)
    {

        $orders = $this->getCorporationMarketOrders($corporation_id, false);

        return Datatables::of($orders)
            ->addColumn('bs', function ($row) {

                return view('web::partials.marketbuysell', compact('row'))
                    ->render();
            })
            ->addColumn('vol', function ($row) {

                return view('web::partials.marketvolume', compact('row'))
                    ->render();
            })
            ->addColumn('state', function ($row) {

                return ucfirst($row->state);
            })
            ->editColumn('price', function ($row) {

                return number($row->price);
            })
            ->addColumn('total', function ($row) {

                return number($row->price * $row->volume_total);
            })
            ->editColumn('typeName', function ($row) {

                return view('web::partials.markettype', compact('row'))
                    ->render();
            })
            ->make(true);

    }
}
