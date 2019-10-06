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

namespace Seat\Web\Http\Controllers\Corporation;

use Seat\Services\Repositories\Corporation\Wallet;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\DataTables\Corporation\Financial\WalletJournalDataTable;
use Seat\Web\Http\DataTables\Corporation\Financial\WalletTransactionDataTable;
use Seat\Web\Http\DataTables\Scopes\CorporationScope;
use Yajra\DataTables\DataTables;

/**
 * Class WalletController.
 * @package Seat\Web\Http\Controllers\Corporation
 */
class WalletController extends Controller
{
    use Wallet;

    /**
     * @param int $corporation_id
     * @param \Seat\Web\Http\DataTables\Corporation\Financial\WalletJournalDataTable $dataTable
     * @return mixed
     */
    public function journal(int $corporation_id, WalletJournalDataTable $dataTable)
    {

        return $dataTable->addScope(new CorporationScope([$corporation_id]))
            ->render('web::corporation.wallet.journal.journal');
    }

    /**
     * @param int $corporation_id
     * @param \Seat\Web\Http\DataTables\Corporation\Financial\WalletTransactionDataTable $dataTable
     * @return mixed
     */
    public function transactions(int $corporation_id, WalletTransactionDataTable $dataTable)
    {

        return $dataTable->addScope(new CorporationScope([$corporation_id]))
            ->render('web::corporation.wallet.transactions.transactions');
    }

    /**
     * @param int $corporation_id
     *
     * @return mixed
     * @throws \Exception
     */
    public function getTransactionsData(int $corporation_id)
    {

        $transactions = $this->getCorporationWalletTransactions($corporation_id, false);

        return DataTables::of($transactions)
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
            ->editColumn('client_id', function ($row) {

                return view('web::partials.transactionclient', compact('row'))
                    ->render();
            })
            ->rawColumns(['is_buy', 'client_id'])
            ->make(true);

    }
}
