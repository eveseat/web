<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2021 Leon Jacobs
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

namespace Seat\Web\Http\Controllers\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Seat\Eveapi\Models\Assets\CharacterAsset;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Character\CharacterSkill;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Eveapi\Models\Mail\MailHeader;
use Seat\Eveapi\Models\Universe\UniverseName;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\DataTables\Character\Intel\Assets\Columns\Station;
use Seat\Web\Http\DataTables\Scopes\CharacterMailScope;
use Seat\Web\Http\DataTables\Scopes\CharacterScope;
use Seat\Web\Http\DataTables\Scopes\CorporationScope;
use Yajra\DataTables\DataTables;

/**
 * Class SearchController.
 * @package Seat\Web\Http\Controllers\Support
 */
class SearchController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getSearch(Request $request)
    {

        $query = $request->q;

        return view('web::search.result', compact('query'));

    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return mixed
     * @throws \Exception
     */
    public function getSearchCharactersData(Request $request)
    {

        $characters = $this->doSearchCharacters();

        return DataTables::of($characters)
            ->editColumn('name', function ($row) {
                return view('web::partials.character', ['character' => $row]);
            })
            ->editColumn('affiliation.corporation.name', function ($row) {
                return view('web::partials.corporation', ['corporation' => $row->affiliation->corporation]);
            })
            ->editColumn('affiliation.alliance.name', function ($row) {
                return view('web::partials.alliance', ['alliance' => $row->affiliation->alliance]);
            })
            ->make(true);

    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return mixed
     * @throws \Exception
     */
    public function getSearchCorporationsData(Request $request)
    {

        $corporations = $this->doSearchCorporations();

        return DataTables::of($corporations)
            ->editColumn('name', function ($row) {
                return view('web::partials.corporation', ['corporation' => $row]);
            })
            ->editColumn('ceo.name', function ($row) {
                return view('web::partials.character', ['character' => $row->ceo]);
            })
            ->editColumn('alliance.name', function ($row) {
                return view('web::partials.alliance', ['alliance' => $row->alliance]);
            })
            ->make(true);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return mixed
     * @throws \Exception
     */
    public function getSearchMailData(Request $request)
    {

        $mail = $this->doSearchCharacterMail();

        return DataTables::of($mail)
            ->editColumn('timestamp', function ($row) {
                return view('web::partials.date', ['datetime' => $row->timestamp]);
            })
            ->editColumn('sender.name', function ($row) {
                switch ($row->sender->category) {
                    case 'character':
                        return view('web::partials.character', ['character' => $row->sender]);
                    case 'corporation':
                        return view('web::partials.corporation', ['corporation' => $row->sender]);
                    case 'alliance':
                        return view('web::partials.alliance', ['alliance' => $row->sender]);
                }

                return '';
            })
            ->editColumn('subject', function ($row) {
                return view('web::character.partials.mail_title', compact('row'));
            })
            ->editColumn('body.body', function ($row) {
                return strip_tags(Str::limit(clean_ccp_html($row->body->body), 30, '...'));
            })
            ->addColumn('tocounts', function ($row) {
                return view('web::common.mails.modals.read.tags', compact('row'));
            })
            ->addColumn('read', function ($row) {
                return view('web::common.mails.buttons.read', ['character_id' => $row->from, 'mail_id' => $row->mail_id]);
            })
            ->addColumn('recipients', function ($row) {

                $recipients = $row->recipients->map(function ($recipient) { return $recipient->recipient_id; });

                return view('web::search.partials.mailrecipient', compact('recipients'));
            })
            ->filterColumn('recipients', function ($query, $keyword) {
                $query->whereHas('recipients', function ($sub_query) use ($keyword) {
                    $resolved_ids = UniverseName::where('name', 'like', '%' . $keyword . '%')
                        ->get()
                        ->map(function ($resolved_id) {
                            return $resolved_id->entity_id;
                        });

                    $character_info_ids = CharacterInfo::where('name', 'like', '%' . $keyword . '%')
                        ->get()
                        ->map(function ($character_info) {
                            return $character_info->character_id;
                        });

                    $corporation_info_ids = CorporationInfo::where('name', 'like', '%' . $keyword . '%')
                        ->get()
                        ->map(function ($corporation_info) {
                            return $corporation_info->corproation_id;
                        });

                    $sub_query->whereIn('recipient_id',
                        array_merge($resolved_ids->toArray(), $character_info_ids->toArray(), $corporation_info_ids->toArray()));
                });
            })
            ->make(true);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return mixed
     * @throws \Exception
     */
    public function getSearchCharacterAssetsData(Request $request)
    {
        $scope = new CharacterScope('character.asset');

        $query = CharacterAsset::with('character', 'type', 'type.group', 'station', 'container', 'container.station',
            'character.affiliation.corporation', 'character.affiliation.alliance')
            ->select()
            ->addSelect('character_assets.name as asset_name');

        $scope->apply($query);

        $table = DataTables::of($query)
            ->editColumn('asset_name', function ($row) {
                return $row->asset_name ?: '';
            })
            ->editColumn('character.name', function ($row) {
                return view('web::partials.character', ['character' => $row->character]);
            })
            ->editColumn('character.affiliation.corporation.name', function ($row) {
                return view('web::partials.corporation', ['corporation' => $row->character->affiliation->corporation]);
            })
            ->editColumn('character.affiliation.alliance.name', function ($row) {
                return view('web::partials.alliance', ['alliance' => $row->character->affiliation->alliance]);
            })
            ->editColumn('type.typeName', function ($row) {
                return view('web::partials.type', [
                    'type_id' => $row->type->typeID,
                    'type_name' => $row->type->typeName,
                    'variation' => $row->type->group->categoryID == 9 ? 'bpc' : 'icon',
                ]);
            })
            ->addColumn('station', function ($row) {
                return $row->station->name ?: ($row->structure->name ?: $row->container->name);
            })
            ->filterColumn('asset_name', function ($query, $search) {
                return $query->whereRaw('character_assets.name LIKE ?', ["%$search%"]);
            });

        $station_column = new Station($table);
        $table->addColumn('station', $station_column)
            ->filterColumn('station', $station_column);

        return $table->make(true);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return mixed
     * @throws \Exception
     */
    public function getSearchCharacterSkillsData(Request $request)
    {
        $scope = new CharacterScope('character.skill');

        $query = CharacterSkill::with('character', 'type', 'type.group',
            'character.affiliation.corporation', 'character.affiliation.alliance');

        $scope->apply($query);

        return DataTables::of($query)
            ->editColumn('character.name', function ($row) {
                return view('web::partials.character', ['character' => $row->character]);
            })
            ->editColumn('character.affiliation.corporation.name', function ($row) {
                return view('web::partials.corporation', ['corporation' => $row->character->affiliation->corporation]);
            })
            ->editColumn('character.affiliation.alliance.name', function ($row) {
                return view('web::partials.alliance', ['alliance' => $row->character->affiliation->alliance]);
            })
            ->editColumn('type.typeName', function ($row) {
                return view('web::partials.type', ['type_id' => $row->type->typeID, 'type_name' => $row->type->typeName]);
            })
            ->make(true);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function doSearchCharacters()
    {
        $scope = new CharacterScope();

        $query = CharacterInfo::with('affiliation.corporation', 'affiliation.alliance')
            ->select('character_infos.*');

        $scope->apply($query);

        return $query;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function doSearchCorporations()
    {
        $scope = new CorporationScope();

        $query = CorporationInfo::with('ceo', 'alliance')
            ->select('corporation_infos.*');

        $scope->apply($query);

        return $query;
    }

    /**
     * @return mixed
     */
    private function doSearchCharacterMail()
    {
        $scope = new CharacterMailScope();

        $query = MailHeader::with('body', 'recipients', 'recipients.entity', 'sender')
            ->select('timestamp', 'from', 'subject', 'mail_headers.mail_id');

        $scope->apply($query);

        return $query;
    }
}
