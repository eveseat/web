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

use Faker\Generator;
use Seat\Eveapi\Models\RefreshToken;

$factory->define(RefreshToken::class, function (Generator $faker) {
    return [
        'character_id'         => $faker->numberBetween(98000000, 98001794),
        'version'              => $faker->numberBetween(1, RefreshToken::CURRENT_VERSION),
        'user_id'              => $faker->numberBetween(1, 10),
        'refresh_token'        => $faker->sha256,
        'scopes'               => [
            'esi-characters.read_agents_research.v1', 'esi-characters.read_blueprints.v1',
            'esi-characters.read_fatigue.v1', ' esi-characters.read_medals.v1', 'esi-characters.read_notifications.v1',
        ],
        'expires_on'           => $faker->dateTime(),
        'token'                => $faker->sha256,
        'character_owner_hash' => $faker->sha256,
    ];
});
