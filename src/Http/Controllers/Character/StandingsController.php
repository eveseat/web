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
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\DataTables\Character\Military\StandingDataTable;
use Seat\Web\Http\DataTables\Scopes\CharacterScope;

/**
 * Class StandingsController.
 *
 * @package Seat\Web\Http\Controllers\Character
 */
class StandingsController extends Controller
{
    /**
     * @param \Seat\Eveapi\Models\Character\CharacterInfo $character
     * @param \Seat\Web\Http\DataTables\Character\Military\StandingDataTable $dataTable
     * @return mixed
     */
    public function index(CharacterInfo $character, StandingDataTable $dataTable)
    {
        return $dataTable
            ->addScope(new CharacterScope('character.standing', request()->input('characters', [])))
            ->render('web::character.standings', compact('character'));
    }
}
