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
use Seat\Eveapi\Models\RefreshToken;
use Seat\Web\Models\Squads\Squad;
use Seat\Web\Models\User;
use Seat\Web\WebServiceProvider;

/**
 * Class FactionRuleTest.
 *
 * @package Seat\Tests\Web\Squads
 */
class FactionRuleTest extends TestCase
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
            ->each(function($character) {
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

    public function testUserHasNoCharacterInFaction()
    {
        // spawn test squad
        $squad = new Squad([
            'name' => 'Testing Squad',
            'description' => 'Some description',
            'type' => 'auto',
            'filters' => json_encode([
                'and' => [
                    [
                        'name' => 'faction',
                        'path' => 'affiliation',
                        'field' => 'faction_id',
                        'operator' => '=',
                        'criteria' => 500001,
                        'text' => 'Random Faction',
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

    public function testUserHasCharacterInFaction()
    {
        // spawn test squad
        $squad = new Squad([
            'name' => 'Testing Squad',
            'description' => 'Some description',
            'type' => 'auto',
            'filters' => json_encode([
                'and' => [
                    [
                        'name' => 'faction',
                        'path' => 'affiliation',
                        'field' => 'faction_id',
                        'operator' => '=',
                        'criteria' => 500001,
                        'text' => 'Random Faction',
                    ],
                ],
            ]),
        ]);

        // update an user to match criteria
        $reference_user = User::first();
        $reference_user->characters->first()->affiliation->update(['faction_id' => 500001]);

        $users = User::all();

        foreach ($users as $user) {
            $user->id == $reference_user->id ?
                $this->assertTrue($squad->isUserEligible($user)) :
                $this->assertFalse($squad->isUserEligible($user));
        }
    }

    /**
     * This test checks whether a character from a corp outside an alliance is eligible for a squad with a alliance is not filter.
     * In SeAT 4, this was not working properly
     */
    public function testCharacterHasNoFactionWithFactionIsNotFilter(){
        $squad = new Squad([
            'name' => 'Testing Squad',
            'description' => 'Some description',
            'type' => 'auto',
            'filters' => json_encode([
                'and' => [
                    [
                        'name' => 'faction',
                        'path' => 'affiliation',
                        'field' => 'faction_id',
                        'operator' => '<>',
                        'criteria' => 500001,
                        'text' => 'Random Faction',
                    ],
                ],
            ]),
        ]);

        $user = User::first();

        $user->characters->each(function ($character){
            $character->affiliation->update([
                'faction_id' => 500001,
            ]);
        });
        $this->assertFalse($squad->isUserEligible($user));

        $user->characters->each(function ($character){
            $character->affiliation->update([
                'faction_id' => null,
            ]);
        });
        $this->assertTrue($squad->isUserEligible($user));
    }
}
