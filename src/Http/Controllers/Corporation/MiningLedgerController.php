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

use Illuminate\View\View;
use Seat\Eveapi\Models\Corporation\CorporationMemberTracking;
use Seat\Services\Repositories\Corporation\MiningLedger;
use Seat\Web\Http\Controllers\Controller;

/**
 * Class MiningLedgerController.
 *
 * @package Seat\Web\Http\Controllers\Corporation
 */
class MiningLedgerController extends Controller
{
    use MiningLedger;

    /**
     * @param int      $corporation_id
     * @param int|null $year
     * @param int|null $month
     *
     * @return \Illuminate\View\View
     */
    public function getLedger(int $corporation_id, int $year = null, int $month = null): View
    {

        if (is_null($year))
            $year = date('Y');

        if (is_null($month))
            $month = date('m');

        $ledgers = $this->getCorporationLedgers($corporation_id);

        $entries = $this->getCorporationLedger($corporation_id, $year, $month);

        return view('web::corporation.mining.ledger', compact('ledgers', 'entries'));
    }

    /**
     * @param int $corporation_id
     *
     * @return \Illuminate\View\View
     */
    public function getTracking(int $corporation_id): View
    {

        $members = CorporationMemberTracking::where('corporation_id', $corporation_id)->get();

        return view('mining-ledger::corporation.views.tracking', compact('members'));
    }
}
