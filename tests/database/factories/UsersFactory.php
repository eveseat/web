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

namespace Seat\Tests\Web\Database\Factories;

use Faker\Generator;
use Illuminate\Database\Eloquent\Factories\Factory;
use Seat\Web\Models\User;

/**
 * Class UsersFactory.
 * @package Seat\Tests\Web\Database\Factories
 */
class UsersFactory extends Factory
{
    /**
     * @var int
     */
    static int $id = 2; // exclude 1 which should be admin

    /**
     * @return array
     */
    public function definition()
    {
        return [
            'id' => self::$id++,
            'name' => $this->faker->name,
            'active' => $this->faker->boolean,
            'admin' => false,
            'last_login' => $this->faker->dateTime(),
            'last_login_source' => $this->faker->ipv4,
            'remember_token' => $this->faker->sha256,
            'main_character_id' => $this->faker->unique()->numberBetween(90000000, 90001000),
        ];
    }
}
