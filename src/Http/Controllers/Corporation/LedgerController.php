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
     * @param int $corporation_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getWalletSummary(int $corporation_id)
    {

        $divisions = $this->getCorporationWalletDivisionSummary($corporation_id);

        return view('web::corporation.ledger.wallet_summary',
            compact('divisions'));
    }

    /**
     * @param int $corporation_id
     * @param int|null $year
     * @param int|null $month
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getBountyPrizesByMonth(int $corporation_id, ?int $year = null, ?int $month = null)
    {

        $year = is_null($year) ? date('Y') : $year;
        $month = is_null($month) ? date('m') : $month;

        $periods = $this->getCorporationLedgerBountyPrizeDates($corporation_id);
        $entries = $this->getCorporationLedgerBountyPrizeByMonth($corporation_id, $year, $month);

        return view('web::corporation.ledger.bounty_prizes',
            compact('periods', 'entries', 'corporation_id', 'month', 'year'));
    }

    /**
     * @param int $corporation_id
     * @param int|null $year
     * @param int|null $month
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getPlanetaryInteractionByMonth(int $corporation_id, ?int $year = null, ?int $month = null)
    {

        $year = is_null($year) ? date('Y') : $year;
        $month = is_null($month) ? date('m') : $month;

        $periods = $this->getCorporationLedgerPIDates($corporation_id);
        $entries = $this->getCorporationLedgerPITotalsByMonth($corporation_id, $year, $month);

        return view('web::corporation.ledger.planetary_interaction',
            compact('periods', 'entries', 'corporation_id', 'month', 'year'));
    }

    /**
     * @param int $corporation_id
     * @param int|null $year
     * @param int|null $month
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getOfficesRentalsByMonth(int $corporation_id, ?int $year = null, ?int $month = null)
    {
        $year = is_null($year) ? date('Y') : $year;
        $month = is_null($month) ? date('m') : $month;

        $periods = $this->getCorporationLedgerOfficesRentalsPeriods($corporation_id);
        $entries = $this->getCorporationLedgerOfficesRentalsByMonth($corporation_id, $year, $month);

        return view('web::corporation.ledger.offices_rentals',
            compact('periods', 'entries', 'corporation_id', 'month', 'year'));
    }

    /**
     * @param int $corporation_id
     * @param int|null $year
     * @param int|null $month
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndustryFacilityByMonth(int $corporation_id, ?int $year = null, ?int $month = null)
    {
        $year = is_null($year) ? date('Y') : $year;
        $month = is_null($month) ? date('m') : $month;

        $periods = $this->getCorporationLedgerIndustryFacilityPeriods($corporation_id);
        $entries = $this->getCorporationLedgerIndustryFacilityByMonth($corporation_id, $year, $month);

        return view('web::corporation.ledger.industry_facility',
            compact('periods', 'entries', 'corporation_id', 'month', 'year'));
    }

    /**
     * @param int $corporation_id
     * @param int|null $year
     * @param int|null $month
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getReprocessingByMonth(int $corporation_id, ?int $year = null, ?int $month = null)
    {
        $year = is_null($year) ? date('Y') : $year;
        $month = is_null($month) ? date('m') : $month;

        $periods = $this->getCorporationLedgerReprocessingPeriods($corporation_id);
        $entries = $this->getCorporationLedgerReprocessingByMonth($corporation_id, $year, $month);

        return view('web::corporation.ledger.reprocessing',
            compact('periods', 'entries', 'corporation_id', 'month', 'year'));
    }

    /**
     * @param int $corporation_id
     * @param int|null $year
     * @param int|null $month
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getJumpClonesByMonth(int $corporation_id, ?int $year = null, ?int $month = null)
    {
        $year = is_null($year) ? date('Y') : $year;
        $month = is_null($month) ? date('m') : $month;

        $periods = $this->getCorporationLedgerJumpClonesPeriods($corporation_id);
        $entries = $this->getCorporationLedgerJumpClonesByMonth($corporation_id, $year, $month);

        return view('web::corporation.ledger.jump_clones',
            compact('periods', 'entries', 'corporation_id', 'month', 'year'));
    }

    /**
     * @param int $corporation_id
     * @param int|null $year
     * @param int|null $month
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getJumpBridgesByMonth(int $corporation_id, ?int $year = null, ?int $month = null)
    {
        $year = is_null($year) ? date('Y') : $year;
        $month = is_null($month) ? date('m') : $month;

        $periods = $this->getCorporationLedgerJumpBridgesPeriods($corporation_id);
        $entries = $this->getCorporationLedgerJumpBridgesByMonth($corporation_id, $year, $month);

        return view('web::corporation.ledger.jump_bridges',
            compact('periods', 'entries', 'corporation_id', 'month', 'year'));
    }
}
