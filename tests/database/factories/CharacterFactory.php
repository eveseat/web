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
use Seat\Eveapi\Models\Character\CharacterInfo;

/**
 * Class CharacterFactory.
 * @package Seat\Tests\Web\Database\Factories
 */
class CharacterFactory extends Factory
{
    /**
     * @return array
     */
    public function definition()
    {
        return [
            'character_id' => $this->faker->unique()->numberBetween(90000000, 90001000),
            'name' => $this->faker->name,
            'description' => $this->faker->sentences(5, true),
            'birthday' => $this->faker->dateTime(),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'race_id' => $this->faker->randomElement([1, 2, 4, 8]),
            'bloodline_id' => $this->faker->randomElement([1, 2, 3, 4, 5, 6, 7, 8, 11, 12, 13, 14]),
            'security_status' => $this->faker->randomFloat(2, -10, 10),
        ];
    }
}
