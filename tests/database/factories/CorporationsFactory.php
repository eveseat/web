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

$factory->define(\Seat\Eveapi\Models\Corporation\CorporationInfo::class, function (Generator $faker) {

    return [
        'corporation_id'  => $faker->numberBetween(98000000, 98001794),
        'name'            => $faker->company,
        'ticker'          => $faker->currencyCode,
        'member_count'    => $faker->numberBetween(5, 200),
        'ceo_id'          => $faker->unique()->numberBetween(90000000, 90001000),
        'alliance_id'     => $faker->numberBetween(99000000, 99000010),
        'description'     => $faker->sentences(5, true),
        'tax_rate'        => $faker->randomFloat(2, 0, 1),
        'date_founded'    => $faker->dateTime(),
        'creator_id'      => $faker->unique()->numberBetween(90000000, 90001000),
        'url'             => $faker->url,
        'faction_id'      => $faker->randomElement([null, 500002, 500003, 500004]),
        'home_station_id' => $faker->numberBetween(60000004, 60015151),
        'shares'          => $faker->numberBetween(1),
    ];
});
