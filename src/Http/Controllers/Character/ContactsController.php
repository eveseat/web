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

use Seat\Eveapi\Models\RefreshToken;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\DataTables\Character\Intel\ContactDataTable;
use Seat\Web\Http\DataTables\Scopes\CharacterScope;
use Seat\Web\Http\DataTables\Scopes\Filters\ContactCategoryScope;
use Seat\Web\Http\DataTables\Scopes\Filters\ContactStandingLevelScope;
use Seat\Web\Models\User;

/**
 * Class ContactsController.
 *
 * @package Seat\Web\Http\Controllers\Character
 */
class ContactsController extends Controller
{
    /**
     * @param int $character_id
     * @param \Seat\Web\Http\DataTables\Character\Intel\ContactDataTable $dataTable
     * @return mixed
     */
    public function index(int $character_id, ContactDataTable $dataTable)
    {
        $token = RefreshToken::where('character_id', $character_id)->first();
        if ($token) {
            $characters = User::with('characters')->find($token->user_id)->characters;
        } else {
            $characters = collect();
        }

        return $dataTable
            ->addScope(new CharacterScope('character.contact', $character_id, request()->input('characters', [])))
            ->addScope(new ContactCategoryScope(request()->input('filters.category')))
            ->addScope(new ContactStandingLevelScope(request()->input('filters.standing')))
            ->render('web::character.contacts', compact('characters'));
    }
}
