<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2021 Leon Jacobs
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

use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Models\User;

/**
 * Class AdminLoginController.
 *
 * @package Seat\Web\Http\Controllers\Auth
 */
class AdminLoginController extends Controller
{
    /**
     * Login using the cached admin user token.
     *
     * @param  string  $token
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function checkLoginToken(string $token)
    {

        if ($token != cache('admin_login_token'))
            abort(404);

        $user = User::where('name', 'admin')->first();

        if (is_null($user))
            return redirect()->route('auth.login')
                ->withErrors('The Admin user does not exist. Re-run the seat:admin:login command.');

        // Login and clear the token we just used.
        auth()->login($user);
        cache()->delete('admin_login_token');

        return redirect()->intended('home');

    }
}
