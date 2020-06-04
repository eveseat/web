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

namespace Seat\Web\Http\Controllers\Support;

use Illuminate\Http\Request;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Eveapi\Models\Mail\MailHeader;
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
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getSearch(Request $request)
    {

        $query = $request->q;

        return view('web::search.result', compact('query'));

    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     * @throws \Exception
     */
    public function getSearchCharactersData(Request $request)
    {

        $characters = $this->doSearchCharacters();

        return DataTables::of($characters)
            ->editColumn('name', function ($row) {

                $character = CharacterInfo::find($row->character_id);

                return view('web::partials.character', compact('character'));
            })
            ->editColumn('corporation_id', function ($row) {

                $corporation = CorporationInfo::find($row->corporation_id);

                return view('web::partials.corporation', compact('corporation'));
            })
            ->rawColumns(['name', 'corporation_id'])
            ->make(true);

    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     * @throws \Exception
     */
    public function getSearchCorporationsData(Request $request)
    {

        $corporations = $this->doSearchCorporations();

        return DataTables::of($corporations)
            ->editColumn('name', function ($row) {

                $corporation = CorporationInfo::find($row->corporation_id) ?: $row->corporation_id;

                return view('web::partials.corporation', compact('corporation'));
            })
            ->editColumn('ceo_id', function ($row) {

                $character = CharacterInfo::find($row->ceo_id) ?: $row->ceo_id;

                return view('web::partials.character', compact('character'));
            })
            ->editColumn('alliance_id', function ($row) {

                $alliance = $row->alliance_id;

                if (empty($alliance))
                    return '';

                return view('web::partials.alliance', compact('alliance'));
            })
            ->rawColumns(['name', 'ceo_id', 'alliance_id'])
            ->make(true);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     * @throws \Exception
     */
    public function getSearchMailData(Request $request)
    {

        $mail = $this->doSearchCharacterMail();

        return DataTables::of($mail)
            ->editColumn('from', function ($row) {

                $character = CharacterInfo::find($row->from) ?: $row->from;

                return view('web::partials.character', compact('character'));
            })
            ->editColumn('subject', function ($row) {

                return view('web::character.partials.mailtitle', compact('row'));
            })
            ->addColumn('body_clean', function (MailHeader $row) {

                return strip_tags(str_limit(clean_ccp_html($row->body->body), 30, '...'));
            })
            ->editColumn('tocounts', function ($row) {

                return view('web::character.partials.mailtocounts', compact('row'));
            })
            ->addColumn('read', function ($row) {

                return view('web::character.partials.mailread', compact('row'));
            })
            ->addColumn('recipients', function ($row) {

                $recipients = $row->recipients->map(function ($recipient) { return $recipient->recipient_id; });

                return view('web::search.partials.mailrecipient', compact('recipients'));
            })
            ->filterColumn('from', function ($query, $keyword) {
                $resolved_ids = UniverseName::where('name', 'like', '%' . $keyword . '%')->get()->map(function ($resolved_id) { return $resolved_id->entity_id; });
                $character_info_ids = CharacterInfo::where('name', 'like', '%' . $keyword . '%')->get()->map(function ($character_info) { return $character_info->character_id; });

                $query->whereIn('from', array_merge($resolved_ids->toArray(), $character_info_ids->toArray()));
            })
            ->filterColumn('recipients', function ($query, $keyword) {
                $resolved_ids = UniverseName::where('name', 'like', '%' . $keyword . '%')->get()->map(function ($resolved_id) { return $resolved_id->entity_id; });
                $character_info_ids = CharacterInfo::where('name', 'like', '%' . $keyword . '%')->get()->map(function ($character_info) { return $character_info->character_id; });
                $corporation_info_ids = CorporationInfo::where('name', 'like', '%' . $keyword . '%')->get()->map(function ($corporation_info) { return $corporation_info->corproation_id; });
                $query->whereIn('from', array_merge($resolved_ids->toArray(), $character_info_ids->toArray(), $corporation_info_ids->toArray()));
            })
            ->rawColumns(['from', 'subject', 'tocounts', 'read', 'recipients'])

            ->make(true);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     * @throws \Exception
     */
    public function getSearchCharacterAssetsData(Request $request)
    {

        $assets = $this->doSearchCharacterAssets();

        return DataTables::of($assets)
            ->editColumn('characterName', function ($row) {

                $character = CharacterInfo::find($row->character_id) ?: $row->character_id;

                return view('web::partials.character', compact('character'));
            })
            ->editColumn('typeName', function ($row) {

                return view('web::search.partials.typename', compact('row'));
            })
            ->rawColumns(['characterName', 'typeName'])
            ->make(true);

    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     * @throws \Exception
     */
    public function getSearchCharacterSkillsData(Request $request)
    {

        $skills = $this->doSearchCharacterSkills();

        return DataTables::of($skills)
            ->editColumn('character_name', function ($row) {

                $character = CharacterInfo::find($row->character_id) ?: $row->character_id;

                return view('web::partials.character', compact('character'));
            })
            ->editColumn('corporation_id', function ($row) {

                $corporation = CorporationInfo::find($row->corporation_id) ?: $row->corporation_id;

                return view('web::partials.corporation', compact('corporation'));
            })
            ->editColumn('typeName', function ($row) {

                return view('web::search.partials.typename', compact('row'));
            })
            ->rawColumns(['character_name', 'corporation_id', 'typeName'])
            ->make(true);

    }
}
