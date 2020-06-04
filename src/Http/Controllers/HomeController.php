<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2020 Leon Jacobs
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

namespace Seat\Web\Http\Controllers;

use Illuminate\View\View;
use Seat\Services\Repositories\Character\Mail;
use Seat\Services\Repositories\Eve\EveRepository;
use Seat\Services\Repositories\Seat\Stats;
use Seat\Services\Settings\Seat;

/**
 * Class HomeController.
 * @package Seat\Web\Http\Controllers
 */
class HomeController extends Controller
{
    use EveRepository, Stats, Mail;

    /**
     * @return \Illuminate\View\View
     * @throws \Seat\Services\Exceptions\SettingException
     */
    public function getHome(): View
    {

        // Warn if the admin contact has not been set yet.
        if (auth()->user()->hasSuperUser())
            if (Seat::get('admin_contact') === 'seatadmin@localhost.local')
                session()->flash('warning', trans('web::seat.admin_contact_warning'));

        // Warn if a refresh token is missing.
        if (! auth()->user()->refresh_token)
            session()->flash('warning', trans('web::seat.refresh_token_warning'));

        $server_status = $this->getEveLastServerStatus();
        $total_character_isk = $this->getTotalCharacterIsk();
        $total_character_skillpoints = $this->getTotalCharacterSkillpoints();
        $total_character_killmails = $this->getTotalCharacterKillmails();
        $total_character_mining = $this->getTotalCharacterMiningIsk();

        return view('web::home', compact(
            'server_status', 'total_character_isk', 'total_character_skillpoints',
            'total_character_killmails', 'total_character_mining'
        ));
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getServerStatusChartData()
    {

        $data = $this->getEveServerStatuses(50);

        return response()->json([
            'labels'   => $data->map(function ($item) {

                return $item->created_at->toDateTimeString();
            })->toArray(),
            'datasets' => [
                [
                    'label'           => 'Concurrent Player Count',
                    'fill'            => false,
                    'lineTension'     => 0.1,
                    'backgroundColor' => 'rgba(60,141,188,0.9)',
                    'borderColor'     => 'rgba(60,141,188,0.8)',
                    'data'            => $data->map(function ($item) {

                        return $item->players;
                    })->toArray(),
                ],
            ],
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEsiResponseTimeChartData()
    {

        $data = $this->getEsiResponseTimes(50);

        return response()->json([
            'labels'   => $data->map(function ($item) {

                return '"' . $item->status . '" @ ' . $item->created_at->toDateTimeString();
            })->toArray(),
            'datasets' => [
                [
                    'label'           => 'Response Time',
                    'fill'            => false,
                    'lineTension'     => 0.1,
                    'backgroundColor' => 'rgba(60,141,188,0.9)',
                    'borderColor'     => 'rgba(60,141,188,0.8)',
                    'data'            => $data->map(function ($item) {

                        return $item->request_time;
                    })->toArray(),
                ],
            ],
        ]);
    }
}
