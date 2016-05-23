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

namespace Seat\Web\Http\Controllers\Configuration;

use App\Http\Controllers\Controller;
use Seat\Services\Settings\Seat;
use Seat\Web\Validation\SeatSettings;

/**
 * Class SeatController
 * @package Seat\Web\Http\Controllers\Configuration
 */
class SeatController extends Controller
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getView()
    {

        // Validate SSO Environment settings
        if (is_null(env('EVE_CLIENT_ID')) or
            is_null(env('EVE_CLIENT_SECRET')) or
            is_null(env('EVE_CALLBACK_URL'))
        )
            $warn_sso = true;
        else
            $warn_sso = false;

        return view('web::configuration.settings.view', compact('warn_sso'));
    }

    /**
     * @param \Seat\Web\Validation\SeatSettings $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postUpdateSettings(SeatSettings $request)
    {

        Seat::set('registration', $request->registration);
        Seat::set('admin_contact', $request->admin_contact);
        Seat::set('force_min_mask', $request->force_min_mask);
        Seat::set('min_access_mask', $request->min_access_mask);
        Seat::set('allow_sso', $request->allow_sso);

        return redirect()->back()
            ->with('success', 'SeAT settings updated!');
    }

}
