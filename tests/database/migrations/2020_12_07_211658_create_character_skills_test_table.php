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
 * Class CreateCharacterSkillsTestTable.
 */
class CreateCharacterSkillsTestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('character_skills', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->bigInteger('character_id');
            $table->integer('skill_id');
            $table->integer('skillpoints_in_skill');
            $table->integer('trained_skill_level');
            $table->integer('active_skill_level');

            $table->timestamps();

            $table->index(['character_id'], 'character_skills_character_id_index');
            $table->index(['skill_id'], 'character_skills_skill_id_index');
            $table->unique(['character_id', 'skill_id'], 'character_skills_character_id_skill_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('character_skills');
    }
}
