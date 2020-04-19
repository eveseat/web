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

use Seat\Eveapi\Models\Corporation\CorporationInfo;
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
     * @param \Seat\Eveapi\Models\Corporation\CorporationInfo $corporation
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getWalletSummary(CorporationInfo $corporation)
    {
        $divisions = $this->getCorporationWalletDivisionSummary($corporation->corporation_id);

        return view('web::corporation.ledger.wallet_summary',
            compact('divisions', 'corporation'));
    }

    /**
     * @param \Seat\Eveapi\Models\Corporation\CorporationInfo $corporation
     * @param int|null $year
     * @param int|null $month
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getBountyPrizesByMonth(CorporationInfo $corporation, ?int $year = null, ?int $month = null)
    {
        $year = is_null($year) ? date('Y') : $year;
        $month = is_null($month) ? date('m') : $month;

        $periods = $this->getCorporationLedgerBountyPrizeDates($corporation->corporation_id);
        $entries = $this->getCorporationLedgerBountyPrizeByMonth($corporation->corporation_id, $year, $month);

        return view('web::corporation.ledger.bounty_prizes',
            compact('periods', 'entries', 'corporation_id', 'month', 'year', 'corporation'));
    }

    /**
     * @param \Seat\Eveapi\Models\Corporation\CorporationInfo $corporation
     * @param int|null $year
     * @param int|null $month
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getPlanetaryInteractionByMonth(CorporationInfo $corporation, ?int $year = null, ?int $month = null)
    {
        $year = is_null($year) ? date('Y') : $year;
        $month = is_null($month) ? date('m') : $month;

        $periods = $this->getCorporationLedgerPIDates($corporation->corporation_id);
        $entries = $this->getCorporationLedgerPITotalsByMonth($corporation->corporation_id, $year, $month);

        return view('web::corporation.ledger.planetary_interaction',
            compact('periods', 'entries', 'corporation_id', 'month', 'year', 'corporation'));
    }

    /**
     * @param \Seat\Eveapi\Models\Corporation\CorporationInfo $corporation
     * @param int|null $year
     * @param int|null $month
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getOfficesRentalsByMonth(CorporationInfo $corporation, ?int $year = null, ?int $month = null)
    {
        $year = is_null($year) ? date('Y') : $year;
        $month = is_null($month) ? date('m') : $month;

        $periods = $this->getCorporationLedgerOfficesRentalsPeriods($corporation->corporation_id);
        $entries = $this->getCorporationLedgerOfficesRentalsByMonth($corporation->corporation_id, $year, $month);

        return view('web::corporation.ledger.offices_rentals',
            compact('periods', 'entries', 'corporation_id', 'month', 'year', 'corporation'));
    }

    /**
     * @param \Seat\Eveapi\Models\Corporation\CorporationInfo $corporation
     * @param int|null $year
     * @param int|null $month
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndustryFacilityByMonth(CorporationInfo $corporation, ?int $year = null, ?int $month = null)
    {
        $year = is_null($year) ? date('Y') : $year;
        $month = is_null($month) ? date('m') : $month;

        $periods = $this->getCorporationLedgerIndustryFacilityPeriods($corporation->corporation_id);
        $entries = $this->getCorporationLedgerIndustryFacilityByMonth($corporation->corporation_id, $year, $month);

        return view('web::corporation.ledger.industry_facility',
            compact('periods', 'entries', 'corporation_id', 'month', 'year', 'corporation'));
    }

    /**
     * @param \Seat\Eveapi\Models\Corporation\CorporationInfo $corporation
     * @param int|null $year
     * @param int|null $month
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getReprocessingByMonth(CorporationInfo $corporation, ?int $year = null, ?int $month = null)
    {
        $year = is_null($year) ? date('Y') : $year;
        $month = is_null($month) ? date('m') : $month;

        $periods = $this->getCorporationLedgerReprocessingPeriods($corporation->corporation_id);
        $entries = $this->getCorporationLedgerReprocessingByMonth($corporation->corporation_id, $year, $month);

        return view('web::corporation.ledger.reprocessing',
            compact('periods', 'entries', 'corporation_id', 'month', 'year', 'corporation'));
    }

    /**
     * @param \Seat\Eveapi\Models\Corporation\CorporationInfo $corporation
     * @param int|null $year
     * @param int|null $month
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getJumpClonesByMonth(CorporationInfo $corporation, ?int $year = null, ?int $month = null)
    {
        $year = is_null($year) ? date('Y') : $year;
        $month = is_null($month) ? date('m') : $month;

        $periods = $this->getCorporationLedgerJumpClonesPeriods($corporation->corporation_id);
        $entries = $this->getCorporationLedgerJumpClonesByMonth($corporation->corporation_id, $year, $month);

        return view('web::corporation.ledger.jump_clones',
            compact('periods', 'entries', 'corporation_id', 'month', 'year', 'corporation'));
    }

    /**
     * @param \Seat\Eveapi\Models\Corporation\CorporationInfo $corporation
     * @param int|null $year
     * @param int|null $month
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getJumpBridgesByMonth(CorporationInfo $corporation, ?int $year = null, ?int $month = null)
    {
        $year = is_null($year) ? date('Y') : $year;
        $month = is_null($month) ? date('m') : $month;

        $periods = $this->getCorporationLedgerJumpBridgesPeriods($corporation->corporation_id);
        $entries = $this->getCorporationLedgerJumpBridgesByMonth($corporation->corporation_id, $year, $month);

        return view('web::corporation.ledger.jump_bridges',
            compact('periods', 'entries', 'corporation_id', 'month', 'year', 'corporation'));
    }
}
