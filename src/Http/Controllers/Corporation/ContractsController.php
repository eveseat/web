<?php

/**
 * MIT License.
 *
 * Copyright (c) 2019. Felix Huber
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace Seat\Web\Http\Controllers\Corporation;

use Seat\Services\Repositories\Corporation\Contracts;
use Seat\Services\Repositories\Seat\NamedIdFilter;
use Seat\Web\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;

/**
 * Class ContractsController.
 * @package Seat\Web\Http\Controllers\Corporation
 */
class ContractsController extends Controller
{
    use Contracts, NamedIdFilter;

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
     * @throws \Exception
     */
    public function getContractsData(int $corporation_id)
    {

        $contracts = $this->getCorporationContracts($corporation_id, false);

        return DataTables::of($contracts)
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

                return view('web::partials.contractcontentsbutton', compact('row'));
            })
            ->filterColumn('issuer_id', function ($query, $keyword) {

                $query->whereIn('a.issuer_id', $this->getIdsForNames($keyword)->toArray());
            })
            ->filterColumn('assignee_id', function ($query, $keyword) {

                $query->whereIn('a.assignee_id', $this->getIdsForNames($keyword)->toArray());
            })
            ->filterColumn('acceptor_id', function ($query, $keyword) {

                $query->whereIn('a.acceptor_id', $this->getIdsForNames($keyword)->toArray());
            })
            ->rawColumns(['issuer_id', 'type', 'contents'])
            ->make(true);
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
