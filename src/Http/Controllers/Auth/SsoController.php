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

namespace Seat\Web\Http\Controllers\Auth;

use Laravel\Socialite\Contracts\Factory as Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Seat\Eveapi\Models\RefreshToken;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Models\Group;
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
     *
     * @return \Seat\Web\Http\Controllers\Auth\Response
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
     *
     * @return \Seat\Web\Http\Controllers\Auth\Response
     * @throws \Seat\Services\Exceptions\SettingException
     */
    public function handleProviderCallback(Socialite $social)
    {

        $eve_data = $social->driver('eveonline')->user();

        // enforce requested scopes control
        if (! empty(array_diff(setting('sso_scopes', true), explode(' ', $eve_data->scopes))))
            return redirect()->route('auth.login')
                ->with('error', 'Login failed. Requested access are not matching, please try again.');

        // Avoid self attachment
        if (auth()->check() && auth()->user()->id == $eve_data->character_id)
            return redirect()->route('home')
                ->with('error', 'You cannot add yourself. Did you forget to change character in Eve Online SSO form ?');

        // Get or create the User bound to this login.
        $user = $this->findOrCreateUser($eve_data);

        // Update the refresh token for this character.
        $this->updateRefreshToken($eve_data);

        if (! $this->loginUser($user))
            return redirect()->route('auth.login')
                ->with('error', 'Login failed. Please contact your administrator.');

        // ensure the user got a valid group - spawn it otherwise
        if (is_null($user->group)) {
            Group::forceCreate([
                'id' => $user->group_id,
            ]);

            // force laravel to update model relationship information
            $user->load('group');
        }

        // Set the main characterID based on the response.
        $this->updateMainCharacterId($user);

        return redirect()->intended();
    }

    /**
     * Check if a user exists in the database, else, create
     * and return the User object.
     *
     * Group memberships are also managed here, ensuring that
     * characters are automatically 'linked' via a group. If
     * an existsing, logged in session is detected, the new login
     * will be associated with that sessions group. Otherwise,
     * a new group for this user will be created.
     *
     * @param \Laravel\Socialite\Two\User $eve_user
     *
     * @return \Seat\Web\Models\User
     */
    private function findOrCreateUser(SocialiteUser $eve_user): User
    {

        // Check if this user already exists in the database.
        if ($existing = User::find($eve_user->character_id)) {

            // If the character_owner_hash has changed, it might be that
            // this character was transferred. We will still allow the login,
            // but the group memberships the character had will be removed.
            if ($existing->character_owner_hash !== $eve_user->character_owner_hash) {

                // Update the group_id for this user based on the current
                // session status. If there is a user already logged in,
                // simply associate the user with a new group id. If not,
                // a new group is generated and given to this user.
                $existing->group_id = auth()->check() ?
                    auth()->user()->group->id : Group::create()->id;

                // Update the new character_owner_hash
                $existing->character_owner_hash = $eve_user->character_owner_hash;
                $existing->save();
            }

            // Detect if the current session is already logged in. If
            // it is, update the group_id for the new login to the same
            // as the current session, thereby associating the characters.
            if (auth()->check()) {

                // Log the association update
                event('security.log', [
                    'Updating ' . $existing->name . ' to be part of ' . auth()->user()->name,
                    'authentication',
                ]);

                // Re-associate the group membership for the newly logged in user.
                $existing->group_id = auth()->user()->group->id;
                $existing->save();

                // Remove any orphan groups we could create during the attachment process
                Group::doesntHave('users')->delete();

            }

            return $existing;
        }

        // Log the new account creation
        event('security.log', [
            'Creating new account for ' . $eve_user->name, 'authentication',
        ]);

        // Detect if the current session is already logged in. If
        // it is, update the group_id for the new login to the same
        // as the current session, thereby associating the characters.
        if (auth()->check()) {

            // Log the association update
            event('security.log', [
                'Updating ' . $eve_user->name . ' to be part of ' . auth()->user()->name,
                'authentication',
            ]);
        }

        return User::forceCreate([  // Only because I don't want to set id as fillable
            'id'                   => $eve_user->character_id,
            'group_id'             => auth()->check() ?
                auth()->user()->group->id : Group::create()->id,
            'name'                 => $eve_user->name,
            'active'               => true,
            'character_owner_hash' => $eve_user->character_owner_hash,
        ]);
    }

    /**
     * @param \Laravel\Socialite\Two\User $eve_data
     */
    public function updateRefreshToken(SocialiteUser $eve_data): void
    {

        RefreshToken::withTrashed()->firstOrNew(['character_id' => $eve_data->character_id])
            ->fill([
                'refresh_token' => $eve_data->refresh_token,
                'scopes'        => explode(' ', $eve_data->scopes),
                'token'         => $eve_data->token,
                'expires_on'    => $eve_data->expires_on,
            ])
            ->save();

        // restore soft deleted token if any
        RefreshToken::onlyTrashed()->where('character_id', $eve_data->character_id)->restore();
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

    /**
     * Set the main character_id for a group if it is
     * not already set.
     *
     * @param \Seat\Web\Models\User $user
     *
     * @throws \Seat\Services\Exceptions\SettingException
     */
    private function updateMainCharacterId(User $user)
    {

        if (setting('main_character_id') == 0)
            setting(['main_character_id', $user->character_id]);
    }
}
