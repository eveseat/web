<?php
/*
This file is part of SeAT

Copyright (C) 2015, 2016  Leon Jacobs

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/

namespace Seat\Web\Http\Controllers\Corporation;

use Illuminate\Http\Request;
use Seat\Services\Repositories\Corporation\Wallet;
use Seat\Web\Http\Controllers\Controller;

class WalletController extends Controller
{

    use Wallet;

    /**
     * @param \Illuminate\Http\Request $request
     * @param                          $corporation_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getJournal(Request $request, int $corporation_id)
    {

        $journal = $this->getCorporationWalletJournal(
            $corporation_id, 50, $request);
        $transaction_types = $this->getEveTransactionTypes();

        return view('web::corporation.journal',
            compact('journal', 'transaction_types'));

    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param                          $corporation_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getTransactions(Request $request, int $corporation_id)
    {

        $transactions = $this->getCorporationWalletTransactions(
            $corporation_id, 50, $request);

        return view('web::corporation.transactions', compact('transactions'));
    }

}
