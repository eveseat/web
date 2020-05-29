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

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Seat\Web\Http\Controllers\Controller;

/**
 * Class LoginController.
 * @package Seat\Web\Http\Controllers\Auth
 */
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * LoginController constructor.
     */
    public function __construct()
    {

        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * @return string
     */
    public function username()
    {

        return 'name';
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLoginForm()
    {

        // Warn if SSO has not been configured yet.
        if (strlen(config('eveapi.config.eseye_client_secret')) < 5 || strlen(config('eveapi.config.eseye_client_id')) < 5)
            session()->flash('warning', trans('web::seat.sso_config_warning'));

        // Sign in message text
        $custom_signin_message = setting('custom_signin_message', true);
        $signin_message = trans('web::seat.login_welcome') . '<div class="box-body text-center"><a href="' . route('auth.eve') . '"><img src="' . asset('web/img/evesso.png') . '" alt="LOG IN with EVE Online"></a></div>';
        if($custom_signin_message != '') {
            // Look for patterns we can use as login buttons and swap them for the login links.
            $pattern = '/([[]{2})([a-zA-Z0-9-_]+)([]]{2})/';
            $signin_message = preg_replace_callback($pattern, function ($matches) {
                return '<div class="box-body text-center"><a href="' . route('auth.eve.profile', $matches[2]) . '"><img src="' . asset('web/img/evesso.png') . '" alt="LOG IN with EVE Online"></a></div>';
            }, $custom_signin_message);
        }

        return view('web::auth.login', compact('signin_message'));
    }
}
