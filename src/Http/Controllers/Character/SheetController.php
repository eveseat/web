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

use Seat\Eveapi\Models\Character\CharacterCorporationHistory;
use Seat\Eveapi\Models\Character\CharacterFatigue;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Clones\CharacterClone;
use Seat\Eveapi\Models\Clones\CharacterImplant;
use Seat\Eveapi\Models\Clones\CharacterJumpClone;
use Seat\Eveapi\Models\Skills\CharacterAttribute;
use Seat\Eveapi\Models\Skills\CharacterSkillQueue;
use Seat\Services\Repositories\Character\Character;
use Seat\Services\Repositories\Character\Implants;
use Seat\Services\Repositories\Character\JumpClone;
use Seat\Services\Repositories\Character\Skills;
use Seat\Services\Repositories\Configuration\UserRespository;
use Seat\Web\Http\Controllers\Controller;

/**
 * Class SheetController.
 *
 * @package Seat\Web\Http\Controllers\Character
 */
class SheetController extends Controller
{
    use Character;
    use Implants;
    use JumpClone;
    use Skills;
    use UserRespository;

    /**
     * @param \Seat\Eveapi\Models\Character\CharacterInfo $character
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function show(CharacterInfo $character)
    {

        // TODO: Revert to repository pattern.

        // Ensure we've the public information for this character
        // If not, redirect back with an error.

        // TODO : queue a job which will pull public data for this toon
        if (! $character)
            return redirect()->back()
                ->with('error', trans('web::seat.unknown_character'));

        return view('web::character.sheet', compact('character'));
    }
}
