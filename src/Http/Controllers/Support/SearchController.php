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

namespace Seat\Web\Http\Controllers\Support;

use Illuminate\Http\Request;
use Seat\Services\Search\Search;
use Seat\Web\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;

/**
 * Class SearchController
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
    public function getSearchCharacters(Request $request)
    {

        $characters = $this->doSearchCharacters($request->input('search.value'));

        return Datatables::of($characters)
            ->editColumn('characterName', function ($row) {

                return view('web::search.partials.charactername', compact('row'))
                    ->render();
            })
            ->editColumn('corporationName', function ($row) {

                return view('web::search.partials.corporationname', compact('row'))
                    ->render();
            })
            ->editColumn('shipTypeName', function ($row) {

                return view('web::search.partials.shiptypename', compact('row'))
                    ->render();
            })
            ->make(true);

    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function getSearchCorporations(Request $request)
    {

        $corporations = $this->doSearchCorporations($request->input('search.value'));

        return Datatables::of($corporations)
            ->editColumn('corporationName', function ($row) {

                return view('web::search.partials.corporationname', compact('row'))
                    ->render();
            })
            ->editColumn('ceoName', function ($row) {

                return view('web::search.partials.ceoname', compact('row'))
                    ->render();
            })
            ->editColumn('allianceName', function ($row) {

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
    public function getSearchMail(Request $request)
    {

        $mail = $this->doSearchCharacterMail($request->input('search.value'));

        return Datatables::of($mail)
            ->editColumn('senderName', function ($row) {

                return view('web::character.partials.mailsendername', compact('row'))
                    ->render();
            })
            ->editColumn('title', function ($row) {

                return view('web::character.partials.mailtitle', compact('row'))
                    ->render();
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

}
