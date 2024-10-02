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
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Eveapi\Models\Corporation\CorporationTitle;
use Seat\Eveapi\Models\RefreshToken;
use Seat\Web\Models\Squads\Squad;
use Seat\Web\Models\User;
use Seat\Web\WebServiceProvider;

/**
 * Class TitleRule.
 *
 * @package Seat\Tests\Web\Squads
 */
class TitleRuleTest extends TestCase
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

        CorporationInfo::factory(10)
            ->create()
            ->each(function ($corporation) {

                $corporation->titles()->saveMany(CorporationTitle::factory(20)->make());

                CharacterInfo::factory(20)
                    ->create()
                    ->each(function ($character) use ($corporation) {
                        $character->affiliation()->save(CharacterAffiliation::factory()->make([
                            'corporation_id' => $corporation->corporation_id,
                        ]));

                        $titles = $corporation->titles->random(rand(0, 10));

                        if ($titles->count() > 0)
                            $character->titles()->attach($titles);
                    });
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

        // spawn 10 users
        // attach characters between 1 to 5 to each users
        // attach between 0 to 20 skills with ID between 3300 and 3349 using a level between 0 to 5
    }

    public function testUserHasNoCharacterWithTitle()
    {
        // spawn test squad
        $squad = new Squad([
            'name' => 'Testing Squad',
            'description' => 'Some description',
            'type' => 'auto',
            'filters' => json_encode([
                'and' => [
                    [
                        'name' => 'title',
                        'path' => 'titles',
                        'field' => 'name',
                        'operator' => '=',
                        'criteria' => 'id',
                        'text' => 'Random Title',
                    ],
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

    public function testUserHasCharacterWithTitle()
    {
        // update an user to match criteria
        $reference_user = User::first();
        $reference_character = User::first()->characters->first();
        $reference_corporation = CorporationInfo::find($reference_character->affiliation->corporation_id);
        $reference_title = CorporationTitle::factory()->make([
            'name' => 'Random Title',
        ]);
        $reference_corporation->titles()->save($reference_title);
        $reference_character->titles()->attach($reference_title);

        // spawn test squad
        $squad = new Squad([
            'name' => 'Testing Squad',
            'description' => 'Some description',
            'type' => 'auto',
            'filters' => json_encode([
                'and' => [
                    [
                        'name' => 'title',
                        'path' => 'titles',
                        'field' => 'id',
                        'operator' => '=',
                        'criteria' => $reference_title->id,
                        'text' => 'Random Title',
                    ],
                ],
            ]),
        ]);

        // pickup users
        $users = User::all();

        // ensure no users are eligible
        foreach ($users as $user) {
            $user->id == $reference_user->id ?
                $this->assertTrue($squad->isUserEligible($user)) :
                $this->assertFalse($squad->isUserEligible($user));
        }
    }
}
