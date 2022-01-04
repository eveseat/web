<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2022 Leon Jacobs
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

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateUniverseMoonReportsTable.
 */
class CreateUniverseMoonReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('universe_moon_reports', function (Blueprint $table) {
            $table->integer('moon_id')->primary();
            $table->unsignedInteger('user_id');
            $table->timestamps();
        });

        DB::table('universe_moon_contents')
            ->leftJoin('moons', 'universe_moon_contents.moon_id', '=', 'moons.moon_id')
            ->whereNull('moons.moon_id')
            ->delete();

        DB::table('universe_moon_contents')
            ->select('moon_id')
            ->distinct()
            ->orderBy('moon_id')
            ->chunk(50, function ($moons) {
                $moons->each(function ($moon) {
                    DB::table('universe_moon_reports')
                        ->insert([
                            'moon_id' => $moon->moon_id,
                            'user_id' => 0,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                });
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('universe_moon_reports');
    }
}
