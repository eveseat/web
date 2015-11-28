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

        return view('web::configuration.settings.view');
    }

    /**
     * @param \Seat\Web\Validation\SeatSettings $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postUpdateSettings(SeatSettings $request)
    {

        Seat::set('registration', $request->registration);

        return redirect()->back()
            ->with('success', 'SeAT settings updated!');
    }

}
