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
use Seat\Eveapi\Models\Assets\CharacterAsset;
use Seat\Eveapi\Models\Character\CharacterAffiliation;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\RefreshToken;
use Seat\Web\Models\Squads\Squad;
use Seat\Web\Models\User;
use Seat\Web\WebServiceProvider;

/**
 * Class ItemRule.
 *
 * @package Seat\Tests\Web\Squads
 */
class ItemRuleTest extends TestCase
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

        Event::fake();

        factory(CharacterInfo::class, 50)
            ->create()
            ->each(function($character) {
                $character->affiliation()->save(factory(CharacterAffiliation::class)->make());

                //for ($i = 0; $i < 10; $i++)
                $character->assets()->saveMany(factory(CharacterAsset::class, 10)->make());
            });

        factory(User::class, 10)
            ->create()
            ->each(function ($user) {
                CharacterInfo::whereDoesntHave('refresh_token')->get()
                    ->random(rand(1, 5))->each(function ($character) use ($user) {
                        factory(RefreshToken::class)->create([
                            'character_id' => $character->character_id,
                            'user_id' => $user->id,
                        ]);
                    });
            });
    }

    public function testUserHasNoCharacterWithItem()
    {
        // spawn test squad
        $squad = new Squad([
            'name' => 'Testing Squad',
            'description' => 'Some description',
            'type' => 'auto',
            'filters' => json_encode([
                'and' => [
                    [
                        'name' => 'type',
                        'path' => 'characters.assets',
                        'field' => 'type_id',
                        'operator' => '=',
                        'criteria' => 2160,
                        'text' => 'Random Item',
                    ],
                ],
            ]),
        ]);

        // pickup users
        $users = User::all();

        // ensure no users are eligible
        foreach ($users as $user) {
            $this->assertFalse($squad->isEligible($user));
        }
    }

    public function testUserHasCharacterWithItem()
    {
        // spawn test squad
        $squad = new Squad([
            'name' => 'Testing Squad',
            'description' => 'Some description',
            'type' => 'auto',
            'filters' => json_encode([
                'and' => [
                    [
                        'name' => 'type',
                        'path' => 'characters.assets',
                        'field' => 'type_id',
                        'operator' => '=',
                        'criteria' => 2160,
                        'text' => 'Random Item',
                    ],
                ],
            ]),
        ]);

        // update an user to match criteria
        $reference_user = User::first();
        $reference_user->characters->first()->assets->first()->update(['type_id' => 2160]);

        $users = User::all();

        foreach ($users as $user) {
            $user->id == $reference_user->id ?
                $this->assertTrue($squad->isEligible($user)) :
                $this->assertFalse($squad->isEligible($user));
        }
    }
}
