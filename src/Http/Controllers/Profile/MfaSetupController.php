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

namespace Seat\Web\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Seat\Web\Validation\Mfa;

/**
 * Class MfaSetupController
 * @package Seat\Web\Http\Controllers\Profile
 */
class MfaSetupController extends Controller
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getNew()
    {

        // Get the 2fa provider
        $mfa = app('google_2fa');

        // Generate a token for the user.
        $key = $mfa->generateSecretKey();

        // Store a temp key in the session
        session()->put('mfa_key', $key);

        $qr_code_url = $mfa->getQRCodeGoogleUrl(
            'SeAT', auth()->user()->email, $key);

        return view('web::profile.mfa.new', compact('qr_code_url'));
    }

    /**
     * @param \Seat\Web\Validation\Mfa $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postSetup(Mfa $request)
    {

        // Get the 2fa provider
        $mfa = app('google_2fa');

        $valid = $mfa->verifyKey(
            session('mfa_key'), $request->confirm_code);

        if ($valid) {

            // Consider this session as one that has been
            // multifactored
            session()->put('multifactored', true);

            // Store the validated key for the user
            auth()->user()->update(['mfa_token' => session('mfa_key')]);

            return redirect()->route('profile.view')
                ->with('success', 'MFA successfully configured!');
        }

        return redirect()->back()
            ->with('error',
                'Confirmation failed. Please rescan the QR code and retry');

    }
}
