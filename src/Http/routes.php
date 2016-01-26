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

// Namespace all of the routes for this package.
Route::group(['namespace' => 'Seat\Web\Http\Controllers'], function () {

    // Authentication & Registration Routes.
    Route::group([
        'namespace'  => 'Auth',
        'middleware' => 'requirements'
    ], function () {

        Route::group(['prefix' => 'auth'], function () {

            include __DIR__ . '/Routes/Auth/Auth.php';
        });

        Route::group(['prefix' => 'password'], function () {

            include __DIR__ . '/Routes/Auth/Password.php';
        });
    });

    // All routes from here require *at least* that the
    // user is authenticated. The mfa middleware checks
    // a setting for the user. We also run the localization
    // related logic here for translation support
    Route::group(['middleware' => ['auth', 'locale']], function () {

        Route::group(['namespace' => 'Auth', 'prefix' => 'auth'], function () {

            include __DIR__ . '/Routes/Auth/Mfa.php';
        });

        // Routes from here on may optionally have a multifactor
        // authentication requirement
        Route::group(['middleware' => 'mfa'], function () {

            // The home route does not need any prefixes
            // and or namespacing modifications, so we will
            // just include it
            include __DIR__ . '/Routes/Home.php';

            // Support Routes
            Route::group([
                'namespace' => 'Support',
                'prefix'    => 'support',
            ], function () {

                include __DIR__ . '/Routes/Support/List.php';
                include __DIR__ . '/Routes/Support/Resolve.php';
            });

            // User Profile Routes
            Route::group([
                'namespace' => 'Profile',
                'prefix'    => 'profile'
            ], function () {

                // Preferences
                Route::group(['prefix' => 'settings'], function () {

                    include __DIR__ . '/Routes/Profile/View.php';
                });

                // Mfa configuration
                Route::group(['prefix' => 'mfa'], function () {

                    include __DIR__ . '/Routes/Profile/Mfa.php';
                });

            });

            // Queue Jobs
            Route::group([
                'namespace' => 'Queue',
                'prefix'    => 'queue',
            ], function () {

                include __DIR__ . '/Routes/Queue/Status.php';
            });

            // Api Key Routes
            Route::group([
                'namespace' => 'Api',
                'prefix'    => 'api-key'
            ], function () {

                include __DIR__ . '/Routes/Api/Key.php';

                // People Group Routes
                Route::group([
                    'prefix' => 'people'
                ], function () {

                    include __DIR__ . '/Routes/Api/People.php';
                });
            });

            // Corporation Routes
            Route::group([
                'namespace' => 'Corporation',
                'prefix'    => 'corporation'
            ], function () {

                include __DIR__ . '/Routes/Corporation/View.php';
            });

            // Character Routes
            Route::group([
                'namespace' => 'Character',
                'prefix'    => 'character'
            ], function () {

                include __DIR__ . '/Routes/Character/View.php';
            });

            // Configuration Routes. In the context of seat,
            // all configuration should only be possible if
            // a user has the 'superuser' role.
            Route::group([
                'namespace'  => 'Configuration',
                'prefix'     => 'configuration',
                'middleware' => 'bouncer:superuser'
            ], function () {

                // User Management
                Route::group(['prefix' => 'users'], function () {

                    include __DIR__ . '/Routes/Configuration/User.php';
                });

                // Access Mangement
                Route::group(['prefix' => 'access'], function () {

                    include __DIR__ . '/Routes/Configuration/Access.php';
                });

                // Security
                Route::group(['prefix' => 'security'], function () {

                    include __DIR__ . '/Routes/Configuration/Security.php';
                });

                // Schedule
                Route::group(['prefix' => 'schedule'], function () {

                    include __DIR__ . '/Routes/Configuration/Schedule.php';
                });

                // Import
                Route::group(['prefix' => 'import'], function () {

                    include __DIR__ . '/Routes/Configuration/Import.php';
                });

                // SeAT Settings
                Route::group(['prefix' => 'settings'], function () {

                    include __DIR__ . '/Routes/Configuration/Seat.php';
                });

            });
        });

    });

});
