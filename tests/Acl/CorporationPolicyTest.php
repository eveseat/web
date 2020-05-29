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
use Seat\Eveapi\Models\Character\CharacterAffiliation;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Character\CharacterRole;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Eveapi\Models\RefreshToken;
use Seat\Web\Acl\EsiRolesMap;
use Seat\Web\Models\Acl\Permission;
use Seat\Web\Models\Acl\Role;
use Seat\Web\Models\User;
use Seat\Web\WebServiceProvider;

/**
 * Class CorporationPolicyTest.
 *
 * @package Seat\Tests\Web\Acl
 */
class CorporationPolicyTest extends TestCase
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
     * Test ACL corporation permissions as a random user.
     */
    public function testCorporationPermissionsAsGuest()
    {
        $permissions = array_keys(require __DIR__ . '/../../src/Config/Permissions/corporation.php');

        $user = factory(User::class)->create();

        $corporation = factory(CorporationInfo::class)->create();

        foreach ($permissions as $permission) {
            Redis::flushdb();

            $scope_permission = sprintf('corporation.%s', $permission);
            $this->assertFalse($user->can($scope_permission, $corporation));
        }
    }

    /**
     * Test ACL corporation permissions as a super-admin.
     */
    public function testCorporationPermissionsAsAdmin()
    {
        $permissions = array_keys(require __DIR__ . '/../../src/Config/Permissions/corporation.php');

        $user = factory(User::class)->create([
            'admin' => true,
        ]);

        $corporation = factory(CorporationInfo::class)->create();

        foreach ($permissions as $permission) {
            Redis::flushdb();

            $scope_permission = sprintf('corporation.%s', $permission);
            $this->assertTrue($user->can($scope_permission, $corporation));
        }
    }

    /**
     * Test ACL corporation permissions as CEO.
     */
    public function testCorporationPermissionsAsCeo()
    {
        Event::fake();

        $permissions = array_keys(require __DIR__ . '/../../src/Config/Permissions/corporation.php');

        $user = factory(User::class)->create();

        $ceo = factory(CharacterInfo::class)->create();
        $ceo->affiliation()->create([
            'corporation_id' => 1000001,
        ]);

        $corporation = factory(CorporationInfo::class)->create([
            'corporation_id' => 1000001,
            'ceo_id'         => $ceo->character_id,
        ]);

        factory(RefreshToken::class)->create([
            'character_id' => $ceo->character_id,
            'user_id'      => $user->id,
        ]);

        foreach ($permissions as $permission) {
            Redis::flushdb();

            $scope_permission = sprintf('corporation.%s', $permission);
            $this->assertTrue($user->can($scope_permission, $corporation));
        }
    }

    /**
     * Test ACL corporation permissions without filters.
     */
    public function testCorporationPermissionsAsWildcard()
    {
        $permissions = array_keys(require __DIR__ . '/../../src/Config/Permissions/corporation.php');

        $user = factory(User::class)->create();

        $role = factory(Role::class)->create();
        $role->users()->save($user);

        $corporation = factory(CorporationInfo::class)->create();

        // seed role with all corporation permissions
        foreach ($permissions as $permission) {
            Redis::flushdb();

            $scope_permission = sprintf('corporation.%s', $permission);

            $role_permission = Permission::create([
                'title' => $scope_permission,
            ]);

            $role->permissions()->attach($role_permission->id);

            $this->assertTrue($user->can($scope_permission, $corporation));
        }
    }

    /**
     * Test ACL corporation permissions with character filters.
     */
    public function testCorporationPermissionsAsDelegatedCharacter()
    {
        $permissions = array_keys(require __DIR__ . '/../../src/Config/Permissions/corporation.php');

        $user = factory(User::class)->create();

        $role = factory(Role::class)->create();
        $role->users()->save($user);

        $corporation = factory(CorporationInfo::class)->create();

        $character = factory(CharacterInfo::class)->create();
        CharacterAffiliation::create([
            'character_id'   => $character->character_id,
            'corporation_id' => $corporation->corporation_id,
        ]);

        // seed role with all corporation permissions
        foreach ($permissions as $permission) {
            Redis::flushdb();

            $scope_permission = sprintf('corporation.%s', $permission);

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

            $this->assertFalse($user->can($scope_permission, $corporation));
        }
    }

    /**
     * Test ACL corporation permissions with corporation filters.
     */
    public function testCorporationPermissionsAsDelegatedCorporation()
    {
        $permissions = array_keys(require __DIR__ . '/../../src/Config/Permissions/corporation.php');

        $user = factory(User::class)->create();

        $role = factory(Role::class)->create();
        $role->users()->save($user);

        $corporations = factory(CorporationInfo::class, 2)->create();
        $denied_corporation = $corporations->last();
        $granted_corporation = $corporations->first();

        // seed role with all corporation permissions
        foreach ($permissions as $permission) {
            Redis::flushdb();

            $scope_permission = sprintf('corporation.%s', $permission);

            $role_permission = Permission::create([
                'title' => $scope_permission,
            ]);

            $role->permissions()->attach($role_permission->id, [
                'filters' => json_encode([
                    'corporation' => [
                        [
                            'id'   => $granted_corporation->corporation_id,
                            'text' => $granted_corporation->name,
                        ],
                    ],
                ]),
            ]);

            $this->assertTrue($user->can($scope_permission, $granted_corporation));
            $this->assertFalse($user->can($scope_permission, $denied_corporation));
        }
    }

    /**
     * Test ACL corporation permissions with alliance filters.
     */
    public function testCorporationPermissionsAsDelegatedAlliance()
    {
        $permissions = array_keys(require __DIR__ . '/../../src/Config/Permissions/corporation.php');

        $user = factory(User::class)->create();

        $role = factory(Role::class)->create();
        $role->users()->save($user);

        $corporations = factory(CorporationInfo::class, 2)->create();
        $denied_corporation = $corporations->first();
        $granted_corporation = $corporations->last();

        $granted_corporation->alliance_id = 99000000;
        $granted_corporation->save();

        // seed role with all corporation permissions
        foreach ($permissions as $permission) {
            Redis::flushdb();

            $scope_permission = sprintf('corporation.%s', $permission);

            $role_permission = Permission::create([
                'title' => $scope_permission,
            ]);

            $role->permissions()->attach($role_permission->id, [
                'filters' => json_encode([
                    'alliance' => [
                        [
                            'id'   => 99000000,
                            'text' => 'Testing Alliance',
                        ],
                    ],
                ]),
            ]);

            $this->assertTrue($user->can($scope_permission, $granted_corporation));
            $this->assertFalse($user->can($scope_permission, $denied_corporation));
        }
    }

    /**
     * Test ACL corporation permissions with alliance filters.
     */
    public function testCorporationPermissionAsDelegatedByInGameRole()
    {
        Event::fake();

        $user = factory(User::class)->create();

        $character = factory(CharacterInfo::class)->create();
        $corporation = factory(CorporationInfo::class)->create();

        CharacterAffiliation::create([
            'character_id'   => $character->character_id,
            'corporation_id' => $corporation->corporation_id,
        ]);

        factory(RefreshToken::class)->create([
            'character_id' => $character->character_id,
            'user_id'      => $user->id,
        ]);

        CharacterRole::create([
            'character_id' => $character->character_id,
            'role'         => 'Director',
            'scope'        => 'roles',
        ]);

        // seed role with all corporation permissions
        foreach (EsiRolesMap::DEFAULT_VALUES['Director'] as $permission) {
            Redis::flushdb();

            $this->assertTrue($user->can($permission, $corporation));
        }
    }
}
