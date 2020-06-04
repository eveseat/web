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

use Illuminate\Support\Collection;
use Illuminate\View\View;
use Seat\Eveapi\Jobs\Character\Affiliation;
use Seat\Eveapi\Jobs\Universe\Names;
use Seat\Eveapi\Models\Character\CharacterAffiliation;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Eveapi\Models\Universe\UniverseName;
use Seat\Services\Repositories\Character\Intel;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\Validation\NewIntelNote;
use Seat\Web\Http\Validation\UpdateIntelNote;
use Seat\Web\Models\User;
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
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function getIntelSummary(int $character_id)
    {
        Affiliation::withChain([new Names])->dispatch();

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
        if (! request()->has('all_linked_characters'))
            return abort(500);

        $character_ids = collect($character_id);

        $user_group = User::find($character_id)->group->users
            ->filter(function ($user) {
                return $user->name !== 'admin' && $user->id !== 1;
            })
            ->pluck('id');

        if (request('all_linked_characters') === 'true')
            $character_ids = $user_group;

        $top = $this->characterTopWalletJournalInteractions($character_ids);

        $user_group = User::find($character_id)->group->users
            ->filter(function ($user) {
                return $user->name !== 'admin' && $user->id !== 1;
            })
            ->pluck('id');

        return DataTables::of($top)
            ->editColumn('ref_type', function ($row) {

                return ucwords(str_replace('_', ' ', $row->ref_type));
            })
            ->addColumn('character', function ($row) {

                return $this->getIntelView('character', $row->character_id, $row->first_party_id, $row->second_party_id);
            })
            ->addColumn('corporation', function ($row) {

                return $this->getIntelView('corporation', $row->character_id, $row->first_party_id, $row->second_party_id);
            })
            ->addColumn('alliance', function ($row) {

                return $this->getIntelView('alliance', $row->character_id, $row->first_party_id, $row->second_party_id)
                    . view('web::character.intel.partials.journalcontentbutton', compact('row'));
            })
            ->addColumn('is_in_group', function ($row) use ($user_group) {
                return in_array($row->first_party_id, $user_group->toArray()) && in_array($row->second_party_id, $user_group->toArray());
            })
            ->rawColumns(['character', 'corporation', 'alliance', 'button'])
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
        if (! request()->has('all_linked_characters'))
            return abort(500);

        $character_ids = collect($character_id);

        $user_group = User::find($character_id)->group->users
            ->filter(function ($user) {
                return $user->name !== 'admin' && $user->id !== 1;
            })
            ->pluck('id');

        if (request('all_linked_characters') === 'true')
            $character_ids = $user_group;

        $top = $this->characterTopWalletTransactionInteractions($character_ids);

        return DataTables::of($top)
            ->addColumn('character', function ($row) {

                return $this->getIntelView('character', $row->character_id, $row->client_id);
            })
            ->addColumn('corporation', function ($row) {

                return $this->getIntelView('corporation', $row->character_id, $row->client_id);
            })
            ->addColumn('alliance', function ($row) {

                return $this->getIntelView('alliance', $row->character_id, $row->client_id)
                    . view('web::character.intel.partials.transactioncontentbutton', compact('row'));
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
    public function getTopMailFromData(int $character_id)
    {
        if (! request()->has('all_linked_characters'))
            return abort(500);

        $character_ids = collect($character_id);

        $user_group = User::find($character_id)->group->users
            ->filter(function ($user) {
                return $user->name !== 'admin' && $user->id !== 1;
            })
            ->pluck('id');

        if (request('all_linked_characters') === 'true')
            $character_ids = $user_group;

        $top = $this->characterTopMailInteractions($character_ids);

        return DataTables::of($top)
            ->editColumn('character_id', function ($row) {

                return $this->getIntelView('character', $row->character_id, $row->from);
            })
            ->editColumn('corporation_id', function ($row) {

                return $this->getIntelView('corporation', $row->character_id, $row->from);
            })
            ->editColumn('alliance_id', function ($row) {

                return $this->getIntelView('alliance', $row->character_id, $row->from)
                    . view('web::character.intel.partials.mailcontentbutton', compact('row'));
            })
            ->rawColumns(['character_id', 'corporation_id', 'alliance_id'])
            ->make(true);
    }

    /**
     * @param int $character_id
     *
     * @return \Illuminate\Contracts\View\Factory|View
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
     * @return \Illuminate\Contracts\View\Factory|View
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
     * @return \Illuminate\Contracts\View\Factory|View
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

    /**
     * @param int $first_party_id
     * @param int $second_party_id
     *
     * @return mixed
     * @throws \Exception
     */
    public function getJournalContent(int $first_party_id, int $second_party_id)
    {

        $journal = $this->characterWalletJournalInteractions($first_party_id, $second_party_id);

        return DataTables::of($journal)
            ->editColumn('ref_type', function ($row) {

                return view('web::partials.journaltranstype', compact('row'));
            })
            ->editColumn('first_party_id', function ($row) {

                $character_id = $row->character_id;

                if (optional($row->first_party)->category === 'character') {

                    $character = CharacterInfo::find($row->first_party_id) ?: $row->first_party_id;

                    return view('web::partials.character', compact('character', 'character_id'));
                }

                if (optional($row->first_party)->category === 'corporation'){

                    $corporation = CorporationInfo::find($row->first_party_id) ?: $row->first_party_id;

                    return view('web::partials.corporation', compact('corporation', 'character_id'));
                }

                return view('web::partials.unknown', [
                    'unknown_id' => $row->first_party_id,
                    'character_id' => $character_id,
                ]);
            })
            ->editColumn('second_party_id', function ($row) {

                $character_id = $row->character_id;

                if (optional($row->second_party)->category === 'character') {

                    $character = CharacterInfo::find($row->second_party_id) ?: $row->second_party_id;

                    return view('web::partials.character', compact('character', 'character_id'));
                }

                if (optional($row->second_party)->category === 'corporation') {

                    $corporation = CorporationInfo::find($row->second_party_id) ?: $row->second_party_id;

                    return view('web::partials.corporation', compact('corporation', 'character_id'));
                }

                return view('web::partials.unknown', [
                    'unknown_id' => $row->second_party_id,
                    'character_id' => $character_id,
                ]);
            })
            ->editColumn('amount', function ($row) {

                return number($row->amount);
            })
            ->editColumn('balance', function ($row) {

                return number($row->balance);
            })
            ->addColumn('is_in_group', function ($row) {

                return $row->character_id === $row->first_party_id || $row->character_id === $row->second_party_id;
            })
            ->rawColumns(['ref_type', 'first_party_id', 'second_party_id'])
            ->make(true);
    }

    /**
     * @param int $character_id
     * @param int $client_id
     *
     * @return mixed
     * @throws \Exception
     */
    public function getTransactionContent(int $character_id, int $client_id)
    {

        $transactions = $this->characterWalletTransactionInteraction($character_id, $client_id);

        return DataTables::of($transactions)
            ->editColumn('is_buy', function ($row) {

                return view('web::partials.transactionbuysell', compact('row'));
            })
            ->editColumn('unit_price', function ($row) {

                return number($row->unit_price);
            })
            ->addColumn('item_view', function ($row) {
                return view('web::partials.transactiontype', compact('row'));
            })
            ->addColumn('total', function ($row) {

                return number($row->unit_price * $row->quantity);
            })
            ->addColumn('client_view', function ($row) {

                $character_id = $row->character_id;

                $character = CharacterInfo::find($row->client_id) ?: $row->client_id;

                return view('web::partials.character', compact('character', 'character_id'));
            })
            ->rawColumns(['is_buy', 'client_view', 'item_view'])
            ->make(true);
    }

    /**
     * @param int $character_id
     * @param int $from
     *
     * @return mixed
     * @throws \Exception
     */
    public function getTopMailContent(int $character_id, int $from)
    {

        $mail = $this->getMailContent($character_id, $from);

        return DataTables::of($mail)
            ->editColumn('from', function ($row) {

                $character_id = $row->character_id;

                $character = CharacterInfo::find($row->from) ?: $row->from;

                return view('web::partials.character', compact('character', 'character_id'));
            })
            ->editColumn('subject', function ($row) {

                return view('web::character.partials.mailtitle', compact('row'));
            })
            ->editColumn('tocounts', function ($row) {

                return view('web::character.partials.mailtocounts', compact('row'));
            })
            ->addColumn('read', function ($row) {

                return view('web::character.partials.topmailread', compact('row'));
            })
            ->rawColumns(['from', 'subject', 'tocounts', 'read'])
            ->make(true);

    }

    /**
     * @param int      $character_id
     * @param int      $first_party_id
     * @param int|null $second_party_id
     *
     * @return \Illuminate\Support\Collection
     */
    private function getUniverseNameResolved(int $character_id, int $first_party_id, int $second_party_id = null): Collection
    {
        // f.e. market escrow -> self referential payment.
        if($first_party_id === $second_party_id)
            return collect([
                'character_id' => $character_id,
                'corporation_id' => CharacterInfo::find($character_id)->corporation_id,
                'alliance_id' => CharacterInfo::find($character_id)->alliance_id,
            ]);

        return collect([UniverseName::find($first_party_id), UniverseName::find($second_party_id)])
            ->filter()
            ->filter(function ($universe_name) use ($character_id) {

                return $universe_name->entity_id !== $character_id;
            })
            ->pipe(function ($collection) use ($character_id, $first_party_id,$second_party_id) {

                if ($collection->isNotEmpty()){

                    return $collection->flatten()->map(function ($item) {

                        if($item->category === 'character')
                            return collect([
                                'character_id' => $item->entity_id,
                                'corporation_id' => optional(CharacterAffiliation::find($item->entity_id))->corporation_id,
                                'alliance_id' => optional(CharacterAffiliation::find($item->entity_id))->alliance_id,
                            ])->filter();

                        if($item->category === 'corporation')
                            return collect([
                                'corporation_id' => $item->entity_id,
                                'alliance_id' => optional(CharacterAffiliation::where('corporation_id', $item->entity_id)->get()->first())->alliance_id,
                            ])->filter();

                        if($item->category === 'alliance')
                            return collect(['alliance_id' => $item->entity_id]);

                        return collect(['other_resolved_id' => $item->entity_id]);
                    })->filter();
                }

                return $collection->push(collect(['unknown_id' => $character_id !== $first_party_id ? $first_party_id : $second_party_id, 'character_id' => $character_id]));
            })
            ->flatMap(function ($value) {

                return $value;
            });
    }

    /**
     * @param string   $type
     * @param int      $character_id
     * @param int      $first_other_id
     * @param int|null $second_other_id
     *
     * @return string
     */
    private function getIntelView(string $type, int $character_id, int $first_other_id, int $second_other_id = null): string
    {
        $universe_name = $this->getUniverseNameResolved($character_id, $first_other_id, $second_other_id);

        if($type === 'character')
            return $this->getCharacterIntelView($universe_name, $character_id);

        if($type === 'corporation')
            return $this->getCorporationIntelView($universe_name, $character_id);

        if($type === 'alliance')
            return $this->getAllianceIntelView($universe_name, $character_id);

        return '';

    }

    /**
     * @param \Illuminate\Support\Collection $universe_name
     *
     * @param int                            $character_id
     *
     * @return string
     */
    private function getCharacterIntelView(Collection $universe_name, int $character_id): string
    {
        if ($universe_name->has('unknown_id'))
            return $this->getUnknownIntelView($universe_name);

        if (! $universe_name->has('character_id'))
            return '';

        $character = CharacterInfo::find($universe_name['character_id']) ?: $universe_name['character_id'];

        return view('web::partials.character', compact('character', 'character_id'));
    }

    /**
     * @param \Illuminate\Support\Collection $universe_name
     *
     * @param int                            $character_id
     *
     * @return string
     */
    private function getCorporationIntelView(Collection $universe_name, int $character_id): string
    {
        if ($universe_name->has('unknown_id'))
            return $this->getUnknownIntelView($universe_name);

        if (! $universe_name->has('corporation_id'))
            return '';

        $corporation = CorporationInfo::find($universe_name['corporation_id']) ?: $universe_name['corporation_id'];

        return view('web::partials.corporation', compact('corporation', 'character_id'));
    }

    /**
     * @param \Illuminate\Support\Collection $universe_name
     *
     * @param int                            $character_id
     *
     * @return string
     */
    private function getAllianceIntelView(Collection $universe_name, int $character_id): string
    {
        if ($universe_name->has('unknown_id'))
            return $this->getUnknownIntelView($universe_name);

        if (! $universe_name->has('alliance_id'))
            return '';

        $alliance = $universe_name['alliance_id'];

        return view('web::partials.alliance', compact('alliance', 'character_id'));
    }

    /**
     * @param \Illuminate\Support\Collection $universe_name
     *
     * @return string
     */
    private function getUnknownIntelView(Collection $universe_name): string
    {

        return view('web::partials.unknown', [
            'unknown_id' => $universe_name['unknown_id'],
            'character_id' => $universe_name['character_id'],
        ]);
    }
}
