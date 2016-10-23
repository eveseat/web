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

        $assets = $this->getCharacterAssets($character_id);

        // TODO: Asset List Contents

        return view('web::character.assets', compact('assets'));
    }

    /**
     * @param $character_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getBookmarks($character_id)
    {

        $bookmarks = $this->getCharacterBookmarks($character_id);

        return view('web::character.bookmarks', compact('bookmarks'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\View\View
     */
    public function getCharacters(Request $request)
    {

        $alliances = $this->getCharacterAlliances();
        $characters = $this->getAllCharactersWithAffiliationsAndFilters($request);
        $corporations = $this->getCharacterCorporations();

        return view('web::character.list',
            compact('alliances', 'characters', 'corporations'));

    }

    /**
     * @param $character_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getChannels($character_id)
    {

        $channels = $this->getCharacterChatChannelsFull($character_id);

        return view('web::character.channels', compact('channels'));
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

        $contacts = $this->getCharacterContacts($character_id);
        $labels = $this->getCharacterContactLabels($character_id);

        return view('web::character.contacts', compact('contacts', 'labels'));
    }

    /**
     * @param $character_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getContracts($character_id)
    {

        $contracts = $this->getCharacterContracts($character_id, 50);

        return view('web::character.contracts', compact('contracts'));

    }

    /**
     * @param $character_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndustry($character_id)
    {

        $jobs = $this->getCharacterIndustry($character_id);

        return view('web::character.industry', compact('jobs'));
    }

    /**
     * @param $character_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getKillmails($character_id)
    {

        $killmails = $this->getCharacterKillmails($character_id, 200);

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
     * @param $message_id
     *
     * @return \Illuminate\View\View
     */
    public function getMailRead($character_id, $message_id)
    {

        $message = $this->getCharacterMailMessage($character_id, $message_id);

        return view('web::character.mail-read', compact('message'));

    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getMailTimeline()
    {

        $messages = $this->getCharacterMailTimeline();

        return view('web::character.mail-timeline', compact('messages'));
    }

    /**
     * @param $message_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getMailTimelineRead($message_id)
    {

        $message = $this->getCharacterMailTimeline($message_id);

        return view('web::character.mail-timeline-read', compact('message'));

    }

    /**
     * @param $character_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getMarket($character_id)
    {

        $orders = $this->getCharacterMarketOrders($character_id);
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
     * @param $character_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getPi($character_id)
    {

        $colonies = $this->getCharacterPlanetaryColonies($character_id);

        // TODO: Complete the Links and stuff™

        return view('web::character.pi', compact('colonies'));
    }

    /**
     * @param $character_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getResearch($character_id)
    {

        $agents = $this->getCharacterResearchAgents($character_id);

        return view('web::character.research', compact('agents'));

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
     * @param $character_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getStandings($character_id)
    {

        $standings = $this->getCharacterStandings($character_id);

        return view('web::character.standings', compact('standings'));
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
