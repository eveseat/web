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
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Eveapi\Models\RefreshToken;
use Seat\Web\Models\Acl\Permission;
use Seat\Web\Models\Acl\Role;
use Seat\Web\Models\User;
use Seat\Web\WebServiceProvider;

/**
 * Class CharacterPolicyTest.
 *
 * @package Seat\Tests\Web\Acl
 */
class CharacterPolicyTest extends TestCase
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
     * Test ACL character permissions as a random user.
     */
    public function testCharacterPermissionsAsGuest()
    {
        $permissions = array_keys(require __DIR__ . '/../../src/Config/Permissions/character.php');

        $user = User::factory()->create();

        $character = CharacterInfo::factory()->create();

        foreach ($permissions as $permission) {
            Redis::flushdb();

            $scope_permission = sprintf('character.%s', $permission);
            $this->assertFalse($user->can($scope_permission, $character));
        }
    }

    /**
     * Test ACL character permissions as a super-admin.
     */
    public function testCharacterPermissionsAsAdmin()
    {
        $permissions = array_keys(require __DIR__ . '/../../src/Config/Permissions/character.php');

        $user = User::factory()->create([
            'admin' => true,
        ]);

        $character = CharacterInfo::factory()->create();

        foreach ($permissions as $permission) {
            Redis::flushdb();

            $scope_permission = sprintf('character.%s', $permission);
            $this->assertTrue($user->can($scope_permission, $character));
        }
    }

    /**
     * Test ACL character permissions as character owner.
     */
    public function testCharacterPermissionsAsOwner()
    {
        Event::fake();

        $permissions = array_keys(require __DIR__ . '/../../src/Config/Permissions/character.php');

        $user = User::factory()->create();

        $owned_character = CharacterInfo::factory()->create();
        RefreshToken::factory()->create([
            'character_id' => $owned_character->character_id,
            'user_id'      => $user->id,
        ]);

        foreach ($permissions as $permission) {
            Redis::flushdb();

            $scope_permission = sprintf('character.%s', $permission);
            $this->assertTrue($user->can($scope_permission, $owned_character));
        }
    }

    /**
     * Test ACL character permissions as character CEO.
     */
    public function testCharacterPermissionsAsCeo()
    {
        Event::fake();

        $permissions = array_keys(require __DIR__ . '/../../src/Config/Permissions/character.php');

        $user = User::factory()->create();

        $characters = CharacterInfo::factory(2)->create();
        $ceo = $characters->first();
        $corporation_member = $characters->last();

        $ceo->affiliation()->create([
            'corporation_id' => 1000001,
        ]);

        $corporation_member->affiliation()->create([
            'corporation_id' => 1000001,
        ]);

        CorporationInfo::factory()->create([
            'corporation_id' => 1000001,
            'ceo_id'         => $ceo->character_id,
        ]);

        RefreshToken::factory()->create([
            'character_id' => $ceo->character_id,
            'user_id'      => $user->id,
        ]);

        foreach ($permissions as $permission) {
            Redis::flushdb();

            $scope_permission = sprintf('character.%s', $permission);
            $this->assertTrue($user->can($scope_permission, $corporation_member));
        }
    }

    /**
     * Test ACL character permissions without filters.
     */
    public function testCharacterPermissionsAsWildcard()
    {
        $permissions = array_keys(require __DIR__ . '/../../src/Config/Permissions/character.php');

        $user = User::factory()->create();

        $role = Role::factory()->create();
        $role->users()->save($user);

        $character = CharacterInfo::factory()->create();

        // seed role with all character permissions
        foreach ($permissions as $permission) {
            Redis::flushdb();

            $scope_permission = sprintf('character.%s', $permission);

            $role_permission = Permission::create([
                'title' => $scope_permission,
            ]);

            $role->permissions()->attach($role_permission->id);

            $this->assertTrue($user->can($scope_permission, $character));
        }
    }

    /**
     * Test ACL character permissions with character filters.
     */
    public function testCharacterPermissionsAsDelegatedCharacter()
    {
        $permissions = array_keys(require __DIR__ . '/../../src/Config/Permissions/character.php');

        $user = User::factory()->create();

        $role = Role::factory()->create();
        $role->users()->save($user);

        $characters = CharacterInfo::factory(2)->create();
        $denied_character = $characters->last();
        $granted_character = $characters->first();

        // seed role with all character permissions
        foreach ($permissions as $permission) {
            Redis::flushdb();

            $scope_permission = sprintf('character.%s', $permission);

            $role_permission = Permission::create([
                'title' => $scope_permission,
            ]);

            $role->permissions()->attach($role_permission->id, [
                'filters' => json_encode([
                    'character' => [
                        [
                            'id'   => $granted_character->character_id,
                            'text' => $granted_character->name,
                        ],
                    ],
                ]),
            ]);

            $this->assertTrue($user->can($scope_permission, $granted_character));
            $this->assertFalse($user->can($scope_permission, $denied_character));
        }
    }

    /**
     * Test ACL character permissions with corporation filters.
     */
    public function testCharacterPermissionsAsDelegatedCorporation()
    {
        // make sure this doesn't generate observer events that trigger the squads logic
        CharacterAffiliation::unsetEventDispatcher();

        $permissions = array_keys(require __DIR__ . '/../../src/Config/Permissions/character.php');

        $user = User::factory()->create();

        $role = Role::factory()->create();
        $role->users()->save($user);

        $characters = CharacterInfo::factory(2)->create();
        $denied_character = $characters->last();
        $granted_character = $characters->first();

        $corporations = CorporationInfo::factory(2)->create();
        $granted_corporation = $corporations->first();
        CharacterAffiliation::create([
            'character_id'   => $granted_character->character_id,
            'corporation_id' => $granted_corporation->corporation_id,
        ]);

        $denied_corporation = $corporations->last();
        CharacterAffiliation::create([
            'character_id'   => $denied_character->character_id,
            'corporation_id' => $denied_corporation->corporation_id,
        ]);

        // seed role with all character permissions
        foreach ($permissions as $permission) {
            Redis::flushdb();

            $scope_permission = sprintf('character.%s', $permission);

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

            $this->assertTrue($user->can($scope_permission, $granted_character));
            $this->assertFalse($user->can($scope_permission, $denied_character));
        }
    }

    /**
     * Test ACL character permissions with alliance filters.
     */
    public function testCharacterPermissionsAsDelegatedAlliance()
    {
        // make sure this doesn't generate observer events that trigger the squads logic
        CharacterAffiliation::unsetEventDispatcher();

        $permissions = array_keys(require __DIR__ . '/../../src/Config/Permissions/character.php');

        $user = User::factory()->create();

        $role = Role::factory()->create();
        $role->users()->save($user);

        $characters = CharacterInfo::factory(2)->create();
        $denied_character = $characters->last();
        $granted_character = $characters->first();

        $corporations = CorporationInfo::factory(2)->create();
        $denied_corporation = $corporations->first();
        $granted_corporation = $corporations->last();

        CharacterAffiliation::create([
            'character_id'   => $granted_character->character_id,
            'corporation_id' => $granted_corporation->corporation_id,
            'alliance_id'    => 99000000,
        ]);

        CharacterAffiliation::create([
            'character_id'   => $denied_character->character_id,
            'corporation_id' => $denied_corporation->corporation_id,
            'alliance_id'    => 99000001,
        ]);

        // seed role with all character permissions
        foreach ($permissions as $permission) {
            Redis::flushdb();

            $scope_permission = sprintf('character.%s', $permission);

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

            $this->assertTrue($user->can($scope_permission, $granted_character));
            $this->assertFalse($user->can($scope_permission, $denied_character));
        }
    }
}
