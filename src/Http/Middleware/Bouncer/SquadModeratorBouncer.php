<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015, 2016, 2017, 2018, 2019  Leon Jacobs
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

namespace Seat\Web\Http\Middleware\Bouncer;

use Closure;
use Illuminate\Http\Request;
use Seat\Web\Models\Squads\Squad;
use Seat\Web\Models\Squads\SquadApplication;

/**
 * Class SquadModeratorBouncer.
 *
 * @package Seat\Web\Http\Middleware\Bouncer
 */
class SquadModeratorBouncer
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $application_routes = ['squads.applications.show', 'squads.applications.approve', 'squads.applications.reject'];
        $member_routes = ['squads.members.kick'];

        if (in_array($request->route()->getName(), $application_routes)) {
            $application = SquadApplication::find($request->route('id'));

            if (! is_null($application) && $application->squad->is_moderator)
                return $next($request);
        }

        if (in_array($request->route()->getName(), $member_routes)) {
            $squad = Squad::find($request->route('id'));

            if (! is_null($squad) && $squad->is_moderator)
                return $next($request);
        }

        if (auth()->user()->hasSuperUser())
            return $next($request);

        // Redirect away from the original request
        return redirect()->route('auth.unauthorized');
    }
}