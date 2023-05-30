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
use Seat\Eveapi\Models\Corporation\CorporationInfo;

/**
 * Class CorporationFactory.
 * @package Seat\Tests\Web\Database\Factories
 */
class CorporationFactory extends Factory
{
    /**
     * @return array
     */
    public function definition()
    {
        return [
            'corporation_id' => $this->faker->numberBetween(98000000, 98001794),
            'name' => $this->faker->company,
            'ticker' => $this->faker->currencyCode,
            'member_count' => $this->faker->numberBetween(5, 200),
            'ceo_id' => $this->faker->unique()->numberBetween(90000000, 90001000),
            'alliance_id' => $this->faker->numberBetween(99000000, 99000010),
            'description' => $this->faker->sentences(5, true),
            'tax_rate' => $this->faker->randomFloat(2, 0, 1),
            'date_founded' => $this->faker->dateTime(),
            'creator_id' => $this->faker->unique()->numberBetween(90000000, 90001000),
            'url' => $this->faker->url,
            'faction_id' => $this->faker->randomElement([null, 500002, 500003, 500004]),
            'home_station_id' => $this->faker->numberBetween(60000004, 60015151),
            'shares' => $this->faker->numberBetween(1),
        ];
    }
}
