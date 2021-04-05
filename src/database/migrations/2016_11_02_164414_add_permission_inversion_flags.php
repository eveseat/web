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

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPermissionInversionFlags extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('group_role', function (Blueprint $table) {

            $table->boolean('not')->after('group_id')->default(false);
        });

        Schema::table('permission_role', function (Blueprint $table) {

            $table->boolean('not')->after('role_id')->default(false);
        });

        Schema::table('affiliation_user', function (Blueprint $table) {

            $table->boolean('not')->after('affiliation_id')->default(false);
        });

        Schema::table('affiliation_role', function (Blueprint $table) {

            $table->boolean('not')->after('affiliation_id')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('group_role', function (Blueprint $table) {

            $table->dropColumn('not');
        });

        Schema::table('permission_role', function (Blueprint $table) {

            $table->dropColumn('not');
        });

        Schema::table('affiliation_user', function (Blueprint $table) {

            $table->dropColumn('not');
        });

        Schema::table('affiliation_role', function (Blueprint $table) {

            $table->dropColumn('not');
        });
    }
}
