<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2022 Leon Jacobs
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

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Seat\Eveapi\Models\Character\CharacterAffiliation;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Mail\MailHeader;
use Seat\Eveapi\Models\Wallet\CharacterWalletJournal;
use Seat\Eveapi\Models\Wallet\CharacterWalletTransaction;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\DataTables\Character\Intel\NoteDataTable;
use Seat\Web\Http\DataTables\Scopes\CharacterNoteScope;
use Seat\Web\Models\StandingsProfile;
use Seat\Web\Models\User;
use Yajra\DataTables\DataTables;

/**
 * Class IntelController.
 *
 * @package Seat\Web\Http\Controllers\Character
 */
class IntelController extends Controller
{
    /**
     * @var int
     */
    protected $top_limit = 10;

    /**
     * @param  CharacterInfo  $character
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIntelSummary(CharacterInfo $character)
    {
        return view('web::character.intel.summary', compact('character'));
    }

    /**
     * @param  \Seat\Eveapi\Models\Character\CharacterInfo  $character
     *
     * @throws \Exception
     */
    public function getTopWalletJournalData(CharacterInfo $character)
    {

        if (! request()->has('characters'))
            return abort(500);

        $requested_characters = (array) request()->input('characters');
        $character_ids = collect($character->character_id);

        if ($character->refresh_token) {
            $related_characters_ids = User::find($character->refresh_token->user_id)
                ->characters
                ->pluck('character_id');

            $character_ids = $related_characters_ids;
        }

        $character_ids = $character_ids->filter(function ($value) use ($requested_characters) {
            return in_array($value, $requested_characters);
        });

        $top = $this->characterTopWalletJournalInteractions($character_ids);

        return DataTables::of($top)
            ->editColumn('ref_type', function ($row) {

                return ucwords(str_replace('_', ' ', $row->ref_type));
            })
            ->editColumn('entity_name', function ($row) {

                if($row->category == 'character') {
                    $character = (object) ['character_id' => $row->party_id, 'name' => $row->party_name];

                    return view('web::partials.character', ['character' => $character]);
                }
                elseif($row->category == 'corporation') {
                    $corporation = (object) ['corporation_id' => $row->party_id, 'name' => $row->party_name];

                    return view('web::partials.corporation', ['corporation' => $corporation]);
                }
                else
                    return '';
            })
            ->editColumn('corporation_id', function ($row) {

                return view('web::partials.corporation', ['corporation' => $row]);
            })
            ->editColumn('alliance_id', function ($row) {

                return view('web::partials.alliance', ['alliance' => $row]);
            })
            ->editColumn('faction_id', function ($row) {

                return view('web::partials.faction', ['faction' => $row]);
            })
            ->addColumn('action', function ($row) {
                return view('web::character.intel.buttons.journal', compact('row'));
            })
            ->rawColumns(['button'])
            ->make(true);

    }

    /**
     * @param  \Seat\Eveapi\Models\Character\CharacterInfo  $character
     *
     * @throws \Exception
     */
    public function getTopTransactionsData(CharacterInfo $character)
    {

        if (! request()->has('characters'))
            return abort(500);

        $requested_characters = (array) request()->input('characters');
        $character_ids = collect($character->character_id);

        if ($character->refresh_token) {
            $related_characters_ids = User::find($character->refresh_token->user_id)
                ->characters
                ->pluck('character_id');

            $character_ids = $related_characters_ids;
        }

        $character_ids = $character_ids->filter(function ($value, $key) use ($requested_characters) {
            return in_array($value, $requested_characters);
        });

        $top = $this->characterTopWalletTransactionInteractions($character_ids);

        return DataTables::of($top)
            ->editColumn('entity_name', function ($row) {
                if($row->category == 'character') {
                    $character = (object) ['character_id' => $row->party_id, 'name' => $row->party_name];

                    return view('web::partials.character', ['character' => $character]);
                }
                elseif($row->category == 'corporation') {
                    $corporation = (object) ['corporation_id' => $row->party_id, 'name' => $row->party_name];

                    return view('web::partials.corporation', ['corporation' => $corporation]);
                }
                else
                    return '';
            })
            ->editColumn('corporation_id', function ($row) {

                return view('web::partials.corporation', ['corporation' => $row]);
            })
            ->editColumn('alliance_id', function ($row) {

                return view('web::partials.alliance', ['alliance' => $row]);
            })
            ->editColumn('faction_id', function ($row) {

                return view('web::partials.faction', ['faction' => $row]);
            })
            ->addColumn('action', function ($row) {
                return view('web::character.intel.buttons.transaction', compact('row'));
            })
            ->make(true);

    }

    /**
     * @param  \Seat\Eveapi\Models\Character\CharacterInfo  $character
     *
     * @throws \Exception
     */
    public function getTopMailFromData(CharacterInfo $character)
    {

        if (! request()->has('characters'))
            return abort(500);

        $requested_characters = (array) request()->input('characters');
        $character_ids = collect($character->character_id);

        if ($character->refresh_token) {
            $related_characters_ids = User::find($character->refresh_token->user_id)
                ->characters
                ->pluck('character_id');

            $character_ids = $related_characters_ids;
        }

        $character_ids = $character_ids->filter(function ($value, $key) use ($requested_characters) {
            return in_array($value, $requested_characters);
        });

        $top = $this->characterTopMailInteractions($character_ids);

        return DataTables::of($top)
            ->editColumn('character_id', function ($row) {
                $character = (object) ['character_id' => $row->character_id ?? 0, 'name' => $row->character_name ?? ''];

                return view('web::partials.character', ['character' => $character]);
            })
            ->editColumn('corporation_id', function ($row) {

                return view('web::partials.corporation', ['corporation' => $row]);
            })
            ->editColumn('alliance_id', function ($row) {

                return view('web::partials.alliance', ['alliance' => $row]);
            })
            ->editColumn('faction_id', function ($row) {

                return view('web::partials.faction', ['faction' => $row]);
            })
            ->addColumn('action', function ($row) {
                return view('web::character.intel.buttons.mail', compact('row'));
            })
            ->make(true);
    }

    /**
     * @param  \Seat\Eveapi\Models\Character\CharacterInfo  $character
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getStandingsComparison(CharacterInfo $character)
    {
        $profiles = StandingsProfile::all();

        return view('web::character.intel.standingscompare', compact('character', 'profiles'));
    }

    /**
     * @param  \Seat\Eveapi\Models\Character\CharacterInfo  $character
     * @param  int  $profile_id
     * @return mixed
     *
     * @throws \Exception
     */
    public function getCompareStandingsWithProfileData(CharacterInfo $character, int $profile_id)
    {
        $journal = $this->getCharacterJournalStandingsWithProfile($character->character_id, $profile_id);

        return DataTables::of($journal)
            ->editColumn('character.name', function ($row) {
                return view('web::partials.character', ['character' => $row->character]);
            })
            ->editColumn('corporation.name', function ($row) {
                return view('web::partials.corporation', ['corporation' => $row->corporation]);
            })
            ->editColumn('alliance.name', function ($row) {
                return view('web::partials.alliance', ['alliance' => $row->alliance]);
            })
            ->editColumn('faction.name', function ($row) {
                return view('web::partials.faction', ['faction' => $row->faction]);
            })
            ->editColumn('standing', function ($row) {
                return view('web::partials.standing', ['standing' => $row->standing]);
            })
            ->make(true);
    }

    /**
     * @param  \Seat\Eveapi\Models\Character\CharacterInfo  $character
     * @param  \Seat\Web\Http\DataTables\Character\Intel\NoteDataTable  $dataTable
     * @return mixed
     */
    public function notes(CharacterInfo $character, NoteDataTable $dataTable)
    {
        $dataTable->addScope(new CharacterNoteScope([$character->character_id]));

        return $dataTable->render('web::character.intel.notes', compact('character'));
    }

    /**
     * @param  CharacterInfo  $character
     * @param  int  $first_party_id
     * @param  int  $second_party_id
     * @param  string  $ref_type
     * @return mixed
     *
     * @throws \Exception
     */
    public function getJournalContent(CharacterInfo $character, int $first_party_id, int $second_party_id, string $ref_type)
    {

        $character_ids = collect($character->character_id);

        if ($character->refresh_token) {
            $related_characters_ids = User::find($character->refresh_token->user_id)
                ->characters
                ->pluck('character_id');

            $character_ids = $related_characters_ids;
        }

        $journal = $this->characterWalletJournalInteractions($character_ids, $first_party_id, $second_party_id, $ref_type);

        return DataTables::of($journal)
            ->editColumn('ref_type', function ($row) {

                return view('web::common.wallets.journaltranstype', compact('row'));
            })
            ->editColumn('first_party_id', function ($row) {

                if (optional($row->first_party)->category === 'character') {

                    return view('web::partials.character', ['character' => $row->first_party]);
                }

                if (optional($row->first_party)->category === 'corporation'){

                    return view('web::partials.corporation', ['corporation' => $row->first_party]);
                }

                return view('web::partials.unknown', [
                    'unknown_id' => $row->first_party_id,
                    'character_id' => $row->character_id,
                ]);
            })
            ->editColumn('second_party_id', function ($row) {

                if (optional($row->second_party)->category === 'character') {

                    return view('web::partials.character', ['character' => $row->second_party]);
                }

                if (optional($row->second_party)->category === 'corporation') {

                    return view('web::partials.corporation', ['corporation' => $row->second_party]);
                }

                return view('web::partials.unknown', [
                    'unknown_id' => $row->second_party_id,
                    'character_id' => $row->character_id,
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
     * @param  \Seat\Eveapi\Models\Character\CharacterInfo  $character
     * @param  int  $client_id
     * @return mixed
     *
     * @throws \Exception
     */
    public function getTransactionContent(CharacterInfo $character, int $client_id)
    {

        $character_ids = collect($character->character_id);

        if ($character->refresh_token) {
            $related_characters_ids = User::find($character->refresh_token->user_id)
                ->characters
                ->pluck('character_id');

            $character_ids = $related_characters_ids;
        }

        $transactions = $this->characterWalletTransactionInteraction($character_ids, $client_id);

        return DataTables::of($transactions)
            ->editColumn('date', function ($row) {
                return view('web::partials.date', ['datetime' => $row->date]);
            })
            ->editColumn('is_buy', function ($row) {
                return view('web::partials.marketbuysell', ['is_buy' => $row->is_buy]);
            })
            ->editColumn('quantity', function ($row) {
                return number_format($row->quantity, 0);
            })
            ->editColumn('unit_price', function ($row) {

                return number_format($row->unit_price, 2);
            })
            ->editColumn('type.typeName', function ($row) {
                return view('web::partials.type', [
                    'type_id' => $row->type->typeID,
                    'type_name' => $row->type->typeName,
                    'variation' => $row->type->group->categoryID == 9 ? 'bpc' : 'icon',
                ]);
            })
            ->editColumn('location.name', function ($row) {
                return $row->location->name;
            })
            ->addColumn('total', function ($row) {
                return number($row->unit_price * $row->quantity);
            })
            ->editColumn('party.name', function ($row) {
                switch ($row->party->category) {
                    case 'character':
                        return view('web::partials.character', ['character' => $row->party]);
                    case 'corporation':
                        return view('web::partials.corporation', ['corporation' => $row->party]);
                    case 'alliance':
                        return view('web::partials.alliance', ['alliance' => $row->party]);
                    case 'faction':
                        return view('web::partials.faction', ['faction' => $row->party]);
                    default:
                        return '';
                }
            })
            ->filterColumn('total', function ($query, $keyword) {
                return $query->whereRaw('(unit_price * quantity) LIKE ?', ["%$keyword%"]);
            })
            ->orderColumn('total', '(unit_price * quantity) $1')
            ->make(true);
    }

    /**
     * @param  \Seat\Eveapi\Models\Character\CharacterInfo  $character
     * @param  int  $from
     * @return mixed
     *
     * @throws \Exception
     */
    public function getTopMailContent(CharacterInfo $character, int $from)
    {

        $character_ids = collect($character->character_id);

        if ($character->refresh_token) {
            $related_characters_ids = User::find($character->refresh_token->user_id)
                ->characters
                ->pluck('character_id');

            $character_ids = $related_characters_ids;
        }

        $mail = MailHeader::with('body', 'sender', 'recipients')
            ->select('mail_headers.mail_id', 'subject', 'from', 'timestamp')
            ->leftJoin('mail_recipients', 'mail_headers.mail_id', '=', 'mail_recipients.mail_id')
            ->where('from', $from)
            ->whereIn('recipient_id', $character_ids->toArray());

        return DataTables::of($mail)
            ->editColumn('sender.name', function ($row) {
                switch ($row->sender->category) {
                    case 'character':
                        return view('web::partials.character', ['character' => $row->sender]);
                    case 'corporation':
                        return view('web::partials.corporation', ['corporation' => $row->sender]);
                    case 'alliance':
                        return view('web::partials.alliance', ['alliance' => $row->sender]);
                    case 'faction':
                        return view('web::partials.faction', ['faction' => $row->sender]);
                    default:
                        return '';
                }
            })
            ->editColumn('subject', function ($row) {
                return view('web::character.partials.mail_title', compact('row'));
            })
            ->addColumn('recipients', function ($row) {
                return view('web::common.mails.modals.read.tags', compact('row'));
            })
            ->addColumn('read', function ($row) use ($character) {
                return view('web::common.mails.buttons.read', ['character_id' => $character->character_id, 'mail_id' => $row->mail_id]);
            })
            ->rawColumns(['from', 'subject', 'tocounts', 'read'])
            ->make(true);

    }

    /**
     * @param  \Illuminate\Support\Collection  $character_ids
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function characterTopWalletJournalInteractions(Collection $character_ids): Builder
    {

        return CharacterWalletJournal::select('first_party_id', 'second_party_id', 'ref_type', 'category', 'entity_id as party_id', 'name as party_name', DB::raw('count(*) as total'),
            DB::raw("
                CASE 
                    when universe_names.category = 'character' then 
                        (SELECT corporation_id FROM character_affiliations WHERE character_id = universe_names.entity_id) 
                    when universe_names.category = 'corporation' then 
                        (SELECT corporation_id FROM character_affiliations WHERE corporation_id = universe_names.entity_id LIMIT 1)
                end AS corporation_id,
                CASE 
                    when universe_names.category = 'character' then 
                        (SELECT alliance_id FROM character_affiliations WHERE character_id = universe_names.entity_id) 
                    when universe_names.category = 'corporation' then 
                        (SELECT alliance_id FROM character_affiliations WHERE corporation_id = universe_names.entity_id LIMIT 1) 
                end AS alliance_id, 
                CASE 
                    when universe_names.category = 'character' then 
                        (SELECT faction_id FROM character_affiliations WHERE character_id = universe_names.entity_id) 
                    when universe_names.category = 'corporation' then 
                        (SELECT faction_id FROM character_affiliations WHERE corporation_id = universe_names.entity_id LIMIT 1)
                end AS faction_id
                ")
        )
            ->leftJoin('universe_names', 'universe_names.entity_id', '=', 'character_wallet_journals.first_party_id')
            ->whereIn('character_wallet_journals.character_id', $character_ids->toArray())
            ->whereNotIn('character_wallet_journals.first_party_id', $character_ids->toArray())
            ->groupBy('first_party_id', 'second_party_id', 'ref_type', 'category', 'party_id', 'party_name')
            ->union(
                CharacterWalletJournal::select('first_party_id', 'second_party_id', 'ref_type', 'category', 'entity_id as party_id', 'name as party_name', DB::raw('count(*) as total'), DB::raw("CASE when universe_names.category = 'character' then (SELECT corporation_id FROM character_affiliations WHERE character_id = universe_names.entity_id) when universe_names.category = 'corporation' then (SELECT corporation_id FROM character_affiliations WHERE corporation_id = universe_names.entity_id LIMIT 1) end AS corporation_id, CASE when universe_names.category = 'character' then (SELECT alliance_id FROM character_affiliations WHERE character_id = universe_names.entity_id) when universe_names.category = 'corporation' then (SELECT alliance_id FROM character_affiliations WHERE corporation_id = universe_names.entity_id LIMIT 1) end AS alliance_id, CASE when universe_names.category = 'character' then (SELECT faction_id FROM character_affiliations WHERE character_id = universe_names.entity_id) when universe_names.category = 'corporation' then (SELECT faction_id FROM character_affiliations WHERE corporation_id = universe_names.entity_id LIMIT 1) end AS faction_id"))
                    ->leftJoin('universe_names', 'universe_names.entity_id', '=', 'character_wallet_journals.second_party_id')
                    ->whereIn('character_wallet_journals.character_id', $character_ids->toArray())
                    ->whereNotIn('character_wallet_journals.second_party_id', $character_ids->toArray())
                    ->groupBy('first_party_id', 'second_party_id', 'ref_type', 'category', 'party_id', 'party_name')
            )
            ->orderBy('total', 'desc');
    }

    /**
     * @param  \Illuminate\Support\Collection  $character_ids
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function characterTopWalletTransactionInteractions(Collection $character_ids): Builder
    {

        return CharacterWalletTransaction::select('client_id', 'category', 'entity_id as party_id', 'name as party_name', DB::raw('count(*) as total'),
            DB::raw("
                CASE 
                    when universe_names.category = 'character' then 
                        (SELECT corporation_id FROM character_affiliations WHERE character_id = universe_names.entity_id) 
                    when universe_names.category = 'corporation' then 
                        (SELECT corporation_id FROM character_affiliations WHERE corporation_id = universe_names.entity_id LIMIT 1)
                end AS corporation_id,
                CASE 
                    when universe_names.category = 'character' then 
                        (SELECT alliance_id FROM character_affiliations WHERE character_id = universe_names.entity_id) 
                    when universe_names.category = 'corporation' then 
                        (SELECT alliance_id FROM character_affiliations WHERE corporation_id = universe_names.entity_id LIMIT 1) 
                end AS alliance_id, 
                CASE 
                    when universe_names.category = 'character' then 
                        (SELECT faction_id FROM character_affiliations WHERE character_id = universe_names.entity_id) 
                    when universe_names.category = 'corporation' then 
                        (SELECT faction_id FROM character_affiliations WHERE corporation_id = universe_names.entity_id LIMIT 1)
                end AS faction_id
                ")
        )
            ->leftJoin('universe_names', 'universe_names.entity_id', '=', 'character_wallet_transactions.client_id')
            ->whereIn('character_wallet_transactions.character_id', $character_ids->toArray())
            ->whereNotIn('character_wallet_transactions.client_id', $character_ids->toArray())
            ->groupBy('client_id', 'category', 'party_id', 'party_name')
            ->orderBy('total', 'desc');

    }

    /**
     * @param  \Illuminate\Support\Collection  $character_ids
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function characterTopMailInteractions(Collection $character_ids): Builder
    {
        return MailHeader::select('from', 'entity_id as character_id', 'name as character_name', DB::raw('COUNT(*) as total'),
            DB::raw("
                CASE 
                    when universe_names.category = 'character' then 
                        (SELECT corporation_id FROM character_affiliations WHERE character_id = universe_names.entity_id) 
                    when universe_names.category = 'corporation' then 
                        (SELECT corporation_id FROM character_affiliations WHERE corporation_id = universe_names.entity_id LIMIT 1)
                end AS corporation_id,
                CASE 
                    when universe_names.category = 'character' then 
                        (SELECT alliance_id FROM character_affiliations WHERE character_id = universe_names.entity_id) 
                    when universe_names.category = 'corporation' then 
                        (SELECT alliance_id FROM character_affiliations WHERE corporation_id = universe_names.entity_id LIMIT 1) 
                end AS alliance_id, 
                CASE 
                    when universe_names.category = 'character' then 
                        (SELECT faction_id FROM character_affiliations WHERE character_id = universe_names.entity_id) 
                    when universe_names.category = 'corporation' then 
                        (SELECT faction_id FROM character_affiliations WHERE corporation_id = universe_names.entity_id LIMIT 1)
                end AS faction_id
                ")
        )
            ->leftJoin('universe_names', 'mail_headers.from', '=', 'universe_names.entity_id')
            ->leftJoin('mail_recipients', 'mail_headers.mail_id', '=', 'mail_recipients.mail_id')
            ->whereIn('recipient_id', $character_ids->toArray())
            ->whereNotIn('from', $character_ids->toArray())
            ->groupBy('from', 'entity_id', 'category', 'name')
            ->orderBy('total', 'desc');
    }

    /**
     * @param  int  $character_id
     * @param  int  $profile_id
     * @return mixed
     */
    private function getCharacterJournalStandingsWithProfile(int $character_id, int $profile_id)
    {

        return CharacterAffiliation::with('character', 'corporation', 'alliance', 'faction')
            ->select(
                DB::raw('count(*) as total'),
                'character_affiliations.character_id',
                'character_affiliations.corporation_id',
                'character_affiliations.alliance_id',
                'character_affiliations.faction_id',
                'standings_profile_standings.standing',
                'standings_profile_standings.category as standing_type',
                'universe_names.entity_id as standing_match_on'
            )->leftJoin('character_wallet_journals', function ($join) {

                $join->on(
                    'character_affiliations.character_id', '=',
                    'character_wallet_journals.first_party_id'
                );

                $join->orOn(
                    'character_affiliations.character_id', '=',
                    'character_wallet_journals.second_party_id'
                );

            })->join('standings_profile_standings', function ($join) {

                $join->on(
                    'standings_profile_standings.entity_id', '=',
                    'character_affiliations.character_id'
                );

                $join->orOn(
                    'standings_profile_standings.entity_id', '=',
                    'character_affiliations.corporation_id'
                );

                $join->orOn(
                    'standings_profile_standings.entity_id', '=',
                    'character_affiliations.alliance_id'
                );

            })
            ->join('universe_names', function ($join) {
                $join->on('standings_profile_standings.entity_id', '=', 'universe_names.entity_id');
            })
            ->where('character_wallet_journals.character_id', $character_id)
            ->where('standings_profile_standings.standings_profile_id', $profile_id)
            ->groupBy('universe_names.entity_id', 'character_id', 'corporation_id', 'alliance_id', 'faction_id', 'standing', 'standings_profile_standings.category');

    }

    /**
     * @param  \Illuminate\Support\Collection  $character_ids
     * @param  int  $first_party_id
     * @param  int  $second_party_id
     * @param  string  $ref_type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function characterWalletJournalInteractions(Collection $character_ids, int $first_party_id, int $second_party_id, string $ref_type): Builder
    {

        return CharacterWalletJournal::with('first_party', 'second_party')
            ->whereIn('character_id', $character_ids->toArray())
            ->where('first_party_id', '=', $first_party_id)
            ->where('second_party_id', '=', $second_party_id)
            ->where('ref_type', '=', $ref_type);
    }

    /**
     * @param  \Illuminate\Support\Collection  $character_ids
     * @param  int  $client_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function characterWalletTransactionInteraction(Collection $character_ids, int $client_id): Builder
    {

        return CharacterWalletTransaction::with('party', 'type', 'location')
            ->select(DB::raw('
            *, CASE
                when character_wallet_transactions.location_id BETWEEN 66015148 AND 66015151 then
                    (SELECT s.stationName FROM staStations AS s
                      WHERE s.stationID=character_wallet_transactions.location_id-6000000)
                when character_wallet_transactions.location_id BETWEEN 66000000 AND 66014933 then
                    (SELECT s.stationName FROM staStations AS s
                      WHERE s.stationID=character_wallet_transactions.location_id-6000001)
                when character_wallet_transactions.location_id BETWEEN 66014934 AND 67999999 then
                    (SELECT d.name FROM `sovereignty_structures` AS c
                      JOIN universe_stations d ON c.structure_id = d.station_id
                      WHERE c.structure_id=character_wallet_transactions.location_id-6000000)
                when character_wallet_transactions.location_id BETWEEN 60014861 AND 60014928 then
                    (SELECT d.name FROM `sovereignty_structures` AS c
                      JOIN universe_stations d ON c.structure_id = d.station_id
                      WHERE c.structure_id=character_wallet_transactions.location_id)
                when character_wallet_transactions.location_id BETWEEN 60000000 AND 61000000 then
                    (SELECT s.stationName FROM staStations AS s
                      WHERE s.stationID=character_wallet_transactions.location_id)
                when character_wallet_transactions.location_id BETWEEN 61000000 AND 61001146 then
                    (SELECT d.name FROM `sovereignty_structures` AS c
                      JOIN universe_stations d ON c.structure_id = d.station_id
                      WHERE c.structure_id=character_wallet_transactions.location_id)
                when character_wallet_transactions.location_id > 61001146 then
                    (SELECT name FROM `universe_structures` AS c
                     WHERE c.structure_id = character_wallet_transactions.location_id)
                else (SELECT m.itemName FROM mapDenormalize AS m
                    WHERE m.itemID=character_wallet_transactions.location_id) end
                AS locationName'
            ))
            ->whereIn('character_id', $character_ids)
            ->where('client_id', $client_id);
    }
}
