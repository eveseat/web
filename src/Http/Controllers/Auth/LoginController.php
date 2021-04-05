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
        if (strlen(config('esi.eseye_client_secret')) < 5 || strlen(config('esi.eseye_client_id')) < 5)
            session()->flash('warning', trans('web::seat.sso_config_warning'));

        // Sign in message text
        $custom_signin_message = setting('custom_signin_message', true);
        $signin_message = sprintf('%s<div class="box-body text-center"><a href="%s"><img src="%s" alt="LOG IN with EVE Online"></a></div>',
            trans('web::seat.login_welcome'),
            route('auth.eve'),
            asset('web/img/evesso.png'));

        if(! empty($custom_signin_message)) {
            $auth_profiles = setting('sso_scopes', true);
            $signin_message = $custom_signin_message;

            // Look for patterns we can use as login buttons and swap them for the login links.
            foreach ($auth_profiles as $profile) {
                $pattern = sprintf('/[[]{2}(%s)[]]{2}/', $profile->name);

                $signin_message = preg_replace_callback($pattern, function ($matches) {
                    return sprintf('<div class="box-body text-center"><a href="%s"><img src="%s" alt="LOG IN with EVE Online"></a></div>',
                        route('auth.eve.profile', $matches[1]),
                        asset('web/img/evesso.png'));
                }, $signin_message);
            }
        }

        return view('web::auth.login', compact('signin_message'));
    }
}
