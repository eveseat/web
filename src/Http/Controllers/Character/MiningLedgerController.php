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

namespace Seat\Web\Http\Controllers\Character;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Seat\Services\Repositories\Character\MiningLedger;
use Seat\Services\Repositories\Eve\EvePrices;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Models\User;
use Yajra\DataTables\DataTables;

/**
 * Class MiningLedgerController.
 *
 * @package Seat\Web\Http\Controllers\Character
 */
class MiningLedgerController extends Controller
{
    use MiningLedger, EvePrices;

    /**
     * @param int $character_id
     *
     * @return \Illuminate\View\View
     */
    public function getLedger(int $character_id): View
    {

        return view('web::character.mining-ledger');
    }

    public function getMiningLedger(int $character_id): JsonResponse
    {
        if (! request()->has('all_linked_characters'))
            return abort(500);

        $character_ids = collect($character_id);

        $user_group = User::find($character_id)->group->users
            ->filter(function ($user) {
                return $user->name !== 'admin' && $user->id !== 1;
            })
            ->pluck('id');

        if (request('all_linked_characters') === 'true')
            $character_ids = $user_group;

        $ledger = $this->getCharacterLedger($character_ids)
            ->addSelect(DB::raw('SUM(quantity) as quantity'))
            ->groupBy('character_id', 'date', 'solar_system_id', 'type_id');

        return DataTables::of($ledger)
            ->addColumn('system', function ($row) {
                return view('web::partials.miningsystem', compact('row'));
            })
            ->addColumn('type', function ($row) {

                $character = User::find($row->character_id);

                return view('web::partials.miningtype', compact('row', 'character'));
            })
            ->editColumn('quantity', function ($row) {

                return view('web::partials.miningquantity', compact('row'));
            })
            ->addColumn('volume', function ($row) {

                return view('web::partials.miningvolume', compact('row'));
            })
            ->addColumn('value', function ($row) {

                $value = $row->quantity * $row->average_price;

                if(empty($value))
                    // If historical price has not been set, get historical price.
                    $value = $row->quantity * $this->getHistoricalPrice($row->type_id, $row->date)->average_price;

                return view('web::partials.miningvalue', compact('value')) . view('web::character.partials.miningdetails-button', compact('row'));
            })
            ->rawColumns(['system', 'type', 'volume', 'value'])
            ->make(true);
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
        if (! request()->has('all_linked_characters'))
            return abort(500);

        $character_ids = collect($character_id);

        $entries = $this->getCharacterLedger($character_ids)
            ->addSelect('time', 'quantity')
            ->where('character_minings.date', $date)
            ->where('solar_system_id', $system_id)
            ->where('character_minings.type_id', $type_id)
            ->get();

        return DataTables::of($entries)
            ->removeColumn('solar_system_id')
            ->removeColumn('date')
            ->removeColumn('type_id')
            ->removeColumn('average_price')
            ->removeColumn('type')
            ->editColumn('quantity', function ($row) {

                return view('web::partials.miningquantity', compact('row'));
            })
            ->editColumn('volume', function ($row) {

                return view('web::partials.miningvolume', compact('row'));
            })
            ->addColumn('value', function ($row) {

                $value = $row->average_price * $row->quantity;

                if(empty($value))
                    // If historical price has not been set, get historical price.
                    $value = $this->getHistoricalPrice($row->type_id, $row->date)->average_price;

                return view('web::partials.miningvalue', compact('value'));
            })
            ->rawColumns(['value', 'volume'])
            ->make(true);
    }
}
