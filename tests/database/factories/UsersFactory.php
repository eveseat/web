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
use Seat\Web\Models\User;

$factory->define(User::class, function (Generator $faker) {
    // exclude 1 which should be admin
    static $id = 2;

    return [
        'id'                   => $id++,
        'name'                 => $faker->name,
        'active'               => $faker->boolean,
        'admin'                => false,
        'last_login'           => $faker->dateTime(),
        'last_login_source'    => $faker->ipv4,
        'remember_token'       => $faker->sha256,
        'main_character_id'    => $faker->unique()->numberBetween(90000000, 90001000),
    ];
});
