<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015, 2016, 2017, 2018  Leon Jacobs
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
     * An array of extentions *required* by SeAT.
     *
     * @var array
     */
    public static $extentions = [
        'mcrypt', 'intl', 'gd', 'PDO', 'curl', 'mbstring', 'dom', 'bz2',
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

        // Get the status of all of the extentions
        $requirements = collect(array_map(function ($extention) {

            return [
                'name'   => $extention,
                'loaded' => extension_loaded($extention),
            ];

        }, self::$extentions))->sortBy('name');

        // If there is an extention not loaded (aka false), then
        // render a view with the information in it.
        if (! $requirements->filter(function ($item) {

            return $item['loaded'] === false;

        })->isEmpty()
        )
            return response()->view('web::requirements.extentions', compact('requirements'));

        // Check the PHP Version.
        if (! version_compare(phpversion(), '5.5.14', '>='))
            return view('web::requirements.phpversion');

        // Everyting ok \o/
        return $next($request);
    }
}
