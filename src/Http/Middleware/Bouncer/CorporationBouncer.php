<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2020 Leon Jacobs
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

/**
 * Class CorporationBouncer.
 * @package Seat\Web\Http\Middleware
 */
class CorporationBouncer
{
    /**
     * Handle an incoming request.
     *
     * This filter checks if a specific permission exists as
     * well as ensures that an affiliation to a corporation
     * exists.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param null                     $permission
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $permission = null)
    {

        // Check on the clipboard if this permission
        // should be granted.
        if (auth()->user()->has('corporation.' . $permission))
            return $next($request);

        $message = 'Request to ' . $request->path() . ' was ' .
            'denied by the corporationbouncer. The permission required is ' .
            'corporation.' . $permission . '.';

        event('security.log', [$message, 'authorization']);

        // Redirect away from the original request
        return redirect()->route('auth.unauthorized');

    }
}
