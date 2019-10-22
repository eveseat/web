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

namespace Seat\Web\Http\Controllers\Corporation;

use Seat\Services\Repositories\Corporation\Wallet;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\DataTables\Corporation\Financial\WalletJournalDataTable;
use Seat\Web\Http\DataTables\Corporation\Financial\WalletTransactionDataTable;
use Seat\Web\Http\DataTables\Scopes\CorporationScope;
use Seat\Web\Http\DataTables\Scopes\CorporationWalletDivisionsScope;

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

        $division_ids = [];
        $division_permissions = [
            'wallet_first_division', 'wallet_second_division', 'wallet_third_division', 'wallet_fourth_division',
            'wallet_fifth_division', 'wallet_sixth_division', 'wallet_seventh_division',
        ];

        foreach ($division_permissions as $key => $permission) {
            if (auth()->user()->has(sprintf('corporation.%s', $permission)))
                array_push($division_ids, ($key + 1));
        }

        return $dataTable->addScope(new CorporationScope([$corporation_id]))
            ->addScope(new CorporationWalletDivisionsScope($division_ids))
            ->render('web::corporation.wallet.journal.journal');
    }

    /**
     * @param int $corporation_id
     * @param \Seat\Web\Http\DataTables\Corporation\Financial\WalletTransactionDataTable $dataTable
     * @return mixed
     */
    public function transactions(int $corporation_id, WalletTransactionDataTable $dataTable)
    {

        $division_ids = [];
        $division_permissions = [
            'wallet_first_division', 'wallet_second_division', 'wallet_third_division', 'wallet_fourth_division',
            'wallet_fifth_division', 'wallet_sixth_division', 'wallet_seventh_division',
        ];

        foreach ($division_permissions as $key => $permission) {
            if (auth()->user()->has(sprintf('corporation.%s', $permission)))
                array_push($division_ids, ($key + 1));
        }

        return $dataTable->addScope(new CorporationScope([$corporation_id]))
            ->addScope(new CorporationWalletDivisionsScope($division_ids))
            ->render('web::corporation.wallet.transactions.transactions');
    }
}
