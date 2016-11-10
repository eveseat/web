<?php
/*
This file is part of SeAT

Copyright (C) 2015, 2016  Leon Jacobs

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/

namespace Seat\Web\Http\Controllers\Character;

use Seat\Services\Repositories\Character\Character;
use Seat\Services\Repositories\Character\Implants;
use Seat\Services\Repositories\Character\Info;
use Seat\Services\Repositories\Character\JumpClone;
use Seat\Services\Repositories\Character\Skills;
use Seat\Web\Http\Controllers\Controller;

class SheetController extends Controller
{

    use Character;
    use Info;
    use Implants;
    use JumpClone;
    use Skills;

    /**
     * @param $character_id
     *
     * @return \Illuminate\View\View
     */
    public function getSheet(int $character_id)
    {

        $character_sheet = $this->getCharacterSheet($character_id);

        // Check if we managed to get any records for
        // this character. If not, redirect back with
        // an error.
        if (empty($character_sheet))
            return redirect()->back()
                ->with('error', trans('web::seat.unknown_character'));

        $account_info = $this->getCharacterAccountInfo($character_id);
        $employment = $this->getCharacterEmploymentHistory($character_id);
        $implants = $this->getCharacterImplants($character_id);
        $jump_clones = $this->getCharacterJumpClones($character_id);
        $skill_in_training = $this->getCharacterSkillInTraining($character_id);
        $skill_queue = $this->getCharacterSkilQueue($character_id);
        $titles = $this->getCharacterCorporationTitles($character_id);

        return view('web::character.sheet',
            compact('account_info', 'character_sheet', 'employment',
                'implants', 'jump_clones', 'skill_in_training',
                'skill_queue', 'titles'));
    }

}
