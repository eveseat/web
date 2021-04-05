<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2021 Leon Jacobs
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

namespace Seat\Web\Http\Middleware;

use Closure;

/**
 * Class Requirements.
 * @package Seat\Web\Http\Middleware
 */
class Requirements
{
    /**
     * An array of extensions *required* by SeAT.
     *
     * @var array
     */
    public static $extensions = [
        'intl', 'gd', 'PDO', 'curl', 'mbstring', 'dom', 'bz2', 'redis',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        // Get the status of all of the extensions
        $requirements = collect(array_map(function ($extension) {

            return [
                'name'   => $extension,
                'loaded' => extension_loaded($extension),
            ];

        }, self::$extensions))->sortBy('name');

        // If there is an extension not loaded (aka false), then
        // render a view with the information in it.
        if (! $requirements->filter(function ($item) {

            return $item['loaded'] === false;

        })->isEmpty())
            return response()->view('web::requirements.extensions', compact('requirements'));

        // Check the PHP Version.
        if (! version_compare(phpversion(), '7.2.0', '>='))
            return response()->view('web::requirements.phpversion');

        // Everything ok \o/
        return $next($request);
    }
}
