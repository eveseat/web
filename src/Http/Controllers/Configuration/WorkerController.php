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


use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\Validation\WorkerConstraint;

/**
 * Class WorkerController
 * @package Seat\Web\Http\Controllers\Configuration
 */
class WorkerController extends Controller
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getConstraints()
    {

        $available = config('eveapi.worker_groups');
        $current = json_decode(setting('api_constraint', true), true);

        return view('web::configuration.workers.global',
            compact('available', 'current'));
    }

    /**
     * @param \Seat\Web\Http\Validation\WorkerConstraint $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postUpdateConstraints(WorkerConstraint $request)
    {

        // Build a new constraints array from the input data
        $constraints = [
            'api'         => $request->input('api'),
            'character'   => $request->input('character'),
            'corporation' => $request->input('corporation'),
            'eve'         => $request->input('eve'),
            'map'         => $request->input('map'),
            'server'      => $request->input('server'),
        ];

        // Set the new constraints to the settings.
        setting(['api_constraint', json_encode($constraints)], true);

        // Redirect back with new values.
        return redirect()->back()
            ->with('success', 'Global Constraints Updated');

    }

}
