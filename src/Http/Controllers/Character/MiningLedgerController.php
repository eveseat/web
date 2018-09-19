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
use Illuminate\View\View;
use Seat\Services\Repositories\Character\MiningLedger;
use Seat\Web\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;

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

        $ledger = $this->getCharacterLedger($character_id)
            ->sortByDesc('date')
            ->groupBy('date', 'solar_system_id', 'type_id')
            ->map(function ($row) {

                $row->quantity = $row->sum('quantity');
                $row->volumes = $row->sum('volumes');
                $row->value = $row->sum('value');

                return $row;
            })->flatten();

        return view('web::character.mining-ledger', compact('ledger'));
    }

    /**
     * @param int $character_id
     * @param     $date
     * @param int $system_id
     * @param int $type_id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function getDetailedLedger(int $character_id, $date, int $system_id, int $type_id): JsonResponse
    {

        $entries = $this->getCharacterLedger($character_id, false)
            ->addSelect('time')
            ->where('date', $date)
            ->where('solar_system_id', $system_id)
            ->where('type_id', $type_id)
            ->get();

        return DataTables::of($entries)
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
            ->addColumn('value', function ($row) {

                return view('web::partials.miningvalue', compact('row'))
                    ->render();
            })
            ->escapeColumns([])
            ->make(true);
    }
}
