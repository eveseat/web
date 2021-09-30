<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2021 Leon Jacobs
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

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Seat\Eveapi\Models\Corporation\CorporationDivision;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Eveapi\Models\Wallet\CorporationWalletJournal;
use Seat\Web\Http\Controllers\Controller;

/**
 * Class ViewController.
 *
 * @package Seat\Web\Http\Controllers\Corporation
 */
class LedgerController extends Controller
{
    /**
     * @param  \Seat\Eveapi\Models\Corporation\CorporationInfo  $corporation
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getWalletSummary(CorporationInfo $corporation)
    {
        $divisions = $this->getCorporationWalletDivisionSummary($corporation->corporation_id);

        return view('web::corporation.ledger.wallet_summary',
            compact('divisions', 'corporation'));
    }

    /**
     * @param  \Seat\Eveapi\Models\Corporation\CorporationInfo  $corporation
     * @param  int|null  $year
     * @param  int|null  $month
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getBountyPrizesByMonth(CorporationInfo $corporation, ?int $year = null, ?int $month = null)
    {
        $year = is_null($year) ? date('Y') : $year;
        $month = is_null($month) ? date('m') : $month;

        $group_column = 'second_party_id';
        $ref_types = ['bounty_prizes', 'bounty_prize', 'ess_escrow_transfer'];

        $periods = $this->getCorporationLedgerPeriods($corporation->corporation_id, $ref_types);
        $entries = $this->getCorporationLedgerByMonth($corporation->corporation_id, $group_column, $ref_types, $year, $month);

        return view('web::corporation.ledger.bounty_prizes',
            compact('periods', 'entries', 'corporation', 'month', 'year'));
    }

    /**
     * @param  \Seat\Eveapi\Models\Corporation\CorporationInfo  $corporation
     * @param  int|null  $year
     * @param  int|null  $month
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getPlanetaryInteractionByMonth(CorporationInfo $corporation, ?int $year = null, ?int $month = null)
    {
        $year = is_null($year) ? date('Y') : $year;
        $month = is_null($month) ? date('m') : $month;

        $group_column = 'first_party_id';
        $ref_types = ['planetary_import_tax', 'planetary_export_tax'];

        $periods = $this->getCorporationLedgerPeriods($corporation->corporation_id, $ref_types);
        $entries = $this->getCorporationLedgerByMonth($corporation->corporation_id, $group_column, $ref_types, $year, $month);

        return view('web::corporation.ledger.planetary_interaction',
            compact('periods', 'entries', 'corporation', 'month', 'year'));
    }

    /**
     * @param  \Seat\Eveapi\Models\Corporation\CorporationInfo  $corporation
     * @param  int|null  $year
     * @param  int|null  $month
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getOfficesRentalsByMonth(CorporationInfo $corporation, ?int $year = null, ?int $month = null)
    {
        $year = is_null($year) ? date('Y') : $year;
        $month = is_null($month) ? date('m') : $month;

        $group_column = 'first_party_id';
        $ref_types = ['office_rental_fee'];

        $periods = $this->getCorporationLedgerPeriods($corporation->corporation_id, $ref_types);
        $entries = $this->getCorporationLedgerByMonth($corporation->corporation_id, $group_column, $ref_types, $year, $month);

        return view('web::corporation.ledger.offices_rentals',
            compact('periods', 'entries', 'corporation', 'month', 'year'));
    }

    /**
     * @param  \Seat\Eveapi\Models\Corporation\CorporationInfo  $corporation
     * @param  int|null  $year
     * @param  int|null  $month
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndustryFacilityByMonth(CorporationInfo $corporation, ?int $year = null, ?int $month = null)
    {
        $year = is_null($year) ? date('Y') : $year;
        $month = is_null($month) ? date('m') : $month;

        $group_column = 'first_party_id';
        $ref_types = ['industry_job_tax'];

        $periods = $this->getCorporationLedgerPeriods($corporation->corporation_id, $ref_types);
        $entries = $this->getCorporationLedgerByMonth($corporation->corporation_id, $group_column, $ref_types, $year, $month);

        return view('web::corporation.ledger.industry_facility',
            compact('periods', 'entries', 'corporation', 'month', 'year'));
    }

    /**
     * @param  \Seat\Eveapi\Models\Corporation\CorporationInfo  $corporation
     * @param  int|null  $year
     * @param  int|null  $month
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getReprocessingByMonth(CorporationInfo $corporation, ?int $year = null, ?int $month = null)
    {
        $year = is_null($year) ? date('Y') : $year;
        $month = is_null($month) ? date('m') : $month;

        $group_column = 'first_party_id';
        $ref_types = ['reprocessing_tax'];

        $periods = $this->getCorporationLedgerPeriods($corporation->corporation_id, $ref_types);
        $entries = $this->getCorporationLedgerByMonth($corporation->corporation_id, $group_column, $ref_types, $year, $month);

        return view('web::corporation.ledger.reprocessing',
            compact('periods', 'entries', 'corporation', 'month', 'year'));
    }

    /**
     * @param  \Seat\Eveapi\Models\Corporation\CorporationInfo  $corporation
     * @param  int|null  $year
     * @param  int|null  $month
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getJumpClonesByMonth(CorporationInfo $corporation, ?int $year = null, ?int $month = null)
    {
        $year = is_null($year) ? date('Y') : $year;
        $month = is_null($month) ? date('m') : $month;

        $group_column = 'first_party_id';
        $ref_types = ['jump_clone_activation_fee', 'jump_clone_installation_fee'];

        $periods = $this->getCorporationLedgerPeriods($corporation->corporation_id, $ref_types);
        $entries = $this->getCorporationLedgerByMonth($corporation->corporation_id, $group_column, $ref_types, $year, $month);

        return view('web::corporation.ledger.jump_clones',
            compact('periods', 'entries', 'corporation', 'month', 'year'));
    }

    /**
     * @param  \Seat\Eveapi\Models\Corporation\CorporationInfo  $corporation
     * @param  int|null  $year
     * @param  int|null  $month
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getJumpBridgesByMonth(CorporationInfo $corporation, ?int $year = null, ?int $month = null)
    {
        $year = is_null($year) ? date('Y') : $year;
        $month = is_null($month) ? date('m') : $month;

        $group_column = 'first_party_id';
        $ref_types = ['structure_gate_jump'];

        $periods = $this->getCorporationLedgerPeriods($corporation->corporation_id, $ref_types);
        $entries = $this->getCorporationLedgerByMonth($corporation->corporation_id, $group_column, $ref_types, $year, $month);

        return view('web::corporation.ledger.jump_bridges',
            compact('periods', 'entries', 'corporation', 'month', 'year'));
    }

    /**
     * Return the Wallet Division Summary for a Corporation.
     *
     * @param  int  $corporation_id
     * @return \Illuminate\Support\Collection
     */
    private function getCorporationWalletDivisionSummary(int $corporation_id): Collection
    {

        return CorporationDivision::where('corporation_divisions.corporation_id', $corporation_id)
            ->where('type', 'wallet')
            ->get();
    }

    /**
     * @param  int  $corporation_id
     * @param  string[]  $ref_types
     * @return \Seat\Eveapi\Models\Wallet\CorporationWalletJournal[]
     */
    private function getCorporationLedgerPeriods(int $corporation_id, array $ref_types)
    {
        return CorporationWalletJournal::select(DB::raw('DISTINCT MONTH(date) as month, YEAR(date) as year'))
            ->where('corporation_id', $corporation_id)
            ->whereIn('ref_type', $ref_types)
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();
    }

    /**
     * @param  int  $corporation_id
     * @param  string  $group_field
     * @param  array  $ref_types
     * @param  int|null  $year
     * @param  int|null  $month
     * @return \Illuminate\Support\Collection
     */
    private function getCorporationLedgerByMonth(int $corporation_id,
                                                 string $group_field,
                                                 array $ref_types,
                                                 ?int $year = null,
                                                 ?int $month = null): Collection
    {
        return CorporationWalletJournal::select(DB::raw('ROUND(SUM(amount)) as total'), $group_field)
            ->where('corporation_id', $corporation_id)
            ->whereIn('ref_type', $ref_types)
            ->whereYear('date', ! is_null($year) ? $year : date('Y'))
            ->whereMonth('date', ! is_null($month) ? $month : date('m'))
            ->groupBy($group_field)
            ->orderBy('total', 'desc')
            ->get();
    }
}
