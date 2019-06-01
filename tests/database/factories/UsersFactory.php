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

$factory->define(\Seat\Web\Models\User::class, function (Generator $faker) {

    return [
        'id'                   => $faker->unique()->numberBetween(90000000, 90001000),
        'group_id'             => $faker->numberBetween(1, 10),
        'name'                 => $faker->name,
        'active'               => $faker->boolean,
        'character_owner_hash' => $faker->sha256,
        'last_login'           => $faker->dateTime(),
        'last_login_source'    => $faker->ipv4,
        'remember_token'       => $faker->sha256,
    ];

});
