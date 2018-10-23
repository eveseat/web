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

use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Services\Repositories\Character\Character;
use Seat\Web\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Request;
use Yajra\Datatables\Datatables;

/**
 * Class CharacterController.
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
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return mixed
     */
    public function getCharactersData(Request $request)
    {

        $characters = ($request->filtered === 'true') ?
            auth()->user()->group->users
                ->filter(function ($user) {
                    if(! $user->name === 'admin' || $user->id === 1)
                        return false;

                    return true;
                })
                ->map(function ($user) {
                    return $user->character;
            }) :
            $this->getAllCharactersWithAffiliations();

        return Datatables::of($characters)
            ->editColumn('name', function ($row) {

                return view('web::character.partials.charactername', compact('row'))
                    ->render();
            })
            ->editColumn('corporation_id', function ($row) {

                return view('web::character.partials.corporationname', compact('row'))
                    ->render();
            })
            ->editColumn('alliance_id', function ($row) {

                return view('web::character.partials.alliancename', compact('row'))
                    ->render();
            })
            ->editColumn('actions', function ($row) {

                return view('web::character.partials.delete', compact('row'))
                    ->render();
            })
            ->make(true);

    }

    /**
     * @param int $character_id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteCharacter(int $character_id)
    {

        CharacterInfo::find($character_id)->delete();

        return redirect()->back()->with(
            'success', 'Character deleted!'
        );
    }
}
