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

namespace Seat\Web\Http\Controllers\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Eveapi\Models\Universe\UniverseName;
use Seat\Services\Search\Search;
use Seat\Web\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;

/**
 * Class SearchController.
 * @package Seat\Web\Http\Controllers\Support
 */
class SearchController extends Controller
{
    use Search;

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
                return view('web::partials.character', ['character' => $row->sender]);
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
        $assets = $this->doSearchCharacterAssets();

        return DataTables::of($assets)
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
                return view('web::partials.type', ['type_id' => $row->type->typeID, 'type_name' => $row->type->typeName]);
            })
            ->addColumn('location_name', function ($row) {
                return $row->location->name ?: ($row->location->stationName ?: $row->location->itemName);
            })
            ->filterColumn('asset_name', function ($query, $search) {
                return $query->whereRaw('character_assets.name LIKE ?', ["%$search%"]);
            })
            ->filterColumn('location_name', function ($query, $search) {
                $query->whereRaw("
                    CASE
                        WHEN location_id = 2004 THEN
                            'Asset Safety' LIKE ?
                        WHEN location_id BETWEEN 30000000 AND 33000000 THEN
                            EXISTS (SELECT itemName FROM mapDenormalize WHERE itemID = location_id AND itemName LIKE ?)
                        WHEN location_id BETWEEN 60000000 AND 64000000 THEN
                            EXISTS (SELECT stationName FROM staStations WHERE stationID = location_id AND stationName LIKE ?)
                        WHEN location_flag IN ('Hangar', 'AssetSafety') THEN
                            EXISTS (SELECT name FROM universe_structures WHERE structure_id = location_id AND name LIKE ?)
                        ELSE
                            character_assets.name LIKE ?
                    END
                ", ["%{$search}%", "%{$search}%", "%{$search}%", "%{$search}%", "%{$search}%"]);
            })
            ->make(true);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return mixed
     * @throws \Exception
     */
    public function getSearchCharacterSkillsData(Request $request)
    {
        $skills = $this->doSearchCharacterSkills();

        return DataTables::of($skills)
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
}
