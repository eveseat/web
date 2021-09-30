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
use Seat\Eveapi\Models\Industry\CharacterMining;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\DataTables\Character\Industrial\MiningDataTable;
use Seat\Web\Http\DataTables\Scopes\CharacterScope;

/**
 * Class MiningLedgerController.
 *
 * @package Seat\Web\Http\Controllers\Character
 */
class MiningLedgerController extends Controller
{
    /**
     * @param  \Seat\Eveapi\Models\Character\CharacterInfo  $character
     * @param  \Seat\Web\Http\DataTables\Character\Industrial\MiningDataTable  $dataTable
     * @return mixed
     */
    public function index(CharacterInfo $character, MiningDataTable $dataTable)
    {
        return $dataTable
            ->addScope(new CharacterScope('character.mining', request()->input('characters', [])))
            ->render('web::character.mining-ledger', compact('character'));
    }

    /**
     * @param  \Seat\Eveapi\Models\Character\CharacterInfo  $character
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(CharacterInfo $character)
    {
        $entries = CharacterMining::where('character_id', $character->character_id)
                                  ->where('date', request()->query('date'))
                                  ->where('solar_system_id', request()->query('solar_system_id'))
                                  ->where('type_id', request()->query('type_id'))
                                  ->orderBy('time', 'desc')
                                  ->get();

        return view('web::common.minings.modals.details.content', compact('character', 'entries'));
    }
}
