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

use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\DataTables\Character\CharacterDataTable;
use Seat\Web\Http\DataTables\Scopes\CharacterScope;

/**
 * Class CharacterController.
 *
 * @package Seat\Web\Http\Controllers\Character
 */
class CharacterController extends Controller
{
    /**
     * @param \Seat\Web\Http\DataTables\Character\CharacterDataTable $dataTable
     * @return mixed
     */
    public function index(CharacterDataTable $dataTable)
    {
        if (auth()->user()->hasSuperUser())
            return $dataTable->render('web::character.list');

        $owned_character_ids = auth()->user()->associatedCharacterIds()->toArray();
        $allowed_character_ids = array_keys(array_get(auth()->user()->getAffiliationMap(), 'char'));

        return $dataTable
            ->addScope(new CharacterScope('character.sheet', auth()->user()->id, array_merge($owned_character_ids, $allowed_character_ids)))
            ->render('web::character.list');
    }

    public function show(int $character_id)
    {

    }
}
