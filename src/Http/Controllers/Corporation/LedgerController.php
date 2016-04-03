<?php
/*
This file is part of SeAT

Copyright (C) 2015  Leon Jacobs

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

use App\Http\Controllers\Controller;
use Seat\Services\Repositories\Corporation\CorporationRepository;

/**
 * Class ViewController
 * @package Seat\Web\Http\Controllers\Corporation
 */
class LedgerController extends Controller
{

    use CorporationRepository;

    /**
     * @param $corporation_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getSummary($corporation_id)
    {
        $ledger = $this->getCorporationMemberSecurity($corporation_id);

        $divisions = $this->getCorporationWalletDivisionSummary($corporation_id);	

        return view('web::corporation.ledger.summary', compact('ledger', 'divisions'));
    }

    /**
     * @param                          $corporation_id
     * @param                          $year
     * @param                          $month
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getBountyPrizesByMonth($corporation_id, $year = 0, $month = 0)
    {

        if ($year == 0) {
                $year = date("Y", time());
        }
        if ($month == 0) {
                $month = date("m", time());
        }

        $bountyprizes = $this->getCorporationLedgerBountyPrizeDates(
            $corporation_id);

        $bountyprizedates = $this->getCorporationLedgerBountyPrizeByMonth(
            $corporation_id, $year, $month);

        return view('web::corporation.ledger.bountyprizesbymonth', compact('bountyprizes','bountyprizedates','corporation_id', 'month', 'year'));
    }

    /**
     * @param                          $corporation_id
     * @param                          $year
     * @param                          $month
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getPlanetaryInteractionByMonth($corporation_id, $year = 0, $month = 0)
    {

        if ($year == 0) {
                $year = date("Y", time());
        }
        if ($month == 0) {
                $month = date("m", time());
        }

        $pidates = $this->getCorporationLedgerPIDates($corporation_id);

        $pitotals = $this->getCorporationLedgerPITotalsByMonth(
            $corporation_id, $year, $month);

        return view('web::corporation.ledger.planetaryinteraction', compact('pidates','pitotals','piimport','piexport','corporation_id', 'month', 'year'));
    }

}
