<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015, 2016, 2017, 2018  Leon Jacobs
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

use Seat\Eveapi\Models\Alliances\AllianceMember;
use Seat\Eveapi\Models\Character\CharacterAffiliation;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Services\Repositories\Character\Intel;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\Validation\NewIntelNote;
use Seat\Web\Http\Validation\UpdateIntelNote;
use Yajra\DataTables\DataTables;

/**
 * Class IntelController.
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
     * @return mixed
     * @throws \Exception
     */
    public function getTopWalletJournalData(int $character_id)
    {

        $top = $this->characterTopWalletJournalInteractions($character_id);

        return DataTables::of($top)
            ->editColumn('ref_type', function ($row) {

                return ucwords(str_replace('_', ' ', $row->ref_type));
            })
            ->addColumn('character', function ($row) {

                $character_id = $row->character_id;

                $character_helper = $row->character_id !== $row->first_party->entity_id ? $row->first_party : $row->second_party;

                if ($character_helper->category !== 'character')
                    return '';

                $character = CharacterInfo::find($character_helper->entity_id) ?: $character_helper->entity_id;

                return view('web::partials.character', compact('character', 'character_id'));
            })
            ->editColumn('corporation', function ($row) {

                $character_id = $row->character_id;

                $corporation_helper = $row->character_id !== $row->first_party->entity_id ? $row->first_party : $row->second_party;

                $corporation_id = '';

                if ($corporation_helper->category === 'character'){
                    $corporation_id = optional(CharacterInfo::find($corporation_helper->entity_id))->corporation_id ?: optional(CharacterAffiliation::find($corporation_helper->entity_id))->corporation_id;
                }

                if ($corporation_helper->category === 'corporation'){
                    $corporation_id = $corporation_helper->entity_id;
                }

                if (is_a($corporation_id, 'Illuminate\Support\Optional'))
                    return '';

                $corporation = CorporationInfo::find($corporation_id) ?: $corporation_id;

                return view('web::partials.corporation', compact('corporation', 'character_id'));


            })
            ->addColumn('alliance', function ($row) {

                $character_id = $row->character_id;

                $alliance_helper = $row->character_id !== $row->first_party->entity_id ? $row->first_party : $row->second_party;

                $alliance_id = '';

                if ($alliance_helper->category === 'character'){
                    $alliance_id = optional(CharacterAffiliation::find($alliance_helper->entity_id))->alliance_id;
                }

                if ($alliance_helper->category === 'corporation'){
                    $alliance_id = optional(CorporationInfo::find($alliance_helper->entity_id))->alliance_id ?: optional(CharacterAffiliation::where('corporation_id', $alliance_helper->entity_id));

                }

                if (is_a($alliance_id, 'Illuminate\Support\Optional'))
                    return '';


                $alliance = $alliance_id;

                return view('web::partials.alliance', compact('alliance', 'character_id'));
            })
            ->rawColumns(['character', 'corporation', 'alliance'])
            ->make(true);

    }

    /**
     * @param int $character_id
     *
     * @return mixed
     * @throws \Exception
     */
    public function getTopTransactionsData(int $character_id)
    {

        $top = $this->characterTopWalletTransactionInteractions($character_id);

        return DataTables::of($top)
            ->editColumn('character_id', function ($row) {

                return view('web::character.intel.partials.charactername', compact('row'))
                    ->render();
            })
            ->editColumn('corporation_id', function ($row) {

                return view('web::character.intel.partials.corporationname', compact('row'))
                    ->render();
            })
            ->editColumn('alliance_id', function ($row) {

                return view('web::character.intel.partials.alliancename', compact('row'))
                    ->render();
            })
            ->rawColumns(['character_id', 'corporation_id', 'alliance_id'])
            ->make(true);

    }

    /**
     * @param int $character_id
     *
     * @return mixed
     * @throws \Exception
     */
    public function getTopMailFromData(int $character_id)
    {

        $top = $this->characterTopMailInteractions($character_id);

        return DataTables::of($top)
            ->editColumn('character_id', function ($row) {

                return view('web::character.intel.partials.charactername', compact('row'))
                    ->render();
            })
            ->editColumn('corporation_id', function ($row) {

                return view('web::character.intel.partials.corporationname', compact('row'))
                    ->render();
            })
            ->editColumn('alliance_id', function ($row) {

                return view('web::character.intel.partials.alliancename', compact('row'))
                    ->render();
            })
            ->rawColumns(['character_id', 'corporation_id', 'alliance_id'])
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
     * @throws \Exception
     */
    public function getCompareStandingsWithProfileData(int $character_id, int $profile_id)
    {

        $journal = $this->getCharacterJournalStandingsWithProfile($character_id, $profile_id);

        return DataTables::of($journal)
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
            ->rawColumns(['characterName', 'corporationName', 'allianceName'])
            ->make(true);

    }

    /**
     * @param int $character_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getNotes(int $character_id)
    {

        return view('web::character.intel.notes');

    }

    /**
     * @param int $character_id
     *
     * @return mixed
     * @throws \Exception
     */
    public function getNotesData(int $character_id)
    {

        return DataTables::of(CharacterInfo::getNotes($character_id))
            ->addColumn('actions', function ($row) {

                return view('web::character.intel.partials.notesactions', compact('row'))
                    ->render();
            })
            ->rawColumns(['actions'])
            ->make(true);

    }

    /**
     * @param int $character_id
     * @param int $note_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSingleNotesData(int $character_id, int $note_id)
    {

        return response()->json(
            CharacterInfo::getNote($character_id, $note_id)->first());

    }

    /**
     * @param \Seat\Web\Http\Validation\NewIntelNote $request
     * @param int                                    $character_id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postAddNew(NewIntelNote $request, int $character_id)
    {

        CharacterInfo::addNote(
            $character_id, $request->input('title'), $request->input('note'));

        return redirect()->back()
            ->with('success', 'Note Added');

    }

    /**
     * @param int $character_id
     * @param int $note_id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getDeleteNote(int $character_id, int $note_id)
    {

        CharacterInfo::deleteNote($character_id, $note_id);

        return redirect()->back()
            ->with('success', 'Note deleted!');

    }

    /**
     * @param \Seat\Web\Http\Validation\UpdateIntelNote $request
     * @param int                                       $character_id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postUpdateNote(UpdateIntelNote $request, int $character_id)
    {

        CharacterInfo::updateNote(
            $character_id, $request->input('note_id'),
            $request->input('title'),
            $request->input('note'));

        return redirect()->back()
            ->with('success', 'Note updated!');

    }
}
