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

use Seat\Eveapi\Models\Fittings\CharacterFitting;
use Seat\Services\Repositories\Character\Fittings;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\DataTables\Character\Military\FittingDataTable;
use Seat\Web\Http\DataTables\Scopes\CharacterScope;

/**
 * Class FittingController.
 * @package Seat\Web\Http\Controllers\Character
 */
class FittingController extends Controller
{

    use Fittings;

    /**
     * @param int $character_id
     * @param \Seat\Web\Http\DataTables\Character\Military\FittingDataTable $dataTable
     * @return mixed
     */
    public function index(int $character_id, FittingDataTable $dataTable)
    {
        return $dataTable->addScope(new CharacterScope([$character_id]))
            ->render('web::character.fittings', compact('fittings'));
    }

    /**
     * @param int $character_id
     * @param int $fitting_id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|\Seat\Eveapi\Models\Fittings\CharacterFitting|null
     */
    public function show(int $character_id, int $fitting_id)
    {
        $fitting = CharacterFitting::with('ship', 'items')->where('character_id', $character_id)
            ->where('fitting_id', $fitting_id)
            ->first();
    }

    /**
     * @param int $character_id
     * @param int $fitting_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getFittingItems(int $character_id, int $fitting_id)
    {

        $fitting = $this->getCharacterFitting($character_id, $fitting_id);
        $items = $this->getCharacterFittingItems($fitting_id);

        return view('web::character.fittingitems', compact('fitting', 'items'));
    }
}
