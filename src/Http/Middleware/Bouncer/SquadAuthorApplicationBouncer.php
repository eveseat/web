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
use Seat\Web\Models\Squads\SquadApplication;

/**
 * Class SquadAuthorApplicationBouncer.
 *
 * @package Seat\Web\Http\Middleware\Bouncer
 */
class SquadAuthorApplicationBouncer
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Retrieve related Squad Application
        $application = SquadApplication::find($request->route('id'));

        // In case the author of that application is the currently authenticated user, grant access
        if (! is_null($application) && $application->user_id == auth()->user()->id)
            return $next($request);

        $message = sprintf('Request to %s was denied by the bouncer. Authenticated user %s is not application owner.',
            $request->path(), auth()->user()->name);

        event('security.log', [$message, 'squads']);

        // Redirect away from the original request
        return redirect()->route('auth.unauthorized');
    }
}
