<?php
/*
This file is part of SeAT

Original SeAT Copyright (C) 2015, 2016  Leon Jacobs
This file Copyright (C) 2016 Tor Livar Flugsrud

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
namespace Seat\Web\Tests;


use Illuminate\Filesystem\Filesystem;
use Illuminate\Filesystem\ClassFinder;
       

class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();
        // uncomment to enable route filters if your package defines routes with filters
        //$this->app['router']->enableFilters();
        // call migrations for packages upon which our package depends, e.g. Cartalyst/Sentry
        // not necessary if your package doesn't depend on another package that requires
        // running migrations for proper installation
        
        $this->artisan('migrate', [
            '--database' => 'testing',
            '--realpath'     => realpath(__DIR__).'/../vendor/eveseat/services/src/database/migrations',
        ]);
        
        $this->artisan('migrate', [
            '--database' => 'testing',
            '--realpath'     => realpath(__DIR__).'/../vendor/eveseat/eveapi/src/database/migrations',
        ]);
        
        // call migrations specific to our tests, e.g. to seed the db
        // the path option should be relative to the 'path.database'
        // path unless `--path` option is available.
        $this->artisan('migrate', [
            '--database' => 'testing',
            '--realpath' => realpath(__DIR__.'/../src/database/migrations'),
        ]);           
        
        $this->withFactories(__DIR__.'/factories');     
    }
    
     /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
    }
    
     /**
     * Get package providers.  At a minimum this is the package being tested, but also
     * would include packages upon which our package depends, e.g. Cartalyst/Sentry
     * In a normal app environment these would be added to the 'providers' array in
     * the config/app.php file.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            'Seat\Eveapi\EveapiServiceProvider',
            'Seat\Services\ServicesServiceProvider',
            'Seat\Web\WebServiceProvider',
        ];
    }
    
    /**
    * Resolve application HTTP Kernel implementation.
    *
    * @param  \Illuminate\Foundation\Application  $app
    * @return void
    */
    protected function resolveApplicationHttpKernel($app)
    {
        $app->singleton('Illuminate\Contracts\Http\Kernel', 'Seat\Web\Tests\Http\Kernel');
    }
    
}