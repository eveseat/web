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

namespace Seat\Web\Http\Controllers\Auth;

use Illuminate\Support\Arr;
use Laravel\Socialite\Contracts\Factory as Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Seat\Eveapi\Jobs\Corporation\Info;
use Seat\Eveapi\Models\Character\CharacterAffiliation;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
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
     *
     * @param \Laravel\Socialite\Contracts\Factory $social
     * @return mixed
     * @throws \Seat\Services\Exceptions\SettingException
     */
    public function redirectToProvider(Socialite $social)
    {

        return $social->driver('eveonline')
            ->scopes(setting('sso_scopes', true))
            ->redirect();
    }

    /**
     * Obtain the user information from Eve Online.
     *
     * @param \Laravel\Socialite\Contracts\Factory $social
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Seat\Services\Exceptions\SettingException
     */
    public function handleProviderCallback(Socialite $social)
    {

        $eve_data = $social->driver('eveonline')->user();

        // Avoid self attachment
        if (auth()->check() && auth()->user()->id == $eve_data->id)
            return redirect()->route('home')
                ->with('error', 'You cannot add yourself. Did you forget to change character in Eve Online SSO form ?');

        // Get or create the User bound to this login.
        $user = $this->findOrCreateUser($eve_data);

        // Update the refresh token for this character.
        $this->updateRefreshToken($eve_data, $user);

        // init character_info for this character.
        $this->updateCharacterInfo($eve_data);

        if (! $this->loginUser($user))
            return redirect()->route('auth.login')
                ->with('error', 'Login failed. Please contact your administrator.');

        return redirect()->intended();
    }

    /**
     * Check if a user exists in the database, else, create
     * and return the User object.
     *
     * Group memberships are also managed here, ensuring that
     * characters are automatically 'linked' via a group. If
     * an existing, logged in session is detected, the new login
     * will be associated with that sessions group. Otherwise,
     * a new group for this user will be created.
     *
     * @param \Laravel\Socialite\Two\User $eve_user
     *
     * @return \Seat\Web\Models\User
     */
    private function findOrCreateUser(SocialiteUser $eve_user): User
    {

        // remove all existing tokens with a different hash.
        // in case a hash has been altered, it might be because
        // that character has been sold.
        RefreshToken::where('character_id', $eve_user->id)
            ->where('character_owner_hash', '<>', $eve_user->character_owner_hash)
            ->whereNull('deleted_at')
            ->delete();

        // retrieve first account linked to a refresh token
        // for authenticating character with the exact same hash.
        $user = User::whereHas('refresh_tokens', function ($query) use ($eve_user) {
            $query->where('character_id', $eve_user->id)
                ->where('character_owner_hash', '=', $eve_user->character_owner_hash)
                ->whereNull('deleted_at');
        })->first();

        // determine if the user is actually authenticated
        // if that the case, we need to revoke found user access
        // related to authenticating eve user.
        if (auth()->check()) {
            if (! is_null($user) && auth()->user()->id !== $user->id) {
                RefreshToken::where('character_id', $eve_user->id)
                    ->where('user_id', $user->id)
                    ->delete();
            }

            $user = auth()->user();
        }

        if ($user)
            return $user;

        // Log the new account creation
        event('security.log', [
            'Creating new account for ' . $eve_user->name, 'authentication',
        ]);

        // Generate a new bucket User and use the authenticating character as main.
        $user = new User();
        $user->main_character_id = $eve_user->id;
        $user->name = $eve_user->name;
        $user->active = true;
        $user->save();

        return User::where('main_character_id', $eve_user->id)
            ->first();
    }

    /**
     * @param \Laravel\Socialite\Two\User $eve_user
     * @param \Seat\Web\Models\User $seat_user
     */
    public function updateRefreshToken(SocialiteUser $eve_user, User $seat_user): void
    {

        RefreshToken::withTrashed()->firstOrNew([
            'character_id'         => $eve_user->id,
            'character_owner_hash' => $eve_user->character_owner_hash,
        ])->fill([
            'user_id'       => $seat_user->id,
            'refresh_token' => $eve_user->refreshToken,
            'scopes'        => $eve_user->scopes,
            'token'         => $eve_user->token,
            'expires_on'    => $eve_user->expires_on,
        ])->save();

        // restore soft deleted token if any
        RefreshToken::onlyTrashed()
            ->where('character_id', $eve_user->id)
            ->where('user_id', $seat_user->id)
            ->restore();
    }

    /**
     * @param \Laravel\Socialite\Two\User $eve_user
     */
    private function updateCharacterInfo(SocialiteUser $eve_user)
    {
        $eseye = app('esi-client')->get();

        $character = $eseye->setVersion('v4')->invoke('get', '/characters/{character_id}/', [
            'character_id' => $eve_user->id,
        ]);

        CharacterInfo::firstOrNew([
            'character_id' => $eve_user->id,
        ])->fill([
            'name'            => $character->name,
            'description'     => $character->optional('description'),
            'birthday'        => $character->birthday,
            'gender'          => $character->gender,
            'race_id'         => $character->race_id,
            'bloodline_id'    => $character->bloodline_id,
            'ancestry_id'     => $character->optional('ancestry_id'),
            'security_status' => $character->optional('security_status'),
        ])->save();

        $affiliation = Arr::first($eseye->setVersion('v1')->setBody([
            $eve_user->id,
        ])->invoke('post', '/characters/affiliation'));

        CharacterAffiliation::firstOrNew([
            'character_id' => $eve_user->id,
        ])->fill([
            'corporation_id' => $affiliation->corporation_id,
            'alliance_id' => $affiliation->alliance_id ?? null,
            'faction_id' => $affiliation->faction_id ?? null,
        ])->save();

        // in case the corporation is unknown, enqueue a corporation info job
        if (! CorporationInfo::find($affiliation->corporation_id))
            Info::dispatch($affiliation->corporation_id);
    }

    /**
     * Login the user, ensuring that a group is attached.
     *
     * If no group is attached, ensure that the user at least
     * has *a* group attached to it.
     *
     * This method returns a boolean as a status flag for the
     * login routine. If a false is returned, it might mean
     * that that account is not allowed to sign in.
     *
     * @param \Seat\Web\Models\User $user
     *
     * @return bool
     */
    public function loginUser(User $user): bool
    {

        // If this account is disabled, refuse to login
        if (! $user->active) {

            event('security.log', [
                'Login for ' . $user->name . ' refused due to a disabled account', 'authentication',
            ]);

            return false;
        }

        auth()->login($user, true);

        return true;
    }
}
