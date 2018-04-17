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

use Seat\Services\Repositories\Corporation\Wallet;
use Seat\Web\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;

/**
 * Class WalletController.
 * @package Seat\Web\Http\Controllers\Corporation
 */
class WalletController extends Controller
{
    use Wallet;

    /**
     * @param int $corporation_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getJournal(int $corporation_id)
    {

        return view('web::corporation.wallet.journal.journal');

    }

    /**
     * @param int $corporation_id
     *
     * @return mixed
     */
    public function getJournalData(int $corporation_id)
    {

        $journal = $this->getCorporationWalletJournal($corporation_id, false);

        return Datatables::of($journal)
            ->editColumn('ref_type', function ($row) {

                return view('web::partials.journaltranstype', compact('row'))
                    ->render();
            })
            ->editColumn('first_party_id', function ($row) {

                return view('web::partials.journalfrom', compact('row'))
                    ->render();
            })
            ->editColumn('second_party_id', function ($row) {

                return view('web::partials.journalto', compact('row'))
                    ->render();
            })
            ->editColumn('amount', function ($row) {

                return number($row->amount);
            })
            ->editColumn('balance', function ($row) {

                return number($row->balance);
            })
            ->make(true);

    }

    /**
     * @param int $corporation_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getTransactions(int $corporation_id)
    {

        return view('web::corporation.wallet.transactions.transactions');
    }

    /**
     * @param int $corporation_id
     *
     * @return mixed
     */
    public function getTransactionsData(int $corporation_id)
    {

        $transactions = $this->getCorporationWalletTransactions($corporation_id, false);

        return Datatables::of($transactions)
            ->editColumn('is_buy', function ($row) {

                return view('web::partials.transactiontype', compact('row'))
                    ->render();
            })
            ->editColumn('unit_price', function ($row) {

                return number($row->unit_price);
            })
            ->addColumn('total', function ($row) {

                return number($row->unit_price * $row->quantity);
            })
            ->editColumn('client', function ($row) {

                return view('web::partials.transactionclient', compact('row'))
                    ->render();
            })
            ->make(true);

    }
}
