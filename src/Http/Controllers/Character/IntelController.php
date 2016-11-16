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

use Seat\Services\Repositories\Character\Intel;
use Seat\Web\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;

/**
 * Class IntelController
 * @package Seat\Web\Http\Controllers\Character
 */
class IntelController extends Controller
{

    use Intel;

    /**
     * @var int
     */
    protected $top_limit = 10;

    /**
     * @param int $character_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIntelSummary(int $character_id)
    {

        return view('web::character.intel.summary');
    }

    /**
     * @param int $character_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAjaxTopWalletJournal(int $character_id)
    {

        $top = $this->characterTopWalletJournalInteractions(
            $character_id, $this->top_limit);

        return view('web::character.intel.ajax.topjournal', compact('top'));
    }

    /**
     * @param int $character_id
     *
     * @return
     */
    public function getTopTransactionsData(int $character_id)
    {

        $top = $this->characterTopWalletTransactionInteractions($character_id);

        return Datatables::of($top)
            ->editColumn('characterName', function ($row) {

                return view('web::character.intel.partials.charactername', compact('row'))
                    ->render();
            })
            ->editColumn('corporationName', function ($row) {

                return view('web::character.intel.partials.corporationname', compact('row'))
                    ->render();
            })
            ->editColumn('allianceName', function ($row) {

                return view('web::character.intel.partials.alliancename', compact('row'))
                    ->render();
            })
            ->make(true);

    }

    /**
     * @param int $character_id
     *
     * @return
     */
    public function getTopMailFromData(int $character_id)
    {

        $top = $this->characterTopMailInteractions($character_id);

        return Datatables::of($top)
            ->editColumn('characterName', function ($row) {

                return view('web::character.intel.partials.charactername', compact('row'))
                    ->render();
            })
            ->editColumn('corporationName', function ($row) {

                return view('web::character.intel.partials.corporationname', compact('row'))
                    ->render();
            })
            ->editColumn('allianceName', function ($row) {

                return view('web::character.intel.partials.alliancename', compact('row'))
                    ->render();
            })
            ->make(true);
    }

    /**
     * @param int $character_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getStandingsComparison(int $character_id)
    {

        $profiles = $this->standingsProfiles();

        return view('web::character.intel.standingscompare', compact('profiles'));
    }

    /**
     * @param int $character_id
     * @param int $profile_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAjaxCompareStandingsWithProfile(int $character_id, int $profile_id)
    {

        $journal = $this->getCharacterJournalStandingsWithProfile($character_id, $profile_id);

        return view('web::character.intel.ajax.standings.journal', compact('journal'));

    }

    /**
     * @param int $character_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getStandingsJournalDetail(int $character_id)
    {

        $profiles = $this->standingsProfiles();

        return view('web::character.intel.journaldetail', compact('profiles'));
    }

}
