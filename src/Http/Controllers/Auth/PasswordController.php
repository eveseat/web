<?php
/*
This file is part of SeAT

Copyright (C) 2015  Leon Jacobs

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

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

/**
 * Class PasswordController
 * @package Seat\Web\Http\Controllers\Auth
 */
class PasswordController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Create a new password controller instance.
     */
    public function __construct()
    {

        $this->middleware('guest');
    }

    /**
     * Override to return the correct view
     *
     * @return \Illuminate\View\View
     */
    public function getEmail()
    {

        return view('web::auth.password');
    }

    /**
     * Override to return the correct view
     *
     * @param null $token
     *
     * @return $this
     * @throws \Seat\Web\Http\Controllers\Auth\NotFoundHttpException
     */
    public function getReset($token = null)
    {

        if (is_null($token)) {
            throw new NotFoundHttpException;
        }

        return view('web::auth.reset')->with('token', $token);
    }
}
