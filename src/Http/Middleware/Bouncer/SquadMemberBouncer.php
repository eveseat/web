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

/**
 * Class SquadMemberBouncer.
 *
 * @package Seat\Web\Http\Middleware\Bouncer
 */
class SquadMemberBouncer
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $squad = Squad::find($request->route('id'));

        if (! is_null($squad) && ($squad->is_moderator || $squad->is_member))
            return $next($request);

        if (auth()->user()->hasSuperUser())
            return $next($request);

        $message = sprintf('Request to %s was denied by the bouncer. Authenticated user %s is not member or moderator of squad %s.',
            $request->path(), auth()->user()->name, $squad->name);

        event('security.log', [$message, 'squads']);

        // Redirect away from the original request
        return redirect()->route('auth.unauthorized');
    }
}
