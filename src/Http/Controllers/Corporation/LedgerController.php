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

use Seat\Services\Repositories\Corporation\Ledger;
use Seat\Services\Repositories\Corporation\Security;
use Seat\Services\Repositories\Corporation\Wallet;
use Seat\Web\Http\Controllers\Controller;

/**
 * Class ViewController.
 * @package Seat\Web\Http\Controllers\Corporation
 */
class LedgerController extends Controller
{
    use Ledger;
    use Security;
    use Wallet;

    /**
     * @param $corporation_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getWalletSummary(int $corporation_id)
    {

        $divisions = $this->getCorporationWalletDivisionSummary($corporation_id);

        return view('web::corporation.ledger.walletsummary',
            compact('divisions'));
    }

    /**
     * @param      $corporation_id
     * @param null $year
     * @param null $month
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getBountyPrizesByMonth(int $corporation_id, $year = null, $month = null)
    {

        ! is_null($year) ? $year : $year = date('Y');
        ! is_null($month) ? $year : $month = date('m');

        $bountyprizes = $this->getCorporationLedgerBountyPrizeDates(
            $corporation_id);

        $bountyprizedates = $this->getCorporationLedgerBountyPrizeByMonth(
            $corporation_id, $year, $month);

        return view('web::corporation.ledger.bountyprizesbymonth',
            compact('bountyprizes', 'bountyprizedates',
                'corporation_id', 'month', 'year'));
    }

    /**
     * @param      $corporation_id
     * @param null $year
     * @param null $month
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getPlanetaryInteractionByMonth(int $corporation_id, $year = null, $month = null)
    {

        ! is_null($year) ? $year : $year = date('Y');
        ! is_null($month) ? $year : $month = date('m');

        $pidates = $this->getCorporationLedgerPIDates($corporation_id);

        $pitotals = $this->getCorporationLedgerPITotalsByMonth(
            $corporation_id, $year, $month);

        return view('web::corporation.ledger.planetaryinteraction',
            compact('pidates', 'pitotals', 'corporation_id', 'month', 'year'));
    }
}
