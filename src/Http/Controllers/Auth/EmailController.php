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

use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Models\User;

/**
 * Class EmailController.
 * @package Seat\Web\Http\Controllers\Auth
 */
class EmailController extends Controller
{
    /**
     * @param $token
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirmEmail($token)
    {

        $user = User::whereActivationToken($token)->firstOrFail();
        $user->confirmEmail();

        auth()->login($user);

        return redirect()->intended()
            ->with('success', 'Email verified!');

    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getEmailRequired()
    {

        return view('web::auth.email');
    }
}
