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
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Services\Repositories\Character\Killmails;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Models\User;
use Yajra\DataTables\DataTables;

/**
 * Class KillmailController.
 * @package Seat\Web\Http\Controllers\Character
 */
class KillmailController extends Controller
{
    use Killmails;

    /**
     * @param $character_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getKillmails(int $character_id)
    {

        return view('web::character.killmails');

    }

    /**
     * @param int $character_id
     *
     * @return mixed
     * @throws \Exception
     */
    public function getKillmailsData(int $character_id)
    {

        if (! request()->has('all_linked_characters'))
            return response('required url parameter is missing!', 400);

        if (request('all_linked_characters') === 'false')
            $character_ids = collect($character_id);

        $user_group = User::find($character_id)->group->users
            ->filter(function ($user) {
                return $user->name !== 'admin' && $user->id !== 1;
            })
            ->pluck('id');

        if (request('all_linked_characters') === 'true')
            $character_ids = $user_group;

        $killmails = $this->getCharacterKillmails($character_ids);

        return DataTables::of($killmails)
            ->addColumn('victim', function ($row) {

                if (is_null($row->killmail_victim))
                    return '';

                $character_id = $row->character_id;

                $character = CharacterInfo::find($row->killmail_victim->character_id) ?: $row->killmail_victim->character_id;
                $corporation = CorporationInfo::find($row->killmail_victim->corporation_id) ?: $row->killmail_victim->corporation_id;

                $view = view('web::partials.character', compact('character', 'character_id'))
                    . '</br>'
                    . view('web::partials.corporation', compact('corporation', 'character_id'));

                $alliance = '';

                if (! empty($row->killmail_victim->alliance_id)) {
                    $alliance = view('web::partials.alliance', ['alliance' => $row->killmail_victim->alliance_id, 'character_id' => $character_id]);
                }

                    return $view . $alliance;
            })
            ->addColumn('ship', function ($row) {

                if (is_null($row->killmail_victim))
                    return '';

                $ship_type = $row->killmail_victim->ship_type;

                return view('web::partials.killmailtype', compact('ship_type'))
                    ->render();
            })
            ->addColumn('place', function ($row) {

                if (is_null($row->killmail_detail))
                    return '';

                $place = $row->killmail_detail->solar_system;

                return view('web::partials.killmailsystem', compact('place'))
                    ->render();
            })
            ->addColumn('zkb', function ($row) {

                return view('web::partials.killmailzkb', compact('row'))
                    ->render();
            })
            ->rawColumns(['victim', 'ship', 'place', 'zkb'])
            ->make(true);

    }
}
