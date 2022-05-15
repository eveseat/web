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
use Seat\Eveapi\Models\Alliances\Alliance;

$factory->define(Alliance::class, function (Generator $faker) {
    return [
        'alliance_id'               => $faker->numberBetween(99000000, 99010010),
        'name'                      => $faker->company,
        'creator_id'                => $faker->unique()->numberBetween(90000000, 90001000),
        'creator_corporation_id'    => $faker->numberBetween(98000000, 98001794),
        'ticker'                    => $faker->currencyCode,
        'executor_corporation_id'   => $faker->numberBetween(98000000, 98001794),
        'date_founded'              => $faker->dateTime(),
        'faction_id'                => $faker->randomElement([null, 500002, 500003, 500004]),
    ];
});
