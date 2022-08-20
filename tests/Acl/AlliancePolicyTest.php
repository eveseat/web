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

use Illuminate\Support\Facades\Redis;
use Lunaweb\RedisMock\Providers\RedisMockServiceProvider;
use Orchestra\Testbench\TestCase;
use Seat\Eveapi\Models\Alliances\Alliance;
use Seat\Eveapi\Models\Character\CharacterAffiliation;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Character\CharacterRole;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Web\Models\Acl\Permission;
use Seat\Web\Models\Acl\Role;
use Seat\Web\Models\User;
use Seat\Web\WebServiceProvider;

/**
 * Class AlliancePolicyTest.
 *
 * @package Seat\Tests\Web\Acl
 */
class AlliancePolicyTest extends TestCase
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

        $this->withFactories(__DIR__ . '/../database/factories');
    }

    /**
     * Test ACL alliance permissions as a random user.
     */
    public function testAlliancePermissionsAsGuest()
    {
        $permissions = array_keys(require __DIR__ . '/../../src/Config/Permissions/alliance.php');

        $user = factory(User::class)->create();

        $alliance = factory(Alliance::class)->create();

        foreach ($permissions as $permission) {
            Redis::flushdb();

            $scope_permission = sprintf('alliance.%s', $permission);
            $this->assertFalse($user->can($scope_permission, $alliance));
        }
    }

    /**
     * Test ACL alliance permissions as a super-admin.
     */
    public function testAlliancePermissionsAsAdmin()
    {
        $permissions = array_keys(require __DIR__ . '/../../src/Config/Permissions/alliance.php');

        $user = factory(User::class)->create([
            'admin' => true,
        ]);

        $alliance = factory(Alliance::class)->create();

        foreach ($permissions as $permission) {
            Redis::flushdb();

            $scope_permission = sprintf('alliance.%s', $permission);
            $this->assertTrue($user->can($scope_permission, $alliance));
        }
    }

    /**
     * Test ACL alliance permissions without filters.
     */
    public function testAlliancePermissionsAsWildcard()
    {
        $permissions = array_keys(require __DIR__ . '/../../src/Config/Permissions/alliance.php');

        $user = factory(User::class)->create();

        $role = factory(Role::class)->create();
        $role->users()->save($user);

        $alliance = factory(Alliance::class)->create();

        // seed role with all corporation permissions
        foreach ($permissions as $permission) {
            Redis::flushdb();

            $scope_permission = sprintf('alliance.%s', $permission);

            $role_permission = Permission::create([
                'title' => $scope_permission,
            ]);

            $role->permissions()->attach($role_permission->id);

            $this->assertTrue($user->can($scope_permission, $alliance));
        }
    }

    /**
     * Test ACL alliance permissions with character filters.
     */
    public function testAlliancePermissionsAsDelegatedCharacter()
    {
        $permissions = array_keys(require __DIR__ . '/../../src/Config/Permissions/alliance.php');

        $user = factory(User::class)->create();

        $role = factory(Role::class)->create();
        $role->users()->save($user);

        $alliance = factory(Alliance::class)->create();

        $corporation = factory(CorporationInfo::class)->create([
            'alliance_id' => $alliance->alliance_id,
        ]);


        $character = factory(CharacterInfo::class)->create();
        CharacterAffiliation::create([
            'character_id'   => $character->character_id,
            'corporation_id' => $corporation->corporation_id,
        ]);

        // seed role with all alliance permissions
        foreach ($permissions as $permission) {
            Redis::flushdb();

            $scope_permission = sprintf('alliance.%s', $permission);

            $role_permission = Permission::create([
                'title' => $scope_permission,
            ]);

            $role->permissions()->attach($role_permission->id, [
                'filters' => json_encode([
                    'character' => [
                        [
                            'id'   => $character->character_id,
                            'text' => $character->name,
                        ],
                    ],
                ]),
            ]);

            $this->assertFalse($user->can($scope_permission, $alliance));
        }
    }

    /**
     * Test ACL alliance permissions with corporation filters.
     */
    public function testAlliancePermissionsAsDelegatedCorporation()
    {
        $permissions = array_keys(require __DIR__ . '/../../src/Config/Permissions/alliance.php');

        $user = factory(User::class)->create();

        $role = factory(Role::class)->create();
        $role->users()->save($user);

        $alliance = factory(Alliance::class)->create();

        $corporation = factory(CorporationInfo::class)->create([
            'alliance_id' => $alliance->alliance_id,
        ]);


        $character = factory(CharacterInfo::class)->create();
        CharacterAffiliation::create([
            'character_id'   => $character->character_id,
            'corporation_id' => $corporation->corporation_id,
        ]);

        // seed role with all alliance permissions
        foreach ($permissions as $permission) {
            Redis::flushdb();

            $scope_permission = sprintf('alliance.%s', $permission);

            $role_permission = Permission::create([
                'title' => $scope_permission,
            ]);

            $role->permissions()->attach($role_permission->id, [
                'filters' => json_encode([
                    'corporation' => [
                        [
                            'id'   => $corporation->corporation_id,
                            'text' => $corporation->name,
                        ],
                    ],
                ]),
            ]);

            $this->assertFalse($user->can($scope_permission, $alliance));
        }
    }

    /**
     * Test ACL alliance permissions with alliance filters.
     */
    public function testAlliancePermissionsAsDelegatedAlliance()
    {
        $permissions = array_keys(require __DIR__ . '/../../src/Config/Permissions/alliance.php');

        $user = factory(User::class)->create();

        $role = factory(Role::class)->create();
        $role->users()->save($user);

        $alliances = factory(Alliance::class, 2)->create();
        $denied_alliance = $alliances->last();
        $granted_alliance = $alliances->first();

        // seed role with all corporation permissions
        foreach ($permissions as $permission) {
            Redis::flushdb();

            $scope_permission = sprintf('alliance.%s', $permission);

            $role_permission = Permission::create([
                'title' => $scope_permission,
            ]);

            $role->permissions()->attach($role_permission->id, [
                'filters' => json_encode([
                    'alliance' => [
                        [
                            'id'   => $granted_alliance->alliance_id,
                            'text' => $granted_alliance->name,
                        ],
                    ],
                ]),
            ]);

            $this->assertTrue($user->can($scope_permission, $granted_alliance));
            $this->assertFalse($user->can($scope_permission, $denied_alliance));
        }
    }
}
