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
use App\Http\Requests;
use Seat\Web\Validation\Mfa;

/**
 * Class MfaController
 * @package Seat\Web\Http\Controllers\Auth
 */
class MfaController extends Controller
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getPrompt()
    {

        return view('web::auth.mfa');
    }

    /**
     * @param \Seat\Web\Validation\Mfa $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postVerify(Mfa $request)
    {

        // Get the 2fa provider
        $mfa = app('google_2fa');

        // Verify the token
        $valid = $mfa->verifyKey(
            auth()->user()->mfa_token, $request->confirm_code);

        if ($valid) {

            // Consider this session as one that has been
            // multifactored
            session()->put('multifactored', true);

            return redirect()->intended('home')
                ->with('success', 'Welcome back!');
        }

        return redirect()->back()
            ->with('error', 'Confirmation failed. Please retry');
    }
}
