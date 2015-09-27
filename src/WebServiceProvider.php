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

namespace Seat\Web;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

/**
 * Class EveapiServiceProvider
 * @package Seat\Eveapi
 */
class WebServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Router $router)
    {

        // Include the Routes
        if (!$this->app->routesAreCached()) {
            include __DIR__ . '/Http/routes.php';
        }

        // Publish the JS & CSS, and Database migrations
        $this->publishes([
            __DIR__ . '/resources/assets'     => public_path('web'),
            __DIR__ . '/database/migrations/' => database_path('migrations'),
        ]);

        // Add the views for the 'web' namespace
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'web');

        // Include our translations
        $this->loadTranslationsFrom(__DIR__ . '/lang', 'web');

        // Add middleware
        $router->middleware('auth', 'Seat\Web\Http\Middleware\Authenticate');

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

        $this->mergeConfigFrom(
            __DIR__ . '/Config/web.config.php', 'web.config');
    }
}
