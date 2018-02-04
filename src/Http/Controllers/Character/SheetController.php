<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015, 2016, 2017  Leon Jacobs
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

use Illuminate\Support\Facades\DB;
use Seat\Eveapi\Models\Character\CharacterCorporationHistory;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Character\CharacterTitle;
use Seat\Eveapi\Models\Clones\CharacterImplant;
use Seat\Eveapi\Models\Clones\CharacterJumpClone;
use Seat\Services\Repositories\Character\Character;
use Seat\Services\Repositories\Character\Implants;
use Seat\Services\Repositories\Character\JumpClone;
use Seat\Services\Repositories\Character\Skills;
use Seat\Services\Repositories\Configuration\UserRespository;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Models\Group;

class SheetController extends Controller
{
    use Character;
    use Implants;
    use JumpClone;
    use Skills;
    use UserRespository;

    /**
     * @param $character_id
     *
     * @return \Illuminate\View\View
     */
    public function getSheet(int $character_id)
    {

        // Ensure we've the public information for this character
        // If not, redirect back with an error
        // TODO : queue a job which will pull public data for this toon
        if (! $character_info = CharacterInfo::find($character_id))
            return redirect()->back()
                ->with('error', trans('web::seat.unknown_character'));

        $group = DB::table('group_user')->where('user_id', $character_id)->first();

        $account_info = $this->getUserGroupCharacters(DB::table('group_user')->where('group_id', $group->group_id)->get());
        $employment = CharacterCorporationHistory::where('character_id', $character_id)->get();
        $implants = CharacterImplant::where('character_id', $character_id)->get();
        $jump_clones = CharacterJumpClone::where('character_id', $character_id)->get();
        $skill_in_training = $this->getCharacterSkillInTraining($character_id);
        $skill_queue = $this->getCharacterSkilQueue($character_id);
        $titles = CharacterTitle::where('character_id', $character_id);

        return view('web::character.sheet',
            compact('account_info', '$character_info', 'employment',
                'implants', 'jump_clones', 'skill_in_training',
                'skill_queue', 'titles'));
    }
}
