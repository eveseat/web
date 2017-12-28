<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015, 2016, 2017  Leon Jacobs
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

namespace Seat\Web\Http\Controllers\Auth;

use Laravel\Socialite\Contracts\Factory as Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Seat\Eveapi\Models\RefreshToken;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Models\User;

/**
 * Class SsoController.
 * @package Seat\Web\Http\Controllers\Auth
 */
class SsoController extends Controller
{
    /**
     * Redirect the user to the Eve Online authentication page.
     * TODO: Make the scopes requested by SeAT configurable in the UI.
     *
     * @param \Laravel\Socialite\Contracts\Factory $social
     *
     * @return \Seat\Web\Http\Controllers\Auth\Response
     */
    public function redirectToProvider(Socialite $social)
    {

        return $social->driver('eveonline')
            ->scopes(config('eveapi.scopes'))
            ->redirect();
    }

    /**
     * Obtain the user information from Eve Online.
     *
     * @param \Laravel\Socialite\Contracts\Factory $social
     *
     * @return \Seat\Web\Http\Controllers\Auth\Response
     * @throws \Seat\Services\Exceptions\SettingException
     */
    public function handleProviderCallback(Socialite $social)
    {

        $eve_data = $social->driver('eveonline')->user();

        // Get or create the User bound to this login.
        $user = $this->findOrCreateUser($eve_data);

        // Update the refresh token for this character.
        $this->updateRefreshToken($eve_data);

        // Login the account
        auth()->login($user, true);

        // Set the main characterID based on the response.
        $this->setCharacterId($eve_data);

        return redirect()->intended();
    }

    /**
     * Check if a user exists in the database, else, create
     * and return the User object.
     *
     * @param \Laravel\Socialite\Two\User $user
     *
     * @return \Seat\Web\Models\User
     */
    private function findOrCreateUser(SocialiteUser $user): User
    {

        if ($existing = User::where('character_owner_hash', $user->character_owner_hash)->first())
            return $existing;

        return User::create([
            'name'                 => $user->name,
            'character_owner_hash' => $user->character_owner_hash,
        ]);
    }

    /**
     * @param \Laravel\Socialite\Two\User $eve_data
     */
    public function updateRefreshToken(SocialiteUser $eve_data): void
    {

        RefreshToken::firstOrNew(['character_id' => $eve_data->character_id])
            ->fill([
                'refresh_token' => $eve_data->refresh_token,
                'scopes'        => explode(' ', $eve_data->scopes),
                'token'         => $eve_data->token,
                'expires_on'    => $eve_data->expires_on,
            ])
            ->save();
    }

    /**
     * @param \Laravel\Socialite\Two\User $data
     *
     * @throws \Seat\Services\Exceptions\SettingException
     */
    private function setCharacterId(SocialiteUser $data)
    {

        // Check if a main_character_id setting is set.
        // If not, we can pull the one from the SSO login data
        if (setting('main_character_id') === 1) {

            setting(['main_character_id', $data->character_id]);
            setting(['main_character_name', $data->name]);
        }

    }
}
