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

namespace Seat\Web\Http\Controllers\Character;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Seat\Services\Repositories\Character\MiningLedger;
use Seat\Web\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;

/**
 * Class MiningLedgerController.
 *
 * @package Seat\Web\Http\Controllers\Character
 */
class MiningLedgerController extends Controller
{
    use MiningLedger;

    /**
     * @param int $character_id
     *
     * @return \Illuminate\View\View
     */
    public function getLedger(int $character_id): View
    {

        $ledger = $this->getCharacterLedger($character_id, false)
            ->addSelect(DB::raw('SUM(quantity) as quantity'), DB::raw('SUM(quantity * volume) as volumes'), DB::raw('SUM(quantity * adjusted_price) as amounts'))
            ->groupBy('character_id', 'date', 'solar_system_id', 'type_id')
            ->get();

        return view('web::character.mining-ledger', compact('ledger'));
    }

    /**
     * @param int $character_id
     * @param     $date
     * @param int $system_id
     * @param int $type_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDetailedLedger(int $character_id, $date, int $system_id, int $type_id): JsonResponse
    {

        $entries = $this->getCharacterLedger($character_id, false)
            ->addSelect('time', 'quantity', DB::raw('(quantity * volume) as volumes'), DB::raw('(quantity * adjusted_price) as amounts'))
            ->where('character_minings.date', $date)
            ->where('solar_system_id', $system_id)
            ->where('character_minings.type_id', $type_id)
            ->get();

        return Datatables::of($entries)
            ->removeColumn('solar_system_id')
            ->removeColumn('date')
            ->removeColumn('type_id')
            ->removeColumn('average_price')
            ->removeColumn('type')
            ->editColumn('quantity', function ($row) {

                return view('web::partials.miningquantity', compact('row'))
                    ->render();
            })
            ->editColumn('volumes', function ($row) {

                return view('web::partials.miningvolume', compact('row'))
                    ->render();
            })
            ->editColumn('amounts', function ($row) {

                return view('web::partials.miningvalue', compact('row'))
                    ->render();
            })
            ->make(true);
    }
}
