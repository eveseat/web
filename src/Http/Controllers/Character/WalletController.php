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

namespace Seat\Web\Http\Controllers\Character;

use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Services\Repositories\Character\Wallet;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Models\User;
use Yajra\DataTables\DataTables;

/**
 * Class WalletController.
 * @package Seat\Web\Http\Controllers\Character
 */
class WalletController extends Controller
{
    use Wallet;

    /**
     * @param int $character_id
     *
     * @return \Illuminate\View\View
     */
    public function getJournal(int $character_id)
    {

        return view('web::character.wallet.journal.journal');
    }

    /**
     * @param int $character_id
     *
     * @return mixed
     * @throws \Exception
     */
    public function getJournalData(int $character_id)
    {
        if (! request()->has('all_linked_characters'))
            return response('required url parameter is missing!', 400);

        if (request('all_linked_characters') === 'false')
            $character_ids = collect($character_id);

        $user_group = User::find($character_id)->group->users
            ->filter(function ($user) {
                return $user->name !== 'admin' && $user->id !== 1;
            })
            ->pluck('id');

        if (request('all_linked_characters') === 'true')
            $character_ids = $user_group;

        $journal = $this->getCharacterWalletJournal($character_ids);

        return DataTables::of($journal)
            ->editColumn('ref_type', function ($row) {

                return view('web::partials.journaltranstype', compact('row'));
            })
            ->editColumn('first_party_id', function ($row) {

                $character_id = $row->character_id;

                if (optional($row->first_party)->category === 'character') {

                    $character = CharacterInfo::find($row->first_party_id) ?: $row->first_party_id;

                    return view('web::partials.character', compact('character', 'character_id'));
                }

                if (optional($row->first_party)->category === 'corporation'){

                    $corporation = CorporationInfo::find($row->first_party_id) ?: $row->first_party_id;

                    return view('web::partials.corporation', compact('corporation', 'character_id'));
                }

                return view('web::partials.unknown', [
                    'unknown_id' => $row->first_party_id,
                    'character_id' => $character_id,
                ]);
            })
            ->editColumn('second_party_id', function ($row) {

                $character_id = $row->character_id;

                if (optional($row->second_party)->category === 'character') {

                    $character = CharacterInfo::find($row->second_party_id) ?: $row->second_party_id;

                    return view('web::partials.character', compact('character', 'character_id'));
                }

                if (optional($row->second_party)->category === 'corporation') {

                    $corporation = CorporationInfo::find($row->second_party_id) ?: $row->second_party_id;

                    return view('web::partials.corporation', compact('corporation', 'character_id'));
                }

                return view('web::partials.unknown', [
                    'unknown_id' => $row->second_party_id,
                    'character_id' => $character_id,
                ]);
            })
            ->editColumn('amount', function ($row) {

                return number($row->amount);
            })
            ->editColumn('balance', function ($row) {

                return number($row->balance);
            })
            ->addColumn('is_in_group', function ($row) use ($user_group) {

                return in_array($row->first_party_id, $user_group->toArray()) && in_array($row->second_party_id, $user_group->toArray());
            })
            ->rawColumns(['ref_type', 'first_party_id', 'second_party_id'])
            ->make(true);

    }

    /**
     * @param int $character_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getJournalGraphBalance(int $character_id)
    {

        $data = $this->getCharacterWalletJournal(collect($character_id))
            ->orderBy('date', 'desc')
            ->take(150)
            ->get();

        return response()->json([
            'labels'   => $data->map(function ($item) {

                return $item->date;
            })->toArray(),
            'datasets' => [
                [
                    'label'           => 'Balance',
                    'fill'            => false,
                    'lineTension'     => 0.1,
                    'backgroundColor' => 'rgba(60,141,188,0.9)',
                    'borderColor'     => 'rgba(60,141,188,0.8)',
                    'data'            => $data->map(function ($item) {

                        return $item->balance;
                    })->toArray(),
                ],
                [
                    'label'           => 'Amount',
                    'fill'            => false,
                    'lineTension'     => 0.1,
                    'backgroundColor' => 'rgba(210, 214, 222, 1)',
                    'borderColor'     => 'rgba(210, 214, 222, 1)',
                    'data'            => $data->map(function ($item) {

                        return $item->amount;
                    })->toArray(),
                ],
            ],
        ]);
    }

    /**
     * @param int $character_id
     *
     * @return \Illuminate\View\View
     */
    public function getTransactions(int $character_id)
    {

        return view('web::character.wallet.transactions.transactions');
    }

    /**
     * @param int $character_id
     *
     * @return mixed
     * @throws \Exception
     */
    public function getTransactionsData(int $character_id)
    {

        if (! request()->has('all_linked_characters'))
            return abort(500);

        if (request('all_linked_characters') === 'false')
            $character_ids = collect($character_id);

        $user_group = User::find($character_id)->group->users
            ->filter(function ($user) {

                return $user->name !== 'admin' && $user->id !== 1;
            })
            ->pluck('id');

        if (request('all_linked_characters') === 'true')
            $character_ids = $user_group;

        $transactions = $this->getCharacterWalletTransactions($character_ids);

        return DataTables::of($transactions)
            ->editColumn('is_buy', function ($row) {

                return view('web::partials.transactionbuysell', compact('row'));
            })
            ->editColumn('unit_price', function ($row) {

                return number($row->unit_price);
            })
            ->addColumn('item_view', function ($row) {
                return view('web::partials.transactiontype', compact('row'));
            })
            ->addColumn('total', function ($row) {

                return number($row->unit_price * $row->quantity);
            })
            ->addColumn('client_view', function ($row) {

                $character_id = $row->character_id;

                $character = CharacterInfo::find($row->client_id) ?: $row->client_id;

                return view('web::partials.character', compact('character', 'character_id'));
            })
            ->rawColumns(['is_buy', 'client_view', 'item_view'])
            ->make(true);

    }
}
