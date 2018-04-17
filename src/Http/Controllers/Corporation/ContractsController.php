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

use Seat\Services\Repositories\Corporation\Contracts;
use Seat\Web\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;

/**
 * Class ContractsController.
 * @package Seat\Web\Http\Controllers\Corporation
 */
class ContractsController extends Controller
{
    use Contracts;

    /**
     * @param $corporation_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getContracts(int $corporation_id)
    {

        return view('web::corporation.contracts', compact('contracts'));
    }

    /**
     * @param int $corporation_id
     *
     * @return mixed
     */
    public function getContractsData(int $corporation_id)
    {

        $contracts = $this->getCorporationContracts($corporation_id, false);

        return Datatables::of($contracts)
            ->editColumn('issuer_id', function ($row) {

                return view('web::partials.contractissuer', compact('row'))
                    ->render();
            })
            ->editColumn('type', function ($row) {

                return view('web::partials.contracttype', compact('row'))
                    ->render();
            })
            ->editColumn('status', function ($row) {

                return ucfirst($row->status);

            })
            ->editColumn('price', function ($row) {

                return number($row->price);
            })
            ->editColumn('reward', function ($row) {

                return number($row->reward);
            })
            ->addColumn('contents', function ($row) {

                return view('web::partials.contractcontentsbutton', compact('row'))
                    ->render();
            })
            ->make('true');
    }

    /**
     * @param int $corporation_id
     * @param int $contract_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getContractsItemsData(int $corporation_id, int $contract_id)
    {

        $assets = $this->getCorporationContractsItems($corporation_id, $contract_id);

        return view('web::corporation.contractitems', compact('assets'));
    }
}
