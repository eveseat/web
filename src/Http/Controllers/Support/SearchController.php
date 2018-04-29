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

namespace Seat\Web\Http\Controllers\Support;

use Illuminate\Http\Request;
use Seat\Eveapi\Models\Mail\MailHeader;
use Seat\Services\Search\Search;
use Seat\Web\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;

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
     */
    public function getSearchCharactersData(Request $request)
    {

        $characters = $this->doSearchCharacters();

        return Datatables::of($characters)
            ->editColumn('name', function ($row) {

                return view('web::search.partials.charactername', compact('row'))
                    ->render();
            })
            ->editColumn('corporation_id', function ($row) {

                return view('web::search.partials.corporationname', compact('row'))
                    ->render();
            })
            ->make(true);

    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function getSearchCorporationsData(Request $request)
    {

        $corporations = $this->doSearchCorporations();

        return Datatables::of($corporations)
            ->editColumn('name', function ($row) {

                return view('web::search.partials.corporationname', compact('row'))
                    ->render();
            })
            ->editColumn('ceo_id', function ($row) {

                return view('web::search.partials.ceoname', compact('row'))
                    ->render();
            })
            ->editColumn('alliance_id', function ($row) {

                return view('web::search.partials.alliancename', compact('row'))
                    ->render();
            })
            ->make(true);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function getSearchMailData(Request $request)
    {

        $mail = $this->doSearchCharacterMail();

        return Datatables::of($mail)
            ->editColumn('from', function ($row) {

                return view('web::character.partials.mailsendername', compact('row'))
                    ->render();
            })
            ->editColumn('subject', function ($row) {

                return view('web::character.partials.mailtitle', compact('row'))
                    ->render();
            })
            ->addColumn('body', function (MailHeader $row) {

                return str_limit(clean_ccp_html($row->body->body), 30, '...');
            })
            ->editColumn('tocounts', function ($row) {

                return view('web::character.partials.mailtocounts', compact('row'))
                    ->render();
            })
            ->addColumn('read', function ($row) {

                return view('web::character.partials.mailread', compact('row'))
                    ->render();

            })
            ->make(true);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function getSearchCharacterAssetsData(Request $request)
    {

        $assets = $this->doSearchCharacterAssets();

        return Datatables::of($assets)
            ->editColumn('characterName', function ($row) {

                return view('web::search.partials.charactername', compact('row'))
                    ->render();
            })
            ->editColumn('typeName', function ($row) {

                return view('web::search.partials.typename', compact('row'))
                    ->render();
            })
            ->make(true);

    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function getSearchCharacterSkillsData(Request $request)
    {

        $skills = $this->doSearchCharacterSkills();

        return Datatables::of($skills)
            ->editColumn('character_name', function ($row) {

                return view('web::search.partials.charactername', compact('row'))
                    ->render();
            })
            ->editColumn('corporation_id', function ($row) {

                return view('web::search.partials.corporationname', compact('row'))
                    ->render();
            })
            ->editColumn('typeName', function ($row) {

                return view('web::search.partials.typename', compact('row'))
                    ->render();
            })
            ->make(true);

    }
}
