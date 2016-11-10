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

namespace Seat\Web\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;
use Seat\Services\Repositories\Character\Character;
use Seat\Services\Repositories\Character\Info;
use Seat\Services\Repositories\People\PeopleRepository;
use Seat\Web\Http\Controllers\Controller;

/**
 * Class PeopleController
 * @package Seat\Web\Http\Controllers\Api
 */
class PeopleController extends Controller
{

    use PeopleRepository, Character, Info;

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAll()
    {

        $people = $this->getPeopleAllUserPeople();
        $unaffiliated = $this->getPeopleUnaffiliatedUserKeys();

        return view('web::people.list', compact('people', 'unaffiliated'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSearchPeopleGroups(Request $request)
    {

        return response()->json(
            $this->getPeopleSearchListJson($request->q));
    }

    /**
     * @param $character_id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getNewGroup($character_id)
    {

        $character_info = $this->getCharacterInformation($character_id);

        $this->addPeopleNewGroup($character_info);

        return redirect()->back()
            ->with('success', 'A new people group was created.');

    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getAddToExisting(Request $request)
    {

        $this->addPeopleKeyToExistingGroup(
            $request->person_id, $request->key_id);

        return redirect()->back()
            ->with('success', 'Added a key to an existing group.');
    }

    /**
     * @param $group_id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeGroup($group_id)
    {

        $this->removePeopleGroup($group_id);

        return redirect()->back()
            ->with('success', 'The group was deleted.');
    }

    /**
     * @param $group_id
     * @param $key_id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeKeyFromGroup($group_id, $key_id)
    {

        $this->removePeopleKeyFromGroup($key_id, $group_id);

        return redirect()->back()
            ->with('success', 'The key was removed from the people group.');
    }

    /**
     * @param $group_id
     * @param $character_id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setGroupMain($group_id, $character_id)
    {

        $character_info = $this->getCharacterInformation($character_id);
        $this->setPeopleMainCharacter($group_id, $character_info);

        return redirect()->back()
            ->with('success', 'Updated the groups main character id.');
    }

}
