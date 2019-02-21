<?php
/**
 * MIT License.
 *
 * Copyright (c) 2019. Felix Huber
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace Seat\Web\Test;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Seat\Web\WebServiceProvider;

abstract class TestCase extends OrchestraTestCase
{
    public function setUp()
    {
        parent::setUp();

        // Setup database
        $this->setupDatabase($this->app);
        $this->withFactories(__DIR__.'/../src/database/factories');
    }
    protected function getPackageProviders($app)
    {
        // ConsoleServiceProvider required to make migrations work
        return [
            WebServiceProvider::class,
        ];
    }
    protected function getPackageAliases($app)
    {
        // For the facade
        /*return [
            'ShopifyApp' => \OhMyBrew\ShopifyApp\Facades\ShopifyApp::class,
        ];*/
    }
    protected function resolveApplicationHttpKernel($app)
    {
        // For adding custom the shop middleware
        //$app->singleton('Illuminate\Contracts\Http\Kernel', 'OhMyBrew\ShopifyApp\Test\Stubs\Kernel');
    }
    protected function getEnvironmentSetUp($app)
    {
        // Use memory SQLite, cleans it self up
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }
    protected function setupDatabase($app)
    {
        // Path to our migrations to load
        $this->artisan('migrate', ['--database' => 'testbench']);
    }
}