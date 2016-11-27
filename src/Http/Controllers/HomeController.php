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

namespace Seat\Web\Http\Controllers;

use Seat\Eveapi\Models\Character\CharacterSheet;
use Seat\Services\Repositories\Character\Mail;
use Seat\Services\Repositories\Eve\EveRepository;
use Seat\Services\Repositories\Seat\Stats;
use Seat\Services\Settings\Seat;

/**
 * Class HomeController
 * @package Seat\Web\Http\Controllers
 */
class HomeController extends Controller
{

    use EveRepository, Stats, Mail;

    /**
     *
     * @return \Illuminate\View\View
     */
    public function getHome()
    {

        // Warn if the admin contact has not been set yet.
        if (auth()->user()->hasSuperUser())
            if (Seat::get('admin_contact') === 'seatadmin@localhost.local')
                session()->flash('warning', trans('web::seat.admin_contact_warning'));

        // Check for the default EVE SSO generated email.
        if (str_contains(auth()->user()->email, '@seat.local'))
            session()->flash('warning', trans('web::seat.sso_email_warning'));

        $server_status = $this->getEveLastServerStatus();
        $total_character_isk = $this->getTotalCharacterIsk();
        $total_character_skillpoints = $this->getTotalCharacterSkillpoints();
        $total_character_killmails = $this->getTotalCharacterKillmails();
        $newest_mail = $this->getAllCharacterNewestMail();

        return view('web::home', compact(
            'server_status', 'total_character_isk', 'total_character_skillpoints',
            'total_character_killmails', 'newest_mail'
        ));
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getServerStatusChartData()
    {

        $data = $this->getEveServerStatuses();

        return response()->json([
            'labels'   => $data->map(function ($item) {

                return $item->currentTime;
            })->toArray(),
            'datasets' => [
                [
                    'label'           => 'Concurrent Player Count',
                    'fill'            => false,
                    'lineTension'     => 0.1,
                    'backgroundColor' => "rgba(60,141,188,0.9)",
                    'borderColor'     => "rgba(60,141,188,0.8)",
                    'data'            => $data->map(function ($item) {

                        return $item->onlinePlayers;
                    })->toArray(),
                ]
            ]
        ]);

    }

    /**
     * @param $character_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMainCharacterSkillsLevelChartData($character_id)
    {
        if ($character_id == 1) {
            return response()->json([]);
        }

        $data = $this->getSkillsAmountPerLevel($character_id);

        return response()->json([
            'labels' => [
                'Level 0', 'Level 1', 'Level 2', 'Level 3', 'Level 4', 'Level 5'
            ],
            'datasets' => [
                [
                    'data'  => $data,
                    'backgroundColor' => [
                        '#00c0ef',
                        '#39cccc',
                        '#00a65a',
                        '#605ca8',
                        '#001f3f',
                        '#3c8dbc'
                    ]
                ]
            ]
        ]);
    }

    /**
     * @param $character_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMainCharacterSkillsCoverageChartData($character_id)
    {
        if ($character_id == 1) {
            return response()->json([]);
        }

        $data = $this->getSkillCoverage($character_id);

        $character = CharacterSheet::where('characterID', $character_id)->first();

        return response()->json([
            'labels' => $data->map(function ($item) {
                return $item->marketGroupName;
            })->toArray(), // skills category
            'datasets' => [
                [
                    'label' => $character->name,
                    'data' => $data->map(function($item) {
                        return round($item->characterAmount / $item->gameAmount * 100, 2);  // character / in game rate
                    })->toArray(),
                    'fill' => true,
                    'backgroundColor' => 'rgba(60,141,188,0.3)',
                    'borderColor' => '#3c8dbc',
                    'pointBackgroundColor' => '#3c8dbc',
                    'pointBorderColor' => '#fff'
                ]
            ]
        ]);
    }

}
