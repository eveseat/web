<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015, 2016, 2017, 2018, 2019  Leon Jacobs
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

use App\Providers\AbstractSeatPlugin;
use Illuminate\Auth\Events\Attempting;
use Illuminate\Auth\Events\Login as LoginEvent;
use Illuminate\Auth\Events\Logout as LogoutEvent;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Validator;
use Laravel\Horizon\Horizon;
use Seat\Eveapi\Models\Assets\CharacterAsset;
use Seat\Eveapi\Models\Character\CharacterAffiliation;
use Seat\Eveapi\Models\Character\CharacterSkill;
use Seat\Web\Events\Attempt;
use Seat\Web\Events\Login;
use Seat\Web\Events\Logout;
use Seat\Web\Events\SecLog;
use Seat\Web\Http\Composers\CharacterLayout;
use Seat\Web\Http\Composers\CharacterMenu;
use Seat\Web\Http\Composers\CharacterSummary;
use Seat\Web\Http\Composers\CorporationLayout;
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
use Seat\Web\Http\Middleware\Bouncer\SquadAuthorApplicationBouncer;
use Seat\Web\Http\Middleware\Bouncer\SquadMemberBouncer;
use Seat\Web\Http\Middleware\Bouncer\SquadModeratorBouncer;
use Seat\Web\Http\Middleware\Locale;
use Seat\Web\Http\Middleware\RegistrationAllowed;
use Seat\Web\Http\Middleware\Requirements;
use Seat\Web\Models\Squads\SquadMember;
use Seat\Web\Models\Squads\SquadRole;
use Seat\Web\Observers\CharacterAffiliationObserver;
use Seat\Web\Observers\CharacterAssetObserver;
use Seat\Web\Observers\CharacterSkillObserver;
use Seat\Web\Observers\SquadMemberObserver;
use Seat\Web\Observers\SquadRoleObserver;

/**
 * Class WebServiceProvider.
 * @package Seat\Web
 */
class WebServiceProvider extends AbstractSeatPlugin
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

        // Inform Laravel how to load migrations
        $this->add_migrations();

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
        $this->loadRoutesFrom(__DIR__ . '/Http/routes.php');
    }

    /**
     * Set the paths for migrations and assets that
     * should be published to the main application.
     */
    public function add_publications()
    {

        $this->publishes([
            __DIR__ . '/resources/css'                                           => public_path('web/css'),
            __DIR__ . '/resources/img'                                           => public_path('web/img'),
            __DIR__ . '/resources/js'                                            => public_path('web/js'),

            // Bootstrap pulled from packagist
            base_path('vendor/twbs/bootstrap/dist/css/bootstrap.min.css')                           => public_path('web/css/bootstrap.min.css'),
            base_path('vendor/almasaeed2010/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') => public_path('web/js/bootstrap.bundle.min.js'),

            // Datatables pulled from packagist
            base_path('vendor/datatables/datatables/media/css/dataTables.bootstrap4.min.css') => public_path('web/css/dataTables.bootstrap4.min.css'),
            base_path('vendor/datatables/datatables/media/js/jquery.dataTables.min.js')       => public_path('web/js/jquery.dataTables.min.js'),
            base_path('vendor/datatables/datatables/media/js/dataTables.dataTables.min.js')   => public_path('web/js/dataTables.dataTables.min.js'),
            base_path('vendor/datatables/datatables/media/js/dataTables.bootstrap4.min.js')   => public_path('web/js/dataTables.bootstrap4.min.js'),

            // AdminLTE pulled from packagist
            base_path('vendor/almasaeed2010/adminlte/dist/css/adminlte.min.css') => public_path('web/css/adminlte.min.css'),
            base_path('vendor/almasaeed2010/adminlte/dist/js/adminlte.min.js')   => public_path('web/js/adminlte.min.js'),

            // Font Awesome pulled from packagist
            base_path('vendor/components/font-awesome/css/all.min.css')          => public_path('web/css/all.min.css'),
            base_path('vendor/components/font-awesome/webfonts')                 => public_path('web/webfonts'),
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

        // Character layout breadcrumb
        $this->app['view']->composer([
            'web::character.layouts.view',
        ], CharacterLayout::class);

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

        // Corporation layout breadcrumb
        $this->app['view']->composer([
            'web::corporation.layouts.view',
        ], CorporationLayout::class);

    }

    /**
     * Include the translations and set the namespace.
     */
    public function add_translations()
    {

        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'web');
    }

    /**
     * Include the middleware needed.
     *
     * @param \Illuminate\Routing\Router $router
     */
    public function add_middleware(Router $router)
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
        $router->aliasMiddleware('squad.moderator.bouncer', SquadModeratorBouncer::class);
        $router->aliasMiddleware('squad.member.bouncer', SquadMemberBouncer::class);
        $router->aliasMiddleware('squad.author.bouncer', SquadAuthorApplicationBouncer::class);

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

        // Squads Events
        CharacterAffiliation::observe(CharacterAffiliationObserver::class);
        CharacterAsset::observe(CharacterAssetObserver::class);
        CharacterSkill::observe(CharacterSkillObserver::class);
        SquadMember::observe(SquadMemberObserver::class);
        SquadRole::observe(SquadRoleObserver::class);
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

            if (is_null($request->user()))
                return false;

            return $request->user()->has('queue_manager', false);
        });

        // attempt to parse the QUEUE_BALANCING variable into a boolean
        $balancing_mode = filter_var(config('seat.config.balancing', false), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        // in case the variable cannot be parsed into a boolean, assign the environment value itself
        if (is_null($balancing_mode))
            $balancing_mode = 'auto';

        // Configure the workers for SeAT.

        /*
        |-----------------------------------------------------------------------------------------
        | Queue Worker Configuration
        | ----------------------------------------------------------------------------------------
        | Default queue is used to collect all jobs with no queue specified
        | High queue is used to collect all jobs which need to be execute as soon as possible
        | Characters queue is used to collect characters jobs dispatched by the scheduler
        | Corporations queue is used to collect corporations jobs dispatched by the scheduler
        | Notifications queue is used to collect notifications jobs
        */

        $horizon_environment_tpl = [
            'connection' => 'redis',
            'queue'      => ['default', 'high', 'characters', 'corporations', 'public', 'notifications'],
            'balance'    => $balancing_mode,
            'tries'      => 3,
            'timeout'    => 900, // 15 minutes
        ];

        $horizon_environment = $horizon_environment_tpl;

        // adapt queue worker configuration according to auto balancing mode
        if ($balancing_mode === 'auto') {
            $horizon_environment['minProcesses'] = 1;
            $horizon_environment['maxProcesses'] = (int) config('seat.config.workers', $horizon_environment['minProcesses'] * 2);
        }

        // adapt queue worker configuration according to simple balancing mode
        if ($balancing_mode === 'simple') {
            $horizon_environment['processes'] = (int) config('seat.config.workers', count($horizon_environment['queue']));
        }

        $horizon_environments = [
            'production' => [
                'seat-workers' => $horizon_environment,
            ],
            'local' => [
                'seat-workers' => $horizon_environment,
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
        $this->mergeConfigFrom(__DIR__ . '/Config/seat.php', 'seat.config');

        // Permissions
        $this->registerPermissions(__DIR__ . '/Config/Permissions/character.php', 'character');
        $this->registerPermissions(__DIR__ . '/Config/Permissions/corporation.php', 'corporation');
        $this->registerPermissions(__DIR__ . '/Config/Permissions/global.php', 'global');
        $this->registerPermissions(__DIR__ . '/Config/Permissions/mail.php', 'mail');
        $this->registerPermissions(__DIR__ . '/Config/Permissions/people.php', 'people');
        $this->registerPermissions(__DIR__ . '/Config/Permissions/search.php', 'search');
        $this->registerPermissions(__DIR__ . '/Config/Permissions/moon.php', 'moon');

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
        // Register the datatables package! Thanks
        //  https://laracasts.com/discuss/channels/laravel/register-service-provider-and-facade-within-service-provider
        $this->app->register('Yajra\DataTables\DataTablesServiceProvider');
        $loader = AliasLoader::getInstance();
        $loader->alias('DataTables', 'Yajra\DataTables\Facades\DataTables');
    }

    /**
     * Set the path for migrations which should
     * be migrated by laravel. More informations:
     * https://laravel.com/docs/5.5/packages#migrations.
     */
    private function add_migrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations/');
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

    /**
     * Return an URI to a CHANGELOG.md file or an API path which will be providing changelog history.
     *
     * @return string|null
     */
    public function getChangelogUri(): ?string
    {
        return 'https://api.github.com/repos/eveseat/web/releases';
    }

    /**
     * In case the changelog is provided from an API, this will help to determine which attribute is containing the
     * changelog body.
     *
     * @exemple body
     *
     * @return string|null
     */
    public function getChangelogBodyAttribute(): ?string
    {
        return 'body';
    }

    /**
     * In case the changelog is provided from an API, this will help to determine which attribute is containing the
     * version name.
     *
     * @example tag_name
     *
     * @return string|null
     */
    public function getChangelogTagAttribute(): ?string
    {
        return 'tag_name';
    }

    /**
     * Return the plugin public name as it should be displayed into settings.
     *
     * @return string
     */
    public function getName(): string
    {
        return 'SeAT Web';
    }

    /**
     * Return the plugin repository address.
     *
     * @return string
     */
    public function getPackageRepositoryUrl(): string
    {
        return 'https://github.com/eveseat/web';
    }

    /**
     * Return the plugin technical name as published on package manager.
     *
     * @return string
     */
    public function getPackagistPackageName(): string
    {
        return 'web';
    }

    /**
     * Return the plugin vendor tag as published on package manager.
     *
     * @return string
     */
    public function getPackagistVendorName(): string
    {
        return 'eveseat';
    }

    /**
     * Return the plugin installed version.
     *
     * @return string
     */
    public function getVersion(): string
    {
        return config('web.config.version');
    }
}
