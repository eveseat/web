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
     * @param $character_id
     *
     * @return \Illuminate\View\View
     */
    public function getAssets($character_id)
    {

        $assets = collect($this->getCharacterAssets($character_id));

        // TODO: Asset List Contents

        return view('web::character.assets', compact('assets'));
    }

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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCalendar($character_id)
    {

        $events = $this->getCharacterUpcomingCalendarEvents($character_id);

        return view('web::character.calendar', compact('events'));
    }

    /**
     * @param $character_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getContacts($character_id)
    {

        $contacts = collect($this->getCharacterContacts($character_id));
        $labels = collect($this->getCharacterContactLabels($character_id));

        return view('web::character.contacts', compact('contacts', 'labels'));
    }

    /**
     * @param $character_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getContracts($character_id)
    {

        $contracts = collect($this->getCharacterContracts($character_id));

        return view('web::character.contracts', compact('contracts'));

    }

    /**
     * @param $character_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndustry($character_id)
    {

        $jobs = collect($this->getCharacterIndustry($character_id));

        return view('web::character.industry', compact('jobs'));
    }

    /**
     * @param $character_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getKillmails($character_id)
    {

        $killmails = $this->getCharacterKillmails($character_id);

        return view('web::character.killmails', compact('killmails'));

    }

    /**
     * @param $character_id
     *
     * @return \Illuminate\View\View
     */
    public function getMail($character_id)
    {

        $mail = $this->getCharacterMail($character_id);

        return view('web::character.mail', compact('mail'));

    }

    /**
     * @param $character_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getMarket($character_id)
    {

        $orders = collect($this->getCharacterMarketOrders($character_id));
        $states = $this->getEveMarketOrderStates();

        return view('web::character.market', compact('orders', 'states'));

    }

    /**
     * @param $character_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getNotifications($character_id)
    {

        $notifications = $this->getCharacterNotifications($character_id);

        return view('web::character.notifications', compact('notifications'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param                          $character_id
     *
     * @return \Illuminate\View\View
     */
    public function getJournal(Request $request, $character_id)
    {

        $journal = $this->getCharacterWalletJournal(
            $character_id, 50, $request);
        $transaction_types = $this->getEveTransactionTypes();

        return view('web::character.journal',
            compact('journal', 'transaction_types'));
    }

    /**
     * @param $character_id
     *
     * @return \Illuminate\View\View
     */
    public function getSheet($character_id)
    {

        $account_info = $this->getCharacterAccountInfo($character_id);
        $character_sheet = $this->getCharacterSheet($character_id);
        $employment = $this->getCharacterEmploymentHistory($character_id);
        $implants = $this->getCharacterImplants($character_id);
        $jump_clones = $this->getCharacterJumpClones($character_id);
        $skill_in_training = $this->getCharacterSkillInTraining($character_id);
        $skill_queue = $this->getCharacterSkilQueue($character_id);

        return view('web::character.sheet',
            compact('account_info', 'character_sheet', 'employment',
                'implants', 'jump_clones', 'skill_in_training',
                'skill_queue'));
    }

    /**
     * @param $character_id
     *
     * @return \Illuminate\View\View
     */
    public function getSkills($character_id)
    {

        $skills = $this->getCharacterSkillsInformation($character_id);
        $skill_groups = $this->getEveSkillsGroups();

        return view('web::character.skills',
            compact('skills', 'skill_groups'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param                          $character_id
     *
     * @return \Illuminate\View\View
     */
    public function getTransactions(Request $request, $character_id)
    {

        $transactions = $this->getCharacterWalletTransactions(
            $character_id, 50, $request);

        return view('web::character.transactions', compact('transactions'));
    }

}
