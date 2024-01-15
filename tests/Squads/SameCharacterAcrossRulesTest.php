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

namespace Seat\Tests\Web\Squads;

use Illuminate\Support\Facades\Event;
use Lunaweb\RedisMock\Providers\RedisMockServiceProvider;
use Orchestra\Testbench\TestCase;
use Seat\Eveapi\Models\Character\CharacterAffiliation;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Character\CharacterRole;
use Seat\Eveapi\Models\RefreshToken;
use Seat\Web\Models\Squads\Squad;
use Seat\Web\Models\User;
use Seat\Web\WebServiceProvider;

/**
 * Class SameCharacterAcrossRulesTest.
 *
 * This class contains test that ensure that two rules use the same character when checking if they apply.
 *
 * @package Seat\Tests\Web\Squads
 */
class SameCharacterAcrossRulesTest extends TestCase
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

        Event::fake();
    }

    public function testGroupUsesSameCharacterForAllRules()
    {
        $CORPORATION_ID = 98681714;
        $ROLE = 'Director';

        // STEP: spawn test squad
        $squad = new Squad([
            'name' => 'Testing Squad',
            'description' => 'Some description',
            'type' => 'auto',
            'filters' => json_encode([
                'and' => [
                    [
                        'name'=>'corporation',
                        'path'=>'characters.affiliation',
                        'field'=>'corporation_id',
                        'criteria'=>strval($CORPORATION_ID),
                        'operator'=>'=',
                        'text'=>'Backbone Trading Inc'
                    ],
                    [
                        'name'=>'role',
                        'path'=>'characters.corporation_roles',
                        'field'=>'role',
                        'criteria'=>$ROLE,
                        'operator'=>'=',
                        'text'=>$ROLE
                    ],
                ]
            ]),
        ]);

        // STEP: Create user with characters
        // get a user with two or more character
        $user = User::factory()->create();
        // get two characters
        $character_1 = CharacterInfo::factory()->create();
        $character_2 = CharacterInfo::factory()->create();
        // attach character to user
        RefreshToken::factory()->create([
            'character_id' => $character_1->character_id,
            'user_id' => $user->id,
        ]);
        RefreshToken::factory()->create([
            'character_id' => $character_2->character_id,
            'user_id' => $user->id,
        ]);

        // attach affiliation to first character
        CharacterAffiliation::factory()->create([
            'corporation_id' => $CORPORATION_ID,
            'character_id' => $character_1->character_id
        ]);

        // attach role to second character
        CharacterRole::factory()->create([
            'role' => $ROLE,
            'character_id' => $character_2->character_id
        ]);

        $this->assertFalse($squad->isEligible($user));
    }
}
