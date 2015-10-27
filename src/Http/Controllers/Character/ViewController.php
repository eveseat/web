<?php
/*
This file is part of SeAT

Copyright (C) 2015  Leon Jacobs

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

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Seat\Services\Repositories\Character\CharacterRepository;
use Seat\Services\Repositories\Eve\EveRepository;
use Seat\Web\Validation\Permission;

/**
 * Class ViewController
 * @package Seat\Web\Http\Controllers\Character
 */
class ViewController extends Controller
{

    use CharacterRepository, EveRepository;

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\View\View
     */
    public function getCharacters(Request $request)
    {

        $characters = $this->getAllCharactersWithAffiliationsAndFilters($request);
        $corporations = $this->getCharacterCorporations();

        return view('web::character.list',
            compact('characters', 'corporations'));

    }

    /**
     * @param $character_id
     *
     * @return \Illuminate\View\View
     */
    public function getSheet($character_id)
    {

        $skills = $this->getCharacterSkillsInformation($character_id);
        $skill_groups = $this->getCharacterSkillsGroups();

        return view('web::character.character-sheet',
            compact('skills', 'skill_groups'));
    }

}
