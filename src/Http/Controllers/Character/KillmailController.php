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
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Services\Repositories\Character\Killmails;
use Seat\Web\Http\Controllers\Controller;
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

        $killmails = $this->getCharacterKillmails($character_id);

        return DataTables::of($killmails)
            ->addColumn('victim', function ($row) {

                $character_id = $row->character_id;

                $character = CharacterInfo::find($row->killmail_victims->character_id) ?: $row->killmail_victims->character_id;
                $corporation = CorporationInfo::find($row->killmail_victims->corporation_id) ?: $row->killmail_victims->corporation_id;

                $view = view('web::partials.character', compact('character', 'character_id'))
                    . '</br>'
                    . view('web::partials.corporation', compact('corporation', 'character_id'));

                if(!empty($row->killmail_victims->alliance_id)){
                    $alliance = ' ('
                    . img('alliance', $row->killmail_victims->alliance_id, 64, ['class' => 'img-circle eve-icon small-icon'], false)
                        . '<span class="id-to-name" data-id=' . $row->killmail_victims->alliance_id . '>' . trans("web::seat.unknown") . '</span>'
                    . ')';
                } else $alliance = '';

                    return $view . $alliance;
            })
            ->addColumn('ship', function ($row) {

                $ship_type = $row->killmail_victims->ship_type;

                return view('web::partials.killmailtype', compact('ship_type'))
                    ->render();
            })
            ->addColumn('place', function ($row) {

                $place = $row->killmail_details->solar_system;

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
