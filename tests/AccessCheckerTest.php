<?php
/*
This file is part of SeAT

Copyright (C) 2019  Leon Jacobs

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

namespace Seat\Tests\Web;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Orchestra\Testbench\TestCase;
use Seat\Eveapi\Models\Character\CharacterAffiliation;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Character\CharacterRole;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Web\Acl\AccessChecker;
use Seat\Web\Exceptions\BouncerException;
use Seat\Web\Models\Acl\Permission;
use Seat\Web\Models\Acl\Role;
use Seat\Web\Models\User;

class AccessCheckerTest extends TestCase
{
    use AccessChecker;
    use RefreshDatabase;

    protected $character;

    protected $request_character;

    protected $request_corporation;

    protected $filters;

    protected $user;

    protected $group;

    protected $user_permissions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->withFactories(__DIR__ . '/database/factories');

        $this->databaseSeed();

        // init a character model which will mock the requested page
        $this->request_character = new CharacterInfo([
            'character_id'   => 90795931,
        ]);

        $this->request_character->affiliation = new CharacterAffiliation([
            'character_id'   => 90795931,
            'corporation_id' => 98413060,
            'alliance_id'    => 150097440,
        ]);

        // init a corporation model which will mock the requested page
        $this->request_corporation = new CorporationInfo([
            'corporation_id' => 98413060,
            'alliance_id'    => 150097440,
            'ceo_id'         => 90795939,
        ]);

        // init an user model which will mock the authenticated user
        $this->user = new User([
            'id' => 90795931,
        ]);

        $this->filters = json_decode(file_get_contents(__DIR__ . '/artifacts/access_filters.json'));
    }

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        // Setup database
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [
            \Orchestra\Database\ConsoleServiceProvider::class,
        ];
    }

    public function testIsGlobalScope()
    {
        $permission = new Permission([
            'title' => 'somethingpermission',
        ]);
        $this->assertTrue($permission->isGlobalScope());

        $permission = new Permission([
            'title' => 'character.permission',
        ]);
        $this->assertFalse($permission->isGlobalScope());

        $permission = new Permission([
            'title' => 'corporation.permission',
        ]);
        $this->assertFalse($permission->isGlobalScope());
    }

    public function testIsCharacterScope()
    {
        $permission = new Permission([
            'title' => 'character.permission',
        ]);
        $this->assertTrue($permission->isCharacterScope());

        $permission = new Permission([
            'title' => 'somethingcharacter',
        ]);
        $this->assertFalse($permission->isCharacterScope());

        $permission = new Permission([
            'title' => 'something.character',
        ]);
        $this->assertFalse($permission->isCharacterScope());
    }

    public function testIsCorporationScope()
    {
        $permission = new Permission([
            'title' => 'corporation.permission',
        ]);
        $this->assertTrue($permission->isCorporationScope());

        $permission = new Permission([
            'title' => 'somethingcorporation',
        ]);
        $this->assertFalse($permission->isCorporationScope());

        $permission = new Permission([
            'title' => 'something.corporation',
        ]);
        $this->assertFalse($permission->isCorporationScope());
    }

    public function testCharacterId()
    {
        request()->character_id = $this->request_character->character_id;
        $this->assertEquals($this->request_character->character_id, $this->getCharacterId());
    }

    public function testCharacterIdBouncerException()
    {
        $this->expectException(BouncerException::class);
        $this->getCharacterId();
    }

    public function testCorporationId()
    {
        request()->corporation_id = $this->request_corporation->corporation_id;
        $this->assertEquals($this->request_corporation->corporation_id, $this->getCorporationId());
    }

    public function testCorporationIdBouncerException()
    {
        $this->expectException(BouncerException::class);
        $this->getCorporationId();
    }

    public function testHasSuperUser()
    {
        $this->assertFalse($this->hasSuperUser());

        $this->user_permissions = [
            'character.sheet',
            'global.superuser',
            'corporation.summary',
            'corporation.assets',
        ];

        $this->assertTrue($this->hasSuperUser());
    }

    public function testHasPermissions()
    {
        $this->user_permissions = [
            'character.sheet',
            'corporation.summary',
            'corporation.assets',
        ];

        $this->assertFalse($this->hasPermissions('superuser'));
        $this->assertTrue($this->hasPermissions('character.sheet'));
    }

    public function testHasRole()
    {
        $roles = [
            new Role([
                'title' => 'Test Role A',
            ]),
            new Role([
                'title' => 'Test Role C',
            ]),
        ];

        $this->roles = collect($roles);

        $this->assertFalse($this->hasRole('Test Role B'));
        $this->assertTrue($this->hasRole('Test Role C'));

        $this->user_permissions = [
            'global.superuser',
        ];

        $this->assertTrue($this->hasRole('Test Role D'));
    }

    public function testIsGrantedCharacter()
    {
        $permission = new Permission([
            'title' => 'character.sheet',
        ]);

        $requested_character = new CharacterInfo([
            'character_id'   => 90795931,
        ]);

        $requested_character->affiliation = new CharacterAffiliation([
            'character_id'   => 90795931,
            'corporation_id' => 98413060,
            'alliance_id'    => 150097440,
        ]);

        $this->assertTrue($this->isGrantedByFilters($permission, $this->filters, $requested_character));

        $requested_character = new CharacterInfo([
            'character_id'   => 90795932,
        ]);

        $requested_character->affiliation = new CharacterAffiliation([
            'character_id'   => 90795932,
            'corporation_id' => 98413061,
            'alliance_id'    => 150097441,
        ]);

        $this->assertFalse($this->isGrantedByFilters($permission, $this->filters, $requested_character));
    }

    public function testIsGrantedCorporation()
    {
        $permission = new Permission([
            'title' => 'corporation.sheet',
        ]);

        $this->assertTrue($this->isGrantedByFilters($permission, $this->filters, $this->request_corporation));
    }

    /**
     * @dataProvider requestedEntitiesProvider
     */
    public function testIsGrantedByFilter($entity_type, $entity_id, $expected)
    {
        $this->assertSame($expected, $this->isGrantedByFilter($this->filters, $entity_type, $entity_id));
    }

    public function testIsOwner()
    {
        $this->assertFalse($this->isOwner());

        request()->character_id = 90795931;
        $this->assertTrue($this->isOwner());

        request()->character_id = 90795932;
        $this->assertFalse($this->isOwner());
    }

    public function testIsCeo()
    {
        $this->assertFalse($this->isOwner());

        $corporation = factory(CorporationInfo::class)->create([
            'ceo_id' => 90795931,
        ]);

        $this->assertFalse($this->isCeo());

        request()->corporation_id = $corporation->corporation_id;

        $this->assertTrue($this->isCeo());

        $character = factory(CharacterInfo::class)->create();

        $character->affiliation = factory(CharacterAffiliation::class)->create([
            'character_id' => $character->affiliation,
            'corporation_id' => $corporation->corporation_id,
        ]);

        request()->character_id = $character->character_id;

        $this->assertTrue($this->isCeo());
    }

    public function testHas()
    {
        $this->assertFalse($this->has('character.sheet', false));

        $this->user_permissions = [
            'corporation.summary',
            'global.superuser',
        ];

        $this->assertTrue($this->has('character.sheet', false));

        $this->user_permissions = [
            'character.sheet',
            'corporation.summary',
        ];

        $this->assertTrue($this->has('corporation.summary', false));
    }

    public function testHasAny()
    {
        $this->assertFalse($this->hasAny(['character.sheet', 'corporation.summary'], false));

        $this->user_permissions = [
            'global.superuser',
        ];

        $this->assertTrue($this->hasAny(['character.sheet', 'corporation.summary'], false));

        $this->user_permissions = [
            'character.sheet',
        ];

        $this->assertTrue($this->hasAny(['character.sheet', 'corporation.summary'], false));
    }

    public function testGetAffiliationMap()
    {
        $roles       = [];
        $permissions = [];

        // define permissions table
        $permission_specifications = [
            [
                'global.superuser' => [
                    'not'     => false,
                    'filters' => null,
                ],
            ],
            [
                'character.sheet' => [
                    'not'     => false,
                    'filters' => null,
                ],
            ],
            [
                'character.sheet' => [
                    'not'     => true,
                    'filters' => '{"character":[{"id":7778965,"text":"Test character"}]}',
                ],
            ],
            [
                'corporation.summary' => [
                    'not'     => false,
                    'filters' => null,
                ],
            ],
            [
                'corporation.assets' => [
                    'not'     => false,
                    'filters' => '{"corporation":[{"id":123456,"text":"Test corporation"}]}',
                ],
            ],
            [
                'character.assets' => [
                    'not'     => false,
                    'filters' => '{"character":[{"id":123468,"text":"Test character"}],"alliance":[{"id":9876542,"text":"Test alliance"}]}',
                ],
            ],
            [
                'corporation.industry' => [
                    'not' => false,
                    'filters' => '{"alliance":[{"id":654879,"text":"Somme alliance"}]}',
                ],
            ],
            [
                'character.industry' => [
                    'not' => false,
                    'filters' => '{"alliance":[{"id":654879,"text":"Somme alliance"}]}',
                ],
            ],
        ];

        // define roles table
        $role_specifications = [
            'Test Role A' => [0, 5],
            'Test Role B' => [1, 3, 4],
            'Test Role C' => [2, 4],
            'Test Role D' => [1, 3, 6, 7],
        ];

        // build permissions table
        foreach ($permission_specifications as $permission_specification) {

            foreach ($permission_specification as $title => $pivot) {

                $permission = new Permission([
                    'title' => $title,
                ]);

                $permission->pivot = (object) $pivot;

                array_push($permissions, $permission);
            }
        }

        // build roles table
        foreach ($role_specifications as $title => $assigned_permissions) {

            $role = new Role([
                'title' => $title,
            ]);

            $role->permissions = collect();

            foreach ($assigned_permissions as $permission_id)
                $role->permissions->push($permissions[$permission_id]);

            array_push($roles, $role);
        }

        // assign roles to the user
        $this->roles = collect($roles);

        // assign corporation role to the character
        $this->character = $this->request_character;
        $this->character->corporation_roles = collect([
            new CharacterRole([
                'role' => 'Accountant',
            ]),
        ]);

        $expected_map = [
            'char' => [
                90795931 => [
                    'character.*',
                ],
                123468 => [
                    'character.assets',
                ],
                123456789 => [
                    'character.sheet',
                ],
                987654321 => [
                    'character.sheet',
                ],
            ],
            'corp' => [
                123456789 => [
                    'corporation.summary',
                ],
                987654321 => [
                    'corporation.summary',
                ],
                123456 => [
                    'corporation.assets',
                ],
                98413060 => [
                    'corporation.summary',
                    'corporation.journal',
                    'corporation.transaction',
                ],
            ],
            'inverted_permissions' => [
                'character.sheet',
            ],
            'inverted_affiliations' => [
                'char' => [],
                'corp' => [],
            ],
        ];

        $this->assertSame($expected_map, $this->getAffiliationMap());
    }

    /**
     * @dataProvider corporationRoleProvider
     */
    public function testGetPermissionsFromCorporationRoles($role, $expected_permission)
    {
        $this->assertSame([], $this->getPermissionsFromCorporationRoles());

        $this->character = new CharacterInfo([
            'character_id'   => 90795931,
        ]);

        $this->character->affiliation = new CharacterAffiliation([
            'character_id'   => 90795931,
            'corporation_id' => 98413060,
            'alliance_id'    => 150097440,
        ]);

        $this->character->corporation_roles = collect([
            new CharacterRole([
                'role' => $role,
            ]),
        ]);

        $this->assertSame($expected_permission, $this->getPermissionsFromCorporationRoles());
    }

    public function requestedEntitiesProvider()
    {
        return [
            ['character', 90795930, false],
            ['corporation', 98413061, false],
            ['alliance', 150097441, false],
            ['character', 90795931, true],
            ['corporation', 98413060, true],
            ['alliance', 150097440, true],
            ['random_type', 0, false],
        ];
    }

    public function corporationRoleProvider()
    {
        return [
            ['Accountant', [98413060 => ['corporation.summary', 'corporation.journal', 'corporation.transaction']]],
            ['Auditor', [98413060 => ['corporation.summary']]],
            ['Contract_Manager', [98413060 => ['corporation.summary', 'corporation.contracts']]],
            ['Diplomat', [98413060 => ['corporation.summary', 'corporation.tracking']]],
            ['Director', [98413060 => ['corporation.*']]],
            ['Junior_Accountant', [98413060 => ['corporation.summary']]],
            ['Security_Officer', [98413060 => ['corporation.summary', 'corporation.security']]],
            ['Trader', [98413060 => ['corporation.summary', 'corporation.market']]],
        ];
    }

    /**
     * Overload standard User method in order to return a list of character Ids
     *
     * @return Collection
     */
    public function getAllCharacters(): Collection
    {
        return collect([
            new CharacterInfo([
                'character_id' => 123456789,
            ]),
            new CharacterInfo([
                'character_id' => 987654321,
            ]),
        ]);
    }

    /**
     * Overload standard User method in order to return a list of corporation Ids
     *
     * @return Collection
     */
    public function getAllCorporations()
    {
        return collect([
            new CorporationInfo([
                'corporation_id' => 123456789,
            ]),
            new CorporationInfo([
                'corporation_id' => 987654321,
            ]),
        ]);
    }

    /**
     * Overload standard trait method in order to return a list of user permissions
     *
     * @return array
     */
    public function getAllPermissions()
    {

        if (is_null($this->user_permissions))
            return [];

        return $this->user_permissions;
    }

    /**
     * Overload standard User method in order to return a list of user character Ids
     *
     * @return Collection
     */
    private function associatedCharacterIds()
    {
        return collect([
            90795931,
        ]);
    }

    private function databaseSeed()
    {
        $users = factory(User::class, 20)->create();
        $characters = factory(CharacterInfo::class, 20)->create();
        $corporations = factory(CorporationInfo::class, 3)->create();
    }
}
