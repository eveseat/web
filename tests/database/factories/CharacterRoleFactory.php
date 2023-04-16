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
use Seat\Eveapi\Models\Character\CharacterRole;

/**
 * Class CharacterRoleFactory.
 * @package Seat\Tests\Web\Database\Factories
 */
class CharacterRoleFactory extends Factory
{
    /**
     * @return array
     */
    public function definition()
    {
        $roles = [];

        for ($i = 1; $i <= 500; $i++) {
            $roles[] = 'role_' . $i;
        }

        return [
            'role' => $this->faker->unique()->randomElement($roles),
            'scope' => $this->faker->randomElement(['roles', 'roles_at_hq', 'roles_at_base', 'roles_at_other']),
        ];
    }
}
