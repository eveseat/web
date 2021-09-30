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

namespace Seat\Web\Http\Controllers\Character;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\DataTables\Character\CharacterDataTable;
use Seat\Web\Http\DataTables\Scopes\CharacterScope;

/**
 * Class CharacterController.
 *
 * @package Seat\Web\Http\Controllers\Character
 */
class CharacterController extends Controller
{
    /**
     * @param  \Seat\Web\Http\DataTables\Character\CharacterDataTable  $dataTable
     * @return mixed
     */
    public function index(CharacterDataTable $dataTable)
    {
        return $dataTable
            ->addScope(new CharacterScope)
            ->render('web::character.list');
    }

    /**
     * @param  \Seat\Eveapi\Models\Character\CharacterInfo  $character
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show(CharacterInfo $character)
    {
        // by default, redirect user to character sheet
        if (Gate::allows('character.sheet', $character))
            return redirect()->route('character.view.sheet', [
                'character' => $character,
            ]);

        // collect all registered routes for character scope and sort them alphabetically
        $configured_routes = array_values(Arr::sort(config('package.character.menu'), function ($menu) {
            return $menu['name'];
        }));

        // for each route, check if the current user got a valid access and redirect him to the first valid entry
        foreach ($configured_routes as $menu) {
            $permissions = $menu['permission'];

            if (Gate::any(is_array($permissions) ? $permissions : [$permissions], $character)) {
                return redirect()->route($menu['route'], [
                    'character' => $character,
                ]);
            }
        }

        $message = sprintf('Request to %s was denied by the characterbouncer.', request()->path());

        event('security.log', [$message, 'authorization']);

        // Redirect away from the original request
        return redirect()->route('auth.unauthorized');
    }

    /**
     * @param  \Seat\Eveapi\Models\Character\CharacterInfo  $character
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getShip(CharacterInfo $character)
    {
        $ship = $character->ship;

        return view('web::character.includes.modals.fitting.content', compact('ship'));
    }

    /**
     * @param  \Seat\Eveapi\Models\Character\CharacterInfo  $character
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Exception
     */
    public function destroy(CharacterInfo $character)
    {
        $character->delete();

        return redirect()->back()
            ->with('success', sprintf('Character %s has been successfully removed.', $character->name));
    }
}
