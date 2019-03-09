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
use Seat\Services\Repositories\Character\Character;
use Seat\Web\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Request;
use Yajra\DataTables\DataTables;

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
     * @throws \Exception
     */
    public function getCharactersData(Request $request)
    {

        $characters = ($request->filtered === 'true') ?
            auth()->user()->group->users
                ->filter(function ($user) {
                    return $user->name !== 'admin' && $user->id !== 1;
                })
                ->map(function ($user) {
                    return $user->character;
            }) :
            $this->getAllCharactersWithAffiliations(false);

        return DataTables::of($characters)
            ->addColumn('name_view', function ($row) {

                $character = $row;

                return view('web::partials.character', compact('character'));
            })
            ->editColumn('corporation_id', function ($row) {

                $corporation = $row->corporation_id;

                return view('web::partials.corporation', compact('corporation'));
            })
            ->editColumn('alliance_id', function ($row) {

                $alliance = $row->alliance_id;

                if (empty($alliance))
                    return '';

                return view('web::partials.alliance', compact('alliance'));
            })
            ->editColumn('actions', function ($row) {

                return view('web::character.partials.delete', compact('row'))
                    ->render();
            })
            ->rawColumns(['name_view', 'corporation_id', 'alliance_id', 'actions'])
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
