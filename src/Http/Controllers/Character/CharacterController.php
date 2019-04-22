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
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function getCharacter()
    {
        // by default, redirect user to character sheet
        if (auth()->user()->has('character.sheet'))
            return redirect()->route('character.view.sheet', [
                'character_id' => request()->character_id,
            ]);

        // collect all registered routes for character scope and sort them alphabetically
        $configured_routes = array_values(array_sort(config('package.character.menu'), function ($menu) {
            return $menu['name'];
        }));

        // for each route, check if the current user got a valid access and redirect him to the first valid entry
        foreach ($configured_routes as $menu) {
            $permissions = $menu['permission'];

            if (! is_array($permissions))
                $permissions = [$permissions];

            foreach ($permissions as $permission) {
                if (auth()->user()->has($permission))
                    return redirect()->route($menu['route'], [
                        'character_id' => request()->character_id,
                    ]);
            }
        }

        $message = sprintf('Request to %s was denied by the characterbouncer.', request()->path());

        event('security.log', [$message, 'authorization']);

        // Redirect away from the original request
        return redirect()->route('auth.unauthorized');
    }

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
