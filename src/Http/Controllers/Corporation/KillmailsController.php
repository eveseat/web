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

namespace Seat\Web\Http\Controllers\Corporation;

use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Services\Repositories\Corporation\Killmails;
use Seat\Web\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;

/**
 * Class KillmailsController.
 * @package Seat\Web\Http\Controllers\Corporation
 */
class KillmailsController extends Controller
{
    use Killmails;

    /**
     * @param $corporation_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getKillmails(int $corporation_id)
    {

        return view('web::corporation.killmails');
    }

    /**
     * @param int $corporation_id
     *
     * @return mixed
     * @throws \Exception
     */
    public function getKillmailsData(int $corporation_id)
    {

        $killmails = $this->getCorporationKillmails($corporation_id);

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

                return view('web::partials.killmailtype', compact('ship_type'));
            })
            ->addColumn('place', function ($row) {

                if (is_null($row->killmail_detail))
                    return '';

                $place = $row->killmail_detail->solar_system;

                return view('web::partials.killmailsystem', compact('place'));
            })
            ->addColumn('zkb', function ($row) {

                return view('web::partials.killmailzkb', compact('row'));
            })
            ->rawColumns(['victim', 'ship', 'place', 'zkb'])
            ->make(true);

    }
}
