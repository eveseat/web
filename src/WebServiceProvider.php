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
use Seat\Web\Events\Attempt;
use Seat\Web\Events\Auth;
use Seat\Web\Events\Login;
use Seat\Web\Events\Logout;
use Seat\Web\Events\SecLog;
use Seat\Web\Events\Security;
use Seat\Web\Http\Composers\CharacterSummary;
use Seat\Web\Http\Composers\Sidebar;
use Seat\Web\Http\Composers\User;
use Seat\Web\Http\Middleware\Authenticate;
use Seat\Web\Http\Middleware\Bouncer;
use Seat\Web\Http\Middleware\CharacterBouncer;
use Seat\Web\Http\Middleware\CorporationBouncer;
use Seat\Web\Http\Middleware\KeyBouncer;

/**
 * Class EveapiServiceProvider
 * @package Seat\Eveapi
 */
class WebServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @param \Illuminate\Routing\Router $router
     */
    public function boot(Router $router)
    {

        // Include the Routes
        $this->add_routes();

        // Publish the JS & CSS, and Database migrations
        $this->add_publications();

        // Add the views for the 'web' namespace
        $this->add_views();

        // Add the view composers
        $this->add_view_composers();

        // Include our translations
        $this->add_translations();

        // Add middleware
        $this->add_middleware($router);

        // Add event listeners
        $this->add_events();

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

        // Merge the config with anything in the main app
        $this->mergeConfigFrom(
            __DIR__ . '/Config/web.config.php', 'web.config');
        $this->mergeConfigFrom(
            __DIR__ . '/Config/web.permissions.php', 'web.permissions');
    }

    /**
     * Include the routes
     */
    public function add_routes()
    {

        if (!$this->app->routesAreCached()) {
            include __DIR__ . '/Http/routes.php';
        }
    }

    /**
     * Set the paths for migrations and assets that
     * should be published to the main application
     */
    public function add_publications()
    {

        $this->publishes([
            __DIR__ . '/resources/assets'     => public_path('web'),
            __DIR__ . '/database/migrations/' => database_path('migrations'),
        ]);
    }

    /**
     * Set the path and namespace for the vies
     */
    public function add_views()
    {

        $this->loadViewsFrom(__DIR__ . '/resources/views', 'web');
    }

    /**
     * Add the view composers. This allows us
     * to make data available in views without
     * repeating any of the code.
     */
    public function add_view_composers()
    {

        // User information view composer
        $this->app['view']->composer([
            'web::includes.sidebar',
            'web::includes.header'
        ], User::class);

        // Sidebar menu view composer
        $this->app['view']->composer(
            'web::includes.sidebar', Sidebar::class);

        // Character info composser
        $this->app['view']->composer(
            'web::character.includes.summary', CharacterSummary::class);

    }

    /**
     * Include the translations and set the namespace
     */
    public function add_translations()
    {

        $this->loadTranslationsFrom(__DIR__ . '/lang', 'web');
    }

    /**
     * Include the middleware needed
     *
     * @param $router
     */
    public function add_middleware($router)
    {

        // Authenticate checks that the session is
        // simply authenticated
        $router->middleware('auth', Authenticate::class);

        // The Bouncer is responsible for checking hes
        // Clipboard and ensuring that every request
        // that comes in is authorized
        $router->middleware('bouncer', Bouncer::class);
        $router->middleware('characterbouncer', CharacterBouncer::class);
        $router->middleware('corporationBouncer', CorporationBouncer::class);
        $router->middleware('keybouncer', KeyBouncer::class);

    }

    /**
     * Register the custom events that may fire for
     * this package
     */
    public function add_events()
    {

        $this->app->events->listen('auth.login', Login::class);
        $this->app->events->listen('auth.logout', Logout::class);
        $this->app->events->listen('auth.attempt', Attempt::class);

        $this->app->events->listen('security.log', SecLog::class);
    }
}
