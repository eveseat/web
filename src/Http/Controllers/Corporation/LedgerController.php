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
            compact('pidates', 'pitotals', 'piimport', 'piexport',
                'corporation_id', 'month', 'year'));
    }
    /**
     * @param      $corporation_id
     * @param null $year
     * @param null $month
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getOfficeRentalFeeByMonth(int $corporation_id, $year = null, $month = null)
    {

        ! is_null($year) ? $year : $year = date('Y');
        ! is_null($month) ? $year : $month = date('m');

        $officerentalfeedates = $this->getCorporationLedgerOfficeRentalFeeDates($corporation_id);

        $officerentalfeetotals = $this->getCorporationLedgerOfficeRentalFeeByMonth(
            $corporation_id, $year, $month);

        return view('web::corporation.ledger.officerentalfeebymonth',
            compact('officerentalfeedates', 'officerentalfeetotals',
                'corporation_id', 'month', 'year'));
    }
    /**
     * @param      $corporation_id
     * @param null $year
     * @param null $month
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndustryFacilityTaxByMonth(int $corporation_id, $year = null, $month = null)
    {

        ! is_null($year) ? $year : $year = date('Y');
        ! is_null($month) ? $year : $month = date('m');

        $industryfacilitytaxdates = $this->getCorporationLedgerIndustryFacilityTaxDates($corporation_id);

        $industryfacilitytaxtotals = $this->getCorporationLedgerIndustryFacilityTaxByMonth(
            $corporation_id, $year, $month);

        return view('web::corporation.ledger.industryfacilitytaxbymonth',
            compact('industryfacilitytaxdates', 'industryfacilitytaxtotals',
                'corporation_id', 'month', 'year'));
    }
    /**
     * @param      $corporation_id
     * @param null $year
     * @param null $month
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getReprocessingFeeByMonth(int $corporation_id, $year = null, $month = null)
    {

        ! is_null($year) ? $year : $year = date('Y');
        ! is_null($month) ? $year : $month = date('m');

        $reprocessingfeedates = $this->getCorporationLedgerReprocessingFeeDates($corporation_id);

        $reprocessingfeetotals = $this->getCorporationLedgerReprocessingFeeByMonth(
            $corporation_id, $year, $month);

        return view('web::corporation.ledger.reprocessingfeebymonth',
            compact('reprocessingfeedates', 'reprocessingfeetotals',
                'corporation_id', 'month', 'year'));
    }
    /**
     * @param      $corporation_id
     * @param null $year
     * @param null $month
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getJumpCloneTotalsByMonth(int $corporation_id, $year = null, $month = null)
    {

        ! is_null($year) ? $year : $year = date('Y');
        ! is_null($month) ? $year : $month = date('m');

        $jumpclonedates = $this->getCorporationLedgerJumpCloneDates($corporation_id);

        $jumpclonetotals = $this->getCorporationLedgerJumpCloneByMonth(
            $corporation_id, $year, $month);

        return view('web::corporation.ledger.jumpclonebymonth',
            compact('jumpclonedates', 'jumpclonetotals',
                'corporation_id', 'month', 'year'));
    }
    /**
     * @param      $corporation_id
     * @param null $year
     * @param null $month
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getJumpBridgeTotalsByMonth(int $corporation_id, $year = null, $month = null)
    {

        ! is_null($year) ? $year : $year = date('Y');
        ! is_null($month) ? $year : $month = date('m');

        $jumpclonedates = $this->getCorporationLedgerJumpBrudgeDates($corporation_id);

        $jumpclonetotals = $this->getCorporationLedgerJumpBridgeByMonth(
            $corporation_id, $year, $month);

        return view('web::corporation.ledger.jumpbridgebymonth',
            compact('jumpbridgedates', 'jumpbridgetotals',
                'corporation_id', 'month', 'year'));
    }
}
