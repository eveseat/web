<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015, 2016, 2017, 2018, 2019  Leon Jacobs
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

use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\RefreshToken;
use Seat\Services\Repositories\Character\Intel;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\DataTables\Character\Intel\NoteDataTable;
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIntelSummary(int $character_id)
    {

        $token = RefreshToken::where('character_id', $character_id)->first();
        $characters = collect();
        if ($token) {
            $characters = User::with('characters')->find($token->user_id)->characters;
        }

        return view('web::character.intel.summary', compact('characters'));
    }

    /**
     * @param int $character_id
     * @throws \Exception
     */
    public function getTopWalletJournalData(int $character_id)
    {

        if (! request()->has('characters'))
            return abort(500);

        $requested_characters = (array) request()->input('characters');
        $character_ids = collect($character_id);

        if (CharacterInfo::find($character_id)->refresh_token) {
            $related_characters_ids = User::find(CharacterInfo::find($character_id)->refresh_token->user_id)
                ->characters
                ->pluck('character_id');

            $character_ids = $related_characters_ids;
        }

        $character_ids = $character_ids->filter(function ($value, $key) use ($requested_characters) {
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
     * @param int $character_id
     * @throws \Exception
     */
    public function getTopTransactionsData(int $character_id)
    {

        if (! request()->has('characters'))
            return abort(500);

        $requested_characters = (array) request()->input('characters');
        $character_ids = collect($character_id);

        if (CharacterInfo::find($character_id)->refresh_token) {
            $related_characters_ids = User::find(CharacterInfo::find($character_id)->refresh_token->user_id)
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
     * @param int $character_id
     * @throws \Exception
     */
    public function getTopMailFromData(int $character_id)
    {

        if (! request()->has('characters'))
            return abort(500);

        $requested_characters = (array) request()->input('characters');
        $character_ids = collect($character_id);

        if (CharacterInfo::find($character_id)->refresh_token) {
            $related_characters_ids = User::find(CharacterInfo::find($character_id)->refresh_token->user_id)
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
     * @param int $character_id
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
     * @return mixed
     * @throws \Exception
     */
    public function getCompareStandingsWithProfileData(int $character_id, int $profile_id)
    {

        $journal = $this->getCharacterJournalStandingsWithProfile($character_id, $profile_id);

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
     * @param int $character_id
     * @param \Seat\Web\Http\DataTables\Character\Intel\NoteDataTable $dataTable
     * @return mixed
     */
    public function notes(int $character_id, NoteDataTable $dataTable)
    {
        return $dataTable->render('web::character.intel.notes');
    }

    /**
     * @param int $character_id
     * @param int $first_party_id
     * @param int $second_party_id
     * @param string $ref_type
     * @return mixed
     * @throws \Exception
     */
    public function getJournalContent(int $character_id, int $first_party_id, int $second_party_id, string $ref_type)
    {

        $character_ids = collect($character_id);

        if (CharacterInfo::find($character_id)->refresh_token) {
            $related_characters_ids = User::find(CharacterInfo::find($character_id)->refresh_token->user_id)
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
     * @param int $character_id
     * @param int $client_id
     * @return mixed
     * @throws \Exception
     */
    public function getTransactionContent(int $character_id, int $client_id)
    {

        $character_ids = collect($character_id);

        if (CharacterInfo::find($character_id)->refresh_token) {
            $related_characters_ids = User::find(CharacterInfo::find($character_id)->refresh_token->user_id)
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
                return view('web::partials.type', ['type_id' => $row->type->typeID, 'type_name' => $row->type->typeName]);
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
     * @param int $character_id
     * @param int $from
     * @return mixed
     * @throws \Exception
     */
    public function getTopMailContent(int $character_id, int $from)
    {

        $character_ids = collect($character_id);

        if (CharacterInfo::find($character_id)->refresh_token) {
            $related_characters_ids = User::find(CharacterInfo::find($character_id)->refresh_token->user_id)
                ->characters
                ->pluck('character_id');

            $character_ids = $related_characters_ids;
        }

        $mail = $this->getMailContent($character_ids, $from);

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
            ->addColumn('read', function ($row) use ($character_id) {
                return view('web::common.mails.buttons.read', ['character_id' => $character_id, 'mail_id' => $row->mail_id]);
            })
            ->rawColumns(['from', 'subject', 'tocounts', 'read'])
            ->make(true);

    }
}
