<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to present Leon Jacobs
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
use Seat\Eveapi\Models\Sde\InvType;
use Seat\Web\Http\Controllers\Controller;

/**
 * Class SheetController.
 *
 * @package Seat\Web\Http\Controllers\Character
 */
class SheetController extends Controller
{
    /**
     * @param  \Seat\Eveapi\Models\Character\CharacterInfo  $character
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function show(CharacterInfo $character)
    {
        //create key/value pairs for implant IDs and texts
        $jumpclone_implant_ids = collect($character->jump_clones)
            ->pluck('implants')
            ->flatten()
            ->unique();

        $jumpclone_implants = InvType::whereIn('typeID', $jumpclone_implant_ids)
            ->get()
            ->pluck('typeName', 'typeID')
            ->toArray();

        return view('web::character.sheet', compact('character', 'jumpclone_implants'));
    }
}
