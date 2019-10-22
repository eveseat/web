<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015, 2016, 2017, 2018, 2019  Leon Jacobs
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
use Illuminate\Support\Facades\Schema;

class CreateCorporationInfosTestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('corporation_infos', function (Blueprint $table) {

            $table->bigInteger('corporation_id')->primary();
            $table->string('name');
            $table->string('ticker');
            $table->integer('member_count');
            $table->bigInteger('ceo_id');
            $table->integer('alliance_id')->nullable();
            $table->text('description')->nullable();
            $table->float('tax_rate');
            $table->dateTime('date_founded')->nullable();
            $table->bigInteger('creator_id');
            $table->string('url')->nullable();
            $table->integer('faction_id')->nullable();
            $table->integer('home_station_id')->nullable();
            $table->bigInteger('shares')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::dropIfExists('corporation_infos');
    }
}
