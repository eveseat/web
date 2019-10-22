<?php
/*
This file is part of SeAT

Copyright (C) 2015, 2016, 2017, 2018, 2019  Leon Jacobs

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

use Faker\Generator;

$factory->define(\Seat\Eveapi\Models\Character\CharacterInfo::class, function (Generator $faker) {

    return [
        'character_id'    => $faker->unique()->numberBetween(90000000, 90001000),
        'name'            => $faker->name,
        'description'     => $faker->sentences(5, true),
        'corporation_id'  => $faker->numberBetween(98000000, 98001794),
        'alliance_id'     => $faker->numberBetween(99000000, 99000010),
        'birthday'        => $faker->dateTime(),
        'gender'          => $faker->randomElement(['male', 'female']),
        'race_id'         => $faker->randomElement([1, 2, 4, 8]),
        'bloodline_id'    => $faker->randomElement([1, 2, 3, 4, 5, 6, 7, 8, 11, 12, 13, 14]),
        'ancestry_id'     => $faker->randomElement([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42]),
        'security_status' => $faker->randomFloat(2, -10, 10),
        'faction_id'      => $faker->randomElement([null, 500002, 500003, 500004]),
    ];
});
