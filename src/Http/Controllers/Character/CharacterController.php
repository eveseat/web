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

use Seat\Services\Repositories\Character\Character;
use Seat\Web\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;

/**
 * Class CharacterController
 * @package Seat\Web\Http\Controllers\Character
 */
class CharacterController extends Controller
{

    use Character;

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCharacters()
    {

        return view('web::character.list');

    }

    /**
     * @return mixed
     */
    public function getCharactersData()
    {

        $characters = $this->getAllCharactersWithAffiliationsAndFilters();

        return Datatables::of($characters)
            ->editColumn('characterName', function ($row) {

                return view('web::character.partials.charactername', compact('row'))
                    ->render();
            })
            ->editColumn('corporationName', function ($row) {

                return view('web::character.partials.corporationname', compact('row'))
                    ->render();
            })
            ->editColumn('alliance', function ($row) {

                return view('web::character.partials.alliancename', compact('row'))
                    ->render();
            })
            ->make(true);

    }

}
