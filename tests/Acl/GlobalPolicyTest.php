<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2020 Leon Jacobs
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

namespace Seat\Tests\Web\Acl;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Redis;
use Lunaweb\RedisMock\Providers\RedisMockServiceProvider;
use Orchestra\Testbench\TestCase;
use Seat\Web\Models\Acl\Permission;
use Seat\Web\Models\Acl\Role;
use Seat\Web\Models\User;
use Seat\Web\WebServiceProvider;

/**
 * Class GlobalPolicyTest.
 *
 * @package Seat\Tests\Web\Acl
 */
class GlobalPolicyTest extends TestCase
{
    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
        $app['config']->set('database.redis.client', 'mock');
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     * @return array|string[]
     */
    protected function getPackageProviders($app)
    {
        return [
            RedisMockServiceProvider::class,
            WebServiceProvider::class,
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(realpath(__DIR__ . '/../database/migrations'));
    }

    /**
     * Test ACL global permissions as a random user.
     */
    public function testGlobalPermissionsAsGuest()
    {
        $permissions = array_keys(require __DIR__ . '/../../src/Config/Permissions/global.php');

        $user = User::factory()->create();

        foreach ($permissions as $permission) {
            Redis::flushdb();

            $scope_permission = sprintf('global.%s', $permission);
            $this->assertFalse($user->can($scope_permission));
        }
    }

    /**
     * Test ACL global permissions as a super-admin.
     */
    public function testGlobalPermissionsAsAdmin()
    {
        $permissions = array_keys(require __DIR__ . '/../../src/Config/Permissions/global.php');

        $user = User::factory()->create([
            'admin' => true,
        ]);

        foreach ($permissions as $permission) {
            Redis::flushdb();

            $scope_permission = sprintf('global.%s', $permission);
            $this->assertTrue($user->can($scope_permission));
        }
    }

    public function testVanillaSuperuser()
    {
        $user = User::factory()->create();

        $role = Role::factory()->create();
        $role->users()->save($user);

        $role_permission = Permission::create([
            'title' => 'global.superuser',
        ]);

        $role->permissions()->attach($role_permission->id);

        $this->assertFalse($user->can('global.superuser'));

        $user->admin = true;
        $user->save();

        Redis::flushdb();

        $this->assertTrue($user->can('global.superuser'));
    }

    /**
     * Test ACL global permissions.
     */
    public function testGlobalPermissions()
    {
        Event::fake();

        $permissions = array_keys(require __DIR__ . '/../../src/Config/Permissions/global.php');

        $user = User::factory()->create();

        $role = Role::factory()->create();
        $role->users()->save($user);

        foreach ($permissions as $permission) {
            Redis::flushdb();

            $scope_permission = sprintf('global.%s', $permission);

            $role_permission = Permission::create([
                'title' => $scope_permission,
            ]);

            $role->permissions()->attach($role_permission->id);

            $this->assertTrue($user->can($scope_permission));
        }
    }
}
