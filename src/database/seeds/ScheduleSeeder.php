<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2021 Leon Jacobs
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

namespace Seat\Web\database\seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class ScheduleSeeder.
 *
 * @package Seat\Web\database\seeds
 */
class ScheduleSeeder extends Seeder
{
    /**
     * @var array
     */
    protected $schedules = [
        [   // Horizon Metrics | Every Five Minutes
            'command'           => 'horizon:snapshot',
            'expression'        => '*/5 * * * *',
            'allow_overlap'     => false,
            'allow_maintenance' => false,
            'ping_before'       => null,
            'ping_after'        => null,
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Check if we have the schedules, else,
        // insert them
        foreach ($this->schedules as $job) {
            if (DB::table('schedules')->where('command', $job['command'])->exists()) {
                DB::table('schedules')->where('command', $job['command'])->update([
                    'expression' => $job['expression'],
                ]);
            } else {
                DB::table('schedules')->insert($job);
            }
        }
    }
}
