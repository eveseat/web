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

// Namespace all of the routes for this package.
Route::group(['namespace' => 'Seat\Web\Http\Controllers'], function () {

    // Authentication & Registration Routes.
    Route::group(['namespace' => 'Auth'], function () {

        Route::group(['prefix' => 'auth'], function () {

            include __DIR__ . '/Routes/Auth/Auth.php';
        });

        Route::group(['prefix' => 'password'], function () {

            include __DIR__ . '/Routes/Auth/Password.php';
        });
    });

    // All routes from here require *at least* that the
    // user is authenticated.
    Route::group(['middleware' => 'auth'], function () {

        // The home route does not need any prefixes
        // and or namespacing modifications, so we will
        // just include it
        include __DIR__ . '/Routes/Home.php';

        // Configuration Routes
        Route::group(['namespace' => 'Configuration', 'prefix' => 'configuration'], function () {

            // User Management
            Route::group(['prefix' => 'users'], function () {

                include __DIR__ . '/Routes/Configuration/User.php';
            });

            // Access Mangement
            Route::group(['prefix' => 'access'], function () {

                include __DIR__ . '/Routes/Configuration/Access.php';
            });

        });

    });

});
