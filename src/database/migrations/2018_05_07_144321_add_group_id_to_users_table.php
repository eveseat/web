<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015, 2016, 2017, 2018  Leon Jacobs
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

class AddGroupIdToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (! Schema::hasColumn('users', 'group_id')) {

            Schema::table('users', function (Blueprint $table) {
                $table->integer('group_id')->after('id');

                $table->index('group_id');
            });

            if (Schema::hasTable('group_user')) {
                DB::table('group_user')->get()->each(function ($record) {

                    DB::table('users')->where('id', $record->user_id)
                        ->update(['group_id' => $record->group_id]);
                });
            }

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        if (! Schema::hasTable('group_user')) {
            Schema::create('group_user', function (Blueprint $table) {

                $table->bigInteger('user_id');
                $table->integer('group_id');

                $table->index('user_id');
                $table->index('group_id');

                $table->foreign('user_id')->references('id')
                    ->on('users')->onDelete('cascade');

            });
        }

        DB::table('users')->get()->each(function ($record) {

            DB::table('group_user')->insert([
                'user_id'  => $record->id,
                'group_id' => $record->group_id,
            ]);

        });

        Schema::table('users', function (Blueprint $table) {

            $table->dropIndex(['group_id']);
            $table->dropColumn('group_id');

        });
    }
}
