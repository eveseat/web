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

namespace Seat\Web;

use Illuminate\Auth\Events\Attempting;
use Illuminate\Auth\Events\Login as LoginEvent;
use Illuminate\Auth\Events\Logout as LogoutEvent;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\SocialiteManager;
use PragmaRX\Google2FA\Google2FA;
use Seat\Web\Events\Attempt;
use Seat\Web\Events\Auth;
use Seat\Web\Events\Login;
use Seat\Web\Events\Logout;
use Seat\Web\Events\SecLog;
use Seat\Web\Events\Security;
use Seat\Web\Extentions\EveOnlineProvider;
use Seat\Web\Http\Composers\CharacterMenu;
use Seat\Web\Http\Composers\CharacterSummary;
use Seat\Web\Http\Composers\CorporationMenu;
use Seat\Web\Http\Composers\CorporationSummary;
use Seat\Web\Http\Composers\Sidebar;
use Seat\Web\Http\Composers\User;
use Seat\Web\Http\Middleware\Authenticate;
use Seat\Web\Http\Middleware\Bouncer\Bouncer;
use Seat\Web\Http\Middleware\Bouncer\CharacterBouncer;
use Seat\Web\Http\Middleware\Bouncer\CorporationBouncer;
use Seat\Web\Http\Middleware\Bouncer\KeyBouncer;
use Seat\Web\Http\Middleware\ConfirmedEmailAddress;
use Seat\Web\Http\Middleware\Locale;
use Seat\Web\Http\Middleware\Mfa;
use Seat\Web\Http\Middleware\RegistrationAllowed;
use Seat\Web\Http\Middleware\Requirements;
use Supervisor\Supervisor;
use Validator;

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

        // Add Validators
        $this->add_custom_validators();

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

        // Merge the config with anything in the main app
        // Web package configurations
        $this->mergeConfigFrom(
            __DIR__ . '/Config/web.config.php', 'web.config');
        $this->mergeConfigFrom(
            __DIR__ . '/Config/web.permissions.php', 'web.permissions');
        $this->mergeConfigFrom(
            __DIR__ . '/Config/web.locale.php', 'web.locale');
        $this->mergeConfigFrom(
            __DIR__ . '/Config/web.supervisor.php', 'web.supervisor');

        // Menu Configurations
        $this->mergeConfigFrom(
            __DIR__ . '/Config/package.sidebar.php', 'package.sidebar');
        $this->mergeConfigFrom(
            __DIR__ . '/Config/package.character.menu.php', 'package.character.menu');
        $this->mergeConfigFrom(
            __DIR__ . '/Config/package.corporation.menu.php', 'package.corporation.menu');

        // Register any extra services.
        $this->register_services();

    }

    /**
     * Include the routes
     */
    public function add_routes()
    {

        if (!$this->app->routesAreCached())
            include __DIR__ . '/Http/routes.php';
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
            'web::includes.header'
        ], User::class);

        // Sidebar menu view composer
        $this->app['view']->composer(
            'web::includes.sidebar', Sidebar::class);

        // Character info composer
        $this->app['view']->composer([
            'web::character.includes.summary',
            'web::character.includes.menu',
            'web::character.intel.includes.menu',
            'web::character.journal.includes.menu'
        ], CharacterSummary::class);

        // Character menu composer
        $this->app['view']->composer([
            'web::character.includes.menu'
        ], CharacterMenu::class);

        // Corporation info composer
        $this->app['view']->composer([
            'web::corporation.includes.summary',
            'web::corporation.includes.menu',
            'web::corporation.security.includes.menu',
            'web::corporation.ledger.includes.menu'
        ], CorporationSummary::class);

        // Corporation menu composer
        $this->app['view']->composer([
            'web::corporation.includes.menu'
        ], CorporationMenu::class);

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

        // Email Verification Requirement
        $router->middleware('auth.email', ConfirmedEmailAddress::class);

        // Ensure that all of the SeAT required modules is installed.
        $router->middleware('requirements', Requirements::class);

        // Localization support
        $router->middleware('locale', Locale::class);

        // Optional multifactor authentication if required
        $router->middleware('mfa', Mfa::class);

        // Registration Middleware checks of the app is
        // allowing new user registration to occur.
        $router->middleware('registration.status', RegistrationAllowed::class);

        // The Bouncer is responsible for checking hes
        // AccessChecker and ensuring that every request
        // that comes in is authorized
        $router->middleware('bouncer', Bouncer::class);
        $router->middleware('characterbouncer', CharacterBouncer::class);
        $router->middleware('corporationbouncer', CorporationBouncer::class);
        $router->middleware('keybouncer', KeyBouncer::class);

    }

    /**
     * Register the custom events that may fire for
     * this package
     */
    public function add_events()
    {

        // Internal Authentication Events
        $this->app->events->listen(LoginEvent::class, Login::class);
        $this->app->events->listen(LogoutEvent::class, Logout::class);
        $this->app->events->listen(Attempting::class, Attempt::class);

        // Custom Events
        $this->app->events->listen('security.log', SecLog::class);
    }

    /**
     * Add custom validators that are not part of Laravel core
     */
    public function add_custom_validators()
    {

        Validator::extend('cron', 'Seat\Web\Validation\Custom\Cron@validate');
    }

    /**
     * Register external services used in this package.
     *
     * Currently this consists of:
     *  - PragmaRX\Google2FA
     *  - Laravel\Socialite
     *  - Yajra\Datatables
     */
    public function register_services()
    {

        // Register the Google2FA into the IoC
        $this->app->bind('google_2fa', function () {

            return new Google2FA;
        });

        // Register the Socialite Factory.
        // From: Laravel\Socialite\SocialiteServiceProvider
        $this->app->singleton('Laravel\Socialite\Contracts\Factory', function ($app) {

            return new SocialiteManager($app);
        });

        // Slap in the Eveonline Socialite Provider
        $eveonline = $this->app->make('Laravel\Socialite\Contracts\Factory');
        $eveonline->extend('eveonline',
            function ($app) use ($eveonline) {

                $config = $app['config']['services.eveonline'];

                return $eveonline->buildProvider(EveOnlineProvider::class, $config);
            }
        );

        // Register the datatables package! Thanks
        //  https://laracasts.com/discuss/channels/laravel/register-service-provider-and-facade-within-service-provider
        $this->app->register('Yajra\Datatables\DatatablesServiceProvider');
        $loader = AliasLoader::getInstance();
        $loader->alias('Datatables', 'Yajra\Datatables\Facades\Datatables');

        // Register the Supervisor RPC helper into the IoC
        $this->app->singleton('supervisor', function () {

            return new Supervisor(
                config('web.supervisor.name'),
                config('web.supervisor.rpc.address'),
                config('web.supervisor.rpc.username'),
                config('web.supervisor.rpc.password'),
                config('web.supervisor.rpc.port')
            );
        });

    }
}
