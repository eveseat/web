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
use Seat\Eveapi\Models\Character\CharacterSkill;
use Seat\Eveapi\Models\RefreshToken;
use Seat\Web\Models\Squads\Squad;
use Seat\Web\Models\User;
use Seat\Web\WebServiceProvider;

/**
 * Class SkillAffiliationRulesGroupsTest.
 *
 * @package Seat\Tests\Web\Squads
 */
class SkillAffiliationRulesGroupsTest extends TestCase
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

        CharacterInfo::factory(50)
            ->create()
            ->each(function ($character) {
                $character->skills()->saveMany(CharacterSkill::factory(20)->make());
                $character->affiliation()->save(CharacterAffiliation::factory()->make());
            });

        User::factory(10)
            ->create()
            ->each(function ($user) {
                CharacterInfo::whereDoesntHave('refresh_token')->get()
                    ->random(rand(1, 5))->each(function ($character) use ($user) {
                        RefreshToken::factory()->create([
                            'character_id' => $character->character_id,
                            'user_id' => $user->id,
                        ]);
                    });
            });
    }

    public function testUserDoNotMeetConditions()
    {
        // spawn test squad
        $squad = new Squad([
            'name' => 'Testing Squad',
            'description' => 'Some description',
            'type' => 'auto',
            'filters' => json_encode([
                'or' => [
                    [
                        'and' => [
                            [
                                'name' => 'skill',
                                'path' => 'skills',
                                'field' => 'skill_id',
                                'operator' => '=',
                                'criteria' => 3350,
                                'text' => 'Random Skill',
                            ],
                            [
                                'name' => 'skill level',
                                'path' => 'skills',
                                'field' => 'trained_skill_level',
                                'operator' => '>',
                                'criteria' => 5,
                                'text' => 'Random Skill Level',
                            ],
                        ],
                    ],
                    [
                        'and' => [
                            [
                                'name' => 'corporation',
                                'path' => 'affiliation',
                                'field' => 'corporation_id',
                                'operator' => '=',
                                'criteria' => 98541700,
                                'text' => 'Random Corporation',
                            ],
                            [
                                'name' => 'alliance',
                                'path' => 'affiliation',
                                'field' => 'alliance_id',
                                'operator' => '=',
                                'criteria' => 99000000,
                                'text' => 'Random Alliance',
                            ],
                        ],
                    ]
                ],
            ]),
        ]);

        // pickup users
        $users = User::all();

        // ensure no users are eligible
        foreach ($users as $user) {
            $this->assertFalse($squad->isUserEligible($user));
        }
    }

    public function testUserMeetConditions()
    {
        // spawn test squad
        $squad = new Squad([
            'name' => 'Testing Squad',
            'description' => 'Some description',
            'type' => 'auto',
            'filters' => json_encode([
                'or' => [
                    [
                        'and' => [
                            [
                                'name' => 'skill',
                                'path' => 'skills',
                                'field' => 'skill_id',
                                'operator' => '=',
                                'criteria' => 3350,
                                'text' => 'Random Skill',
                            ],
                            [
                                'name' => 'skill level',
                                'path' => 'skills',
                                'field' => 'trained_skill_level',
                                'operator' => '>',
                                'criteria' => 5,
                                'text' => 'Random Skill Level',
                            ],
                        ],
                    ],
                    [
                        'and' => [
                            [
                                'name' => 'corporation',
                                'path' => 'affiliation',
                                'field' => 'corporation_id',
                                'operator' => '=',
                                'criteria' => 98541700,
                                'text' => 'Random Corporation',
                            ],
                            [
                                'name' => 'alliance',
                                'path' => 'affiliation',
                                'field' => 'alliance_id',
                                'operator' => '=',
                                'criteria' => 99000000,
                                'text' => 'Random Alliance',
                            ],
                        ],
                    ]
                ],
            ]),
        ]);

        // pickup users
        $users = User::all();
        $reference_user = $users->first();
        $reference_character = $reference_user->characters->first();

        $reference_character->affiliation->update([
            'corporation_id' => 98541700,
        ]);

        $this->assertFalse($squad->isUserEligible($reference_user));

        $reference_character->affiliation->update([
            'alliance_id' => 99000000,
            'corporation_id' => 98541699,
        ]);

        $this->assertFalse($squad->isUserEligible($reference_user));

        $reference_character->affiliation->update([
            'alliance_id' => 99000000,
            'corporation_id' => 98541700,
        ]);

        $this->assertTrue($squad->isUserEligible($reference_user));
    }
}
