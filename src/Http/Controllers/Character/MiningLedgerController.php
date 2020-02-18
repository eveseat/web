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

use Seat\Eveapi\Models\Industry\CharacterMining;
use Seat\Eveapi\Models\RefreshToken;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\DataTables\Character\Industrial\MiningDataTable;
use Seat\Web\Http\DataTables\Scopes\CharacterScope;
use Seat\Web\Models\User;

/**
 * Class MiningLedgerController.
 *
 * @package Seat\Web\Http\Controllers\Character
 */
class MiningLedgerController extends Controller
{
    /**
     * @param int $character_id
     * @param \Seat\Web\Http\DataTables\Character\Industrial\MiningDataTable $dataTable
     * @return mixed
     */
    public function index(int $character_id, MiningDataTable $dataTable)
    {
        $token = RefreshToken::where('character_id', $character_id)->first();
        $characters = collect();
        if ($token) {
            $characters = User::with('characters')->find($token->user_id)->characters;
        }

        return $dataTable
            ->addScope(new CharacterScope('character.mining', $character_id, request()->input('characters', [])))
            ->render('web::character.mining-ledger', compact('characters'));
    }

    public function show(int $character_id)
    {
        $entries = CharacterMining::where('character_id', $character_id)
                                  ->where('date', request()->query('date'))
                                  ->where('solar_system_id', request()->query('solar_system_id'))
                                  ->where('type_id', request()->query('type_id'))
                                  ->orderBy('time', 'desc')
                                  ->get();

        return view('web::common.minings.modals.details.content', compact('entries'));
    }
}
