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

namespace Seat\Web\Http\Controllers\Auth;

use Laravel\Socialite\Contracts\Factory as Socialite;
use Seat\Services\Settings\Profile;
use Seat\Services\Settings\Seat;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Models\User;
use Seat\Web\Notifications\EmailVerification;
use Seat\Web\Validation\EmailUpdate;

/**
 * Class SsoController
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
     */
    public function redirectToProvider(Socialite $social)
    {

        // Prevent dropping into this route is SSO
        // is disabled.
        if (Seat::get('allow_sso') !== 'yes')
            abort(404);

        return $social->driver('eveonline')->redirect();
    }

    /**
     * Obtain the user information from Eve Online.
     *
     * @param \Laravel\Socialite\Contracts\Factory $social
     *
     * @return \Seat\Web\Http\Controllers\Auth\Response
     */
    public function handleProviderCallback(Socialite $social)
    {

        $eve_data = $social->driver('eveonline')->user();
        $user = $this->findOrCreateUser($eve_data);

        // Login the account
        auth()->login($user, true);

        // Check that we have a valid email for the user.
        if (!$user->active)
            return redirect()->route('auth.eve.email');

        // Check if a main_character_id setting is set.
        // If not, we can pull the one from the SSO login data
        if (Profile::get('main_character_id') === 1)
            Profile::set('main_character_id', $eve_data->character_id);

        return redirect()->intended();
    }

    /**
     * Check if a user exists in the database, else, create
     * and return the User object
     *
     * @param $user
     *
     * @return static
     */
    public function findOrCreateUser($user)
    {

        if ($existing = User::where('eve_id', $user->eve_id)->first())
            return $existing;

        return User::create([
            'name'     => $user->name,
            'email'    => str_random(8) . '@seat.local',  // Temp Address
            'eve_id'   => $user->eve_id,
            'active'   => 0,
            'token'    => $user->token,
            'password' => bcrypt(str_random(128))   // Random Password.
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getUserEmail()
    {

        // Make sure that only logged in SSO accounts can get here.
        if (is_null(auth()->user()->eve_id)) abort(404);

        return view('web::auth.ssoemail');
    }

    /**
     * @param \Seat\Web\Validation\EmailUpdate $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postUpdateUserEmail(EmailUpdate $request)
    {

        // Make sure that only logged in SSO accounts can get here.
        if (is_null(auth()->user()->eve_id)) abort(404);

        // Update the email with the new value
        $user = auth()->user();
        $user->email = $request->input('new_email');
        $user->save();

        $user->notify(new EmailVerification());

        return redirect()->route('auth.email')
            ->with('success', 'Please check your email for the confirmation link!');

    }
}