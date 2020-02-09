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

namespace Seat\Web\Events;

use DateTime;
use Illuminate\Auth\Events\Login as LoginEvent;
use Illuminate\Support\Facades\Request;
use Seat\Console\Bus\CharacterTokenShouldUpdate;
use Seat\Console\Bus\CorporationTokenShouldUpdate;
use Seat\Eveapi\Models\Character\CharacterRole;
use Seat\Web\Models\UserLoginHistory;

/**
 * Class Login.
 *
 * @package Seat\Web\Events
 */
class Login
{
    /**
     * Update the last login values and write a new
     * login history item.
     *
     * @param \Illuminate\Auth\Events\Login $event
     */
    public static function handle(LoginEvent $event)
    {
        if (session()->has('impersonation_origin'))
            return;

        // Create a log entry for this login.
        $event->user->last_login_source = Request::getClientIp();
        $event->user->last_login = new DateTime();
        $event->user->save();

        $event->user->login_history()->save(new UserLoginHistory([
            'source'     => Request::getClientIp(),
            'user_agent' => Request::header('User-Agent'),
            'action'     => 'login',
        ]));

        $message = 'User logged in from ' . Request::getClientIp();
        event('security.log', [$message, 'authentication']);

        foreach ($event->user->refresh_tokens as $token) {

            // Update Character Information
            (new CharacterTokenShouldUpdate($token))->fire();

            // Update Corporation Information if user is Director (otherwise, keep normal flow)
            if (CharacterRole::where('character_id', $token->character_id)->where('role', 'Director')->exists())
                (new CorporationTokenShouldUpdate($token->affiliation->corporation_id, $token))->fire();
        }
    }
}
