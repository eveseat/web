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

use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\DataTables\Character\Military\KillMailDataTable;
use Seat\Web\Http\DataTables\Scopes\CharacterScope;

/**
 * Class KillmailController.
 * @package Seat\Web\Http\Controllers\Character
 */
class KillmailController extends Controller
{
    /**
     * @param $character_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(int $character_id, KillMailDataTable $dataTable)
    {

        return $dataTable->addScope(new CharacterScope([$character_id]))
            ->render('web::character.killmails');

    }
}
