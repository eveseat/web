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

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateAlliancesTestTable.
 */
class CreateAlliancesTestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alliances', function (Blueprint $table) {
            $table->integer('alliance_id');
            $table->string('name')->nullable();
            $table->bigInteger('creator_id')->nullable();
            $table->bigInteger('creator_corporation_id')->nullable();
            $table->string('ticker')->nullable();
            $table->bigInteger('executor_corporation_id')->nullable();
            $table->timestamp('date_founded')->useCurrent();
            $table->integer('faction_id')->nullable();

            $table->primary('alliance_id');
            $table->index('creator_id');
            $table->index('creator_corporation_id');
            $table->index('executor_corporation_id');
            $table->index('faction_id');

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
        Schema::dropIfExists('alliances');
    }
}
