<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2022 Leon Jacobs
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
use Seat\Eveapi\Models\Industry\CharacterMining;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\DataTables\Corporation\Industrial\MiningDataTable;
use Seat\Web\Http\DataTables\Scopes\MiningCorporationScope;

/**
 * Class MiningLedgerController.
 *
 * @package Seat\Web\Http\Controllers\Corporation
 */
class MiningLedgerController extends Controller
{
    /**
     * @param  \Seat\Eveapi\Models\Corporation\CorporationInfo  $corporation
     * @param  \Seat\Web\Http\DataTables\Corporation\Industrial\MiningDataTable  $dataTable
     * @return mixed
     */
    public function index(CorporationInfo $corporation, MiningDataTable $dataTable, int $year = null, int $month = null)
    {
        if (is_null($year)) $year = carbon()->year;

        if (is_null($month)) $month = carbon()->month;

        $ledgers = $this->getCorporationLedgers($corporation->corporation_id);

        return $dataTable
            ->addScope(new MiningCorporationScope([$corporation->corporation_id], $year, $month))
            ->render('web::corporation.mining.ledger', compact('corporation', 'ledgers'));
    }

    /**
     * @param  int  $corporation_id
     * @param  bool  $get
     * @return CharacterMining[]
     */
    private function getCorporationLedgers(int $corporation_id)
    {
        return CharacterMining::select('year', 'month')
            ->join('corporation_member_trackings', 'corporation_member_trackings.character_id', 'character_minings.character_id')
            ->distinct()
            ->where('corporation_id', $corporation_id)
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();
    }
}
