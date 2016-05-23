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

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Seat\Web\Models\User;

/**
 * Class AuthController
 * @package Seat\Web\Http\Controllers\Auth
 */
class AuthController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * @var string
     */
    protected $redirectAfterLogout = '/home';

    /**
     * Set the login username to name instead of the
     * default which is email
     *
     * @var string
     */
    protected $username = 'name';

    /**
     * Create a new authentication controller instance.
     */
    public function __construct()
    {

        $this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {

        return Validator::make($data, [
            'name'     => 'required|max:255|unique:users',
            'email'    => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     *
     * @return User
     */
    protected function create(array $data)
    {

        return User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    /**
     * Override to return the correct view
     *
     * @return \Illuminate\View\View
     */
    public function getLogin()
    {

        return view('web::auth.login');
    }

    /**
     * Override to return the correct view
     *
     * @return \Illuminate\View\View
     */
    public function getRegister()
    {

        return view('web::auth.register');

    }

    /**
     * Override to return the correct Language
     *
     * @return mixed
     */
    protected function getFailedLoginMessage()
    {

        return Lang::get('web::auth.failed');
    }

    /**
     * Log the user out of the application.
     * We override this method to add the session
     * flushing.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogout()
    {

        Auth::logout();

        // Flush all of the session data
        session()->flush();

        return redirect('/');
    }

}
