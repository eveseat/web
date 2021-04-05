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

// Namespace all of the routes for this package.
Route::group([
    'namespace'  => 'Seat\Web\Http\Controllers',
    'middleware' => 'web',   // Web middleware for state etc since L5.3
], function () {

    // Authentication & Registration Routes.
    Route::group([
        'namespace'  => 'Auth',
        'middleware' => 'requirements',
    ], function () {

        // Since Laravel 5.3, its recommended to use Auth::routes(),
        // for these. We use named routes though, so that does not
        // *really* work for us here.
        Route::group(['prefix' => 'auth'], function () {

            include __DIR__ . '/Routes/Auth/Auth.php';
            include __DIR__ . '/Routes/Auth/Sso.php';
        });

    });

    // All routes from here require *at least* that the
    // user is authenticated. We also run the localization
    // related logic here for translation support.
    Route::group(['middleware' => ['auth', 'locale']], function () {

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
            include __DIR__ . '/Routes/Support/Search.php';
            include __DIR__ . '/Routes/Support/Insurance.php';
        });

        // User Profile Routes
        Route::group([
            'namespace' => 'Profile',
            'prefix'    => 'profile',
        ], function () {

            // Preferences
            Route::group(['prefix' => 'settings'], function () {

                include __DIR__ . '/Routes/Profile/View.php';
            });

        });

        // Queue Jobs
        Route::group([
            'namespace' => 'Queue',
            'prefix'    => 'queue',
        ], function () {

            include __DIR__ . '/Routes/Queue/Status.php';
        });

        // Corporation Routes
        Route::group([
            'namespace' => 'Corporation',
            'prefix'    => 'corporations',
        ], function () {

            include __DIR__ . '/Routes/Corporation/View.php';
        });

        // Character Routes
        Route::group([
            'namespace' => 'Character',
            'prefix'    => 'characters',
        ], function () {

            include __DIR__ . '/Routes/Character/View.php';
        });

        // Squads Routes
        Route::group([
            'namespace' => 'Squads',
            'prefix'    => 'squads',
        ], function () {
            include __DIR__ . '/Routes/Squads/Routes.php';
        });

        // Configuration Routes. In the context of seat,
        // all configuration should only be possible if
        // a user has the 'superuser' role.
        Route::group([
            'namespace'  => 'Configuration',
            'prefix'     => 'configuration',
            'middleware' => 'can:global.superuser',
        ], function () {

            // User Management
            Route::group(['prefix' => 'users'], function () {

                include __DIR__ . '/Routes/Configuration/User.php';
            });

            // Access Management
            Route::group(['prefix' => 'access'], function () {

                include __DIR__ . '/Routes/Configuration/Access.php';
            });

            // Security
            Route::group(['prefix' => 'security'], function () {

                include __DIR__ . '/Routes/Configuration/Security.php';
            });

            // Sso
            Route::group(['prefix' => 'sso'], function () {

                include __DIR__ . '/Routes/Configuration/Sso.php';
            });

            // Schedule
            Route::group(['prefix' => 'schedule'], function () {

                include __DIR__ . '/Routes/Configuration/Schedule.php';
            });

            // SeAT Settings
            Route::group(['prefix' => 'settings'], function () {

                include __DIR__ . '/Routes/Configuration/Seat.php';
            });

        });

        // Impersonation Helper Group. This one is Separate purely
        // because we don't want to restrict this to superusers only.
        // For obvious reasons I hope...
        Route::group([
            'namespace' => 'Configuration',
            'prefix'    => 'configuration',
        ], function () {

            include __DIR__ . '/Routes/Configuration/Impersonation.php';
        });

        // Tools Routes
        Route::group([
            'namespace' => 'Tools',
            'prefix'    => 'tools',
        ], function () {

            include __DIR__ . '/Routes/Tools/Job.php';
            include __DIR__ . '/Routes/Tools/Standings.php';
            include __DIR__ . '/Routes/Tools/Notes.php';
            include __DIR__ . '/Routes/Tools/Moons.php';
        });

    });

    Route::group([
        'namespace'  => 'Support',
        'middleware' => ['auth'],
    ], function () {

        Route::group(['prefix' => 'lookup'], function () {

            include __DIR__ . '/Routes/Support/FastLookup.php';
        });
    });

});
