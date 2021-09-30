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

namespace Seat\Web\Http\Controllers\Character;

use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Wallet\CharacterWalletJournal;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\DataTables\Character\Financial\WalletJournalDataTable;
use Seat\Web\Http\DataTables\Character\Financial\WalletTransactionDataTable;
use Seat\Web\Http\DataTables\Scopes\CharacterScope;

/**
 * Class WalletController.
 *
 * @package Seat\Web\Http\Controllers\Character
 */
class WalletController extends Controller
{
    /**
     * @param  \Seat\Eveapi\Models\Character\CharacterInfo  $character
     * @param  \Seat\Web\Http\DataTables\Character\Financial\WalletJournalDataTable  $dataTable
     * @return mixed
     */
    public function journal(CharacterInfo $character, WalletJournalDataTable $dataTable)
    {
        return $dataTable
            ->addScope(new CharacterScope('character.journal', request()->input('characters', [])))
            ->render('web::character.wallet.journal.journal', compact('character'));
    }

    /**
     * @param  \Seat\Eveapi\Models\Character\CharacterInfo  $character
     * @param  \Seat\Web\Http\DataTables\Character\Financial\WalletTransactionDataTable  $dataTable
     * @return mixed
     */
    public function transactions(CharacterInfo $character, WalletTransactionDataTable $dataTable)
    {
        return $dataTable
            ->addScope(new CharacterScope('character.transaction', request()->input('characters')))
            ->render('web::character.wallet.transactions.transactions', compact('character'));
    }

    /**
     * @param  \Seat\Eveapi\Models\Character\CharacterInfo  $character
     * @return \Illuminate\Http\JsonResponse
     */
    public function getJournalGraphBalance(CharacterInfo $character)
    {

        $data = CharacterWalletJournal::with('first_party', 'second_party')
            ->where('character_id', $character->character_id)
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
}
