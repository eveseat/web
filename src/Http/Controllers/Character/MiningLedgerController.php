<?php

/**
 * MIT License.
 *
 * Copyright (c) 2019. Felix Huber
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
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

        return view('web::character.mining-ledger', compact('ledger'));
    }

    public function getMiningLedger(int $character_id) : JsonResponse
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

                $value = $row->average_price * $row->quantity;

                if(empty($value))
                    // If historical price has not been set, get historical price.
                    $value = $this->getHistoricalPrice($row->type_id, $row->date)->average_price;

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
