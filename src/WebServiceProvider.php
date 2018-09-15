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

namespace Seat\Web;

use Exception;
use Illuminate\Auth\Events\Attempting;
use Illuminate\Auth\Events\Login as LoginEvent;
use Illuminate\Auth\Events\Logout as LogoutEvent;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Laravel\Horizon\Horizon;
use Laravel\Socialite\SocialiteManager;
use Seat\Web\Events\Attempt;
use Seat\Web\Events\Login;
use Seat\Web\Events\Logout;
use Seat\Web\Events\SecLog;
use Seat\Web\Events\Security;
use Seat\Web\Extentions\EveOnlineProvider;
use Seat\Web\Http\Composers\CharacterMenu;
use Seat\Web\Http\Composers\CharacterSummary;
use Seat\Web\Http\Composers\CorporationMenu;
use Seat\Web\Http\Composers\CorporationSummary;
use Seat\Web\Http\Composers\Esi;
use Seat\Web\Http\Composers\Sidebar;
use Seat\Web\Http\Composers\User;
use Seat\Web\Http\Middleware\Authenticate;
use Seat\Web\Http\Middleware\Bouncer\Bouncer;
use Seat\Web\Http\Middleware\Bouncer\CharacterBouncer;
use Seat\Web\Http\Middleware\Bouncer\CorporationBouncer;
use Seat\Web\Http\Middleware\Bouncer\KeyBouncer;
use Seat\Web\Http\Middleware\Locale;
use Seat\Web\Http\Middleware\RegistrationAllowed;
use Seat\Web\Http\Middleware\Requirements;
use Validator;

/**
 * Class EveapiServiceProvider.
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

        // Configure the queue dashboard
        $this->configure_horizon();

        // Configure API
        $this->configure_api();
    }

    /**
     * Include the routes.
     */
    public function add_routes()
    {

        if (! $this->app->routesAreCached())
            include __DIR__ . '/Http/routes.php';
    }

    /**
     * Set the paths for migrations and assets that
     * should be published to the main application.
     */
    public function add_publications()
    {

        $this->publishes([
            __DIR__ . '/resources/assets'                                        => public_path('web'),
            __DIR__ . '/database/migrations/'                                    => database_path('migrations'),

            // Font Awesome Pulled from packagist
            base_path('vendor/components/font-awesome/css/font-awesome.min.css') => public_path('web/css/font-awesome.min.css'),
            base_path('vendor/components/font-awesome/fonts')                    => public_path('web/fonts'),
        ]);
    }

    /**
     * Set the path and namespace for the vies.
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
            'web::includes.header',
            'web::includes.sidebar',
        ], User::class);

        // ESI Status view composer
        $this->app['view']->composer([
            'web::includes.footer',
        ], Esi::class);

        // Sidebar menu view composer
        $this->app['view']->composer(
            'web::includes.sidebar', Sidebar::class);

        // Character info composer
        $this->app['view']->composer([
            'web::character.includes.summary',
            'web::character.includes.menu',
            'web::character.intel.includes.menu',
            'web::character.wallet.includes.menu',
        ], CharacterSummary::class);

        // Character menu composer
        $this->app['view']->composer([
            'web::character.includes.menu',
        ], CharacterMenu::class);

        // Corporation info composer
        $this->app['view']->composer([
            'web::corporation.includes.summary',
            'web::corporation.includes.menu',
            'web::corporation.security.includes.menu',
            'web::corporation.ledger.includes.menu',
            'web::corporation.wallet.includes.menu',
        ], CorporationSummary::class);

        // Corporation menu composer
        $this->app['view']->composer([
            'web::corporation.includes.menu',
        ], CorporationMenu::class);

    }

    /**
     * Include the translations and set the namespace.
     */
    public function add_translations()
    {

        $this->loadTranslationsFrom(__DIR__ . '/lang', 'web');
    }

    /**
     * Include the middleware needed.
     *
     * @param $router
     */
    public function add_middleware($router)
    {

        // Authenticate checks that the session is
        // simply authenticated
        $router->aliasMiddleware('auth', Authenticate::class);

        // Ensure that all of the SeAT required modules is installed.
        $router->aliasMiddleware('requirements', Requirements::class);

        // Localization support
        $router->aliasMiddleware('locale', Locale::class);

        // Registration Middleware checks of the app is
        // allowing new user registration to occur.
        $router->aliasMiddleware('registration.status', RegistrationAllowed::class);

        // The Bouncer is responsible for checking hes
        // AccessChecker and ensuring that every request
        // that comes in is authorized
        $router->aliasMiddleware('bouncer', Bouncer::class);
        $router->aliasMiddleware('characterbouncer', CharacterBouncer::class);
        $router->aliasMiddleware('corporationbouncer', CorporationBouncer::class);
        $router->aliasMiddleware('keybouncer', KeyBouncer::class);

    }

    /**
     * Register the custom events that may fire for
     * this package.
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
     * Add custom validators that are not part of Laravel core.
     */
    public function add_custom_validators()
    {

        Validator::extend('cron', 'Seat\Web\Http\Validation\Custom\Cron@validate');
    }

    /**
     * Configure Horizon.
     *
     * This includes the access rules for the dashboard, as
     * well as the number of workers to use for the job processor.
     */
    public function configure_horizon()
    {

        // Require the queue_manager role to view the dashboard
        Horizon::auth(function ($request) {

            return $request->user()->has('queue_manager', false);
        });

        // During autoload-dumping and other cases, it may happen
        // that the MySQL database is not yet ready. In that case,
        // we need to catch the exception the call to `setting()`
        // will cause.

        try {

            $worker_count = setting('queue_workers', true);

        } catch (Exception $e) {

            $worker_count = 3;
        }

        // Configure the workers for SeAT.
        $horizon_environments = [
            'local' => [
                'seat-workers' => [
                    'connection' => 'redis',
                    'queue'      => ['high', 'medium', 'low', 'default'],
                    'balance'    => false,
                    'processes'  => $worker_count,
                    'tries'      => 1,
                    'timeout'    => 900, // 15 minutes
                ],
            ],
        ];

        // Set the environment configuration.
        config(['horizon.environments' => $horizon_environments]);
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

        // Menu Configurations
        $this->mergeConfigFrom(
            __DIR__ . '/Config/package.sidebar.php', 'package.sidebar');
        $this->mergeConfigFrom(
            __DIR__ . '/Config/package.character.menu.php', 'package.character.menu');
        $this->mergeConfigFrom(
            __DIR__ . '/Config/package.corporation.menu.php', 'package.corporation.menu');

        // Helper configurations
        $this->mergeConfigFrom(__DIR__ . '/Config/web.jobnames.php', 'web.jobnames');

        // Register any extra services.
        $this->register_services();

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
                (int) config('web.supervisor.rpc.port')
            );
        });

    }

    /**
     * Update Laravel 5 Swagger annotation path.
     */
    private function configure_api()
    {

        // ensure current annotations setting is an array of path or transform into it
        $current_annotations = config('l5-swagger.paths.annotations');
        if (! is_array($current_annotations))
            $current_annotations = [$current_annotations];

        // merge paths together and update config
        config([
            'l5-swagger.paths.annotations' => array_unique(array_merge($current_annotations, [
                __DIR__ . '/Models',
            ])),
        ]);
    }
}
