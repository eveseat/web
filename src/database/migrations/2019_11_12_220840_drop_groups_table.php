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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Class DropGroupsTable.
 */
class DropGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // drop admin account - if exists
        $entry = DB::table('users')
            ->where('name', 'admin')
            ->first();

        if ($entry) {
            DB::table('refresh_tokens')
                ->where('character_id', $entry->id)
                ->delete();

            DB::table('users')
                ->where('id', $entry->id)
                ->delete();
        }

        // drop erased tokens
        DB::table('refresh_tokens')
            ->whereNotNull('deleted_at')
            ->delete();

        ///
        /// Pre-check regarding users table structure and associated main_character
        ///

        // control regarding user structure
        $entries = DB::table('users')
            ->leftJoin('user_settings', function ($join) {
                $join->on('users.group_id', 'user_settings.group_id');
                $join->where('user_settings.name', 'main_character_id');
            })
            ->select('users.group_id', 'users.id', 'users.name', 'users.character_owner_hash', 'user_settings.value')
            ->get();

        foreach ($entries as $entry) {
            if (is_null($entry->group_id))
                throw new Exception(sprintf('User with id %s | name %s | hash %s has group_id unset.',
                    $entry->id, $entry->name, $entry->character_owner_hash));

            if (is_null($entry->character_owner_hash))
                throw new Exception(sprintf('User with id %s | name %s | group_id %s has character_owner_hash unset.',
                    $entry->id, $entry->name, $entry->group_id));

            if (is_null($entry->value))
                throw new Exception(sprintf('User with id %s | name %s | group_id %s has no main_character set.',
                    $entry->id, $entry->name, $entry->group_id));
        }

        // remove orphan settings
        DB::table('user_settings')
            ->whereNotIn('group_id', DB::table('groups')->select('id'))
            ->delete();

        // control regarding main_character
        $entries = DB::table('user_settings')
            ->where('name', 'main_character_id')
            ->select('value')
            ->selectRaw('COUNT(*) AS counter')
            ->groupBy('value')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        if ($entries->isNotEmpty())
            throw new Exception(sprintf('The following main_character are set to more than a single group: %s',
                implode(',', $entries->pluck('value')->flatten()->toArray())));

        ///
        /// Ensure compatibility level with third party structures
        ///

        // SeAT Calendar

        if (Schema::hasTable('calendar_attendees')) {
            Schema::table('calendar_attendees', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
            });
        }

        if (Schema::hasTable('calendar_operations')) {
            Schema::table('calendar_operations', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
            });
        }

        // Slack

        if (Schema::hasTable('slack_users')) {
            Schema::table('slack_users', function (Blueprint $table) {
                $table->dropForeign(['group_id']);
            });
        }

        if (Schema::hasTable('slack_channel_users')) {
            Schema::table('slack_channel_users', function (Blueprint $table) {
                $table->dropForeign(['group_id']);
            });
        }

        // SeAT Connector

        if (Schema::hasTable('seat_connector_users')) {
            Schema::table('seat_connector_users', function (Blueprint $table) {
                $table->dropForeign('fk_groups');
            });
        }

        ///
        /// Update tables
        ///

        // spawn a migration table which could be use by third party to upgrade their plugins
        // we will store original group_id, user_id and their attached character hash
        Schema::create('mig_groups', function (Blueprint $table) {
            $table->bigInteger('group_id');
            $table->bigInteger('old_user_id'); // this field was acting both as primary key, user ID and Character ID
            $table->integer('new_user_id')->nullable(); // this field will acting as a single UID
            $table->string('character_owner_hash');
            $table->bigInteger('main_character_id');
        });

        // generate initial migration table
        DB::table('mig_groups')
            ->insertUsing(
                ['group_id', 'old_user_id', 'character_owner_hash', 'main_character_id'],
                DB::table('users')
                    ->leftJoin('user_settings', function ($join) {
                        $join->on('users.group_id', 'user_settings.group_id');
                        $join->where('user_settings.name', 'main_character_id');
                    })
                    ->whereNotNull('user_settings.value')
                    ->select('users.group_id', 'users.id', 'users.character_owner_hash', 'user_settings.value'));

        // fetch duplicated users which are not related to main character
        $entries = DB::table('mig_groups')
            ->whereColumn('old_user_id', '<>', 'main_character_id')
            ->get();

        // drop duplicate account
        foreach ($entries as $entry) {
            DB::table('users')->where('id', $entry->old_user_id)
                ->delete();
        }

        // seed main_character_id field
        $entries = DB::table('mig_groups')->get();

        Schema::table('users', function (Blueprint $table) {
            $table->bigInteger('main_character_id')->after('remember_token')->nullable();
        });

        foreach ($entries as $entry) {
            DB::table('users')->where('id', $entry->old_user_id)
                ->update([
                    'main_character_id' => $entry->main_character_id,
                ]);
        }

        // spawn new users primary key and lock main_character_id field
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('id');
            $table->dropColumn('group_id');
            $table->dropColumn('character_owner_hash');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('id')->nullable()->first();
            $table->bigInteger('main_character_id')->nullable(false)->change();
        });

        // init auto-increment field (1 is lock for admin)
        DB::table('users')->insert([
            'id'                => 1,
            'name'              => 'admin',
            'active'            => true,
            'main_character_id' => 0,
            'created_at'        => carbon(),
            'updated_at'        => carbon(),
        ]);

        $id = 0;
        DB::table('users')->get()->each(function ($user) use (&$id) {
            //$id++;
            DB::table('users')
                ->where('main_character_id', $user->main_character_id)
                ->update([
                    'id' => ++$id,
                ]);
        });

        // switch id field from simple integer to auto-increment field
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('id')->nullable(false)->change();
            $table->primary('id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->increments('id')->change();
        });

        // update migration table with new user ID
        $entries = DB::table('users')
            ->select('id', 'main_character_id')
            ->get();

        foreach ($entries as $entry) {
            DB::table('mig_groups')
                ->where('main_character_id', $entry->main_character_id)
                ->update([
                    'new_user_id' => $entry->id,
                ]);
        }

        ///
        /// update electrons tables
        ///

        Schema::table('refresh_tokens', function (Blueprint $table) {
            $table->integer('user_id')->after('character_id')->nullable();
            $table->string('character_owner_hash')->after('token')->nullable();
        });

        Schema::table('user_settings', function (Blueprint $table) {
            $table->integer('user_id')->after('id')->nullable();
        });

        $entries = DB::table('mig_groups')->get();

        foreach ($entries as $entry) {

            // upgrade refresh_tokens
            DB::table('refresh_tokens')
                ->where('character_id', $entry->old_user_id)
                ->update([
                    'user_id' => $entry->new_user_id,
                    'character_owner_hash' => $entry->character_owner_hash,
                ]);

            // convert user_settings
            DB::table('user_settings')
                ->where('group_id', $entry->group_id)
                ->update([
                    'user_id' => $entry->new_user_id,
                ]);

            // convert user_login_histories
            DB::table('user_login_histories')
                ->where('user_id', $entry->old_user_id)
                ->update([
                    'user_id' => $entry->new_user_id,
                ]);

            // convert security_logs data
            DB::table('security_logs')
                ->where('user_id', $entry->old_user_id)
                ->update([
                    'user_id' => $entry->new_user_id,
                ]);
        }

        DB::table('refresh_tokens')
            ->whereNull('user_id')
            ->delete();

        // lock user_id and character_owner_hash fields
        Schema::table('refresh_tokens', function (Blueprint $table) {
            $table->integer('user_id')->nullable(false)->change();
            $table->string('character_owner_hash')->nullable(false)->change();
        });

        DB::table('user_settings')
            ->whereNull('user_id')
            ->delete();

        // lock user_id and drop group_id
        Schema::table('user_settings', function (Blueprint $table) {
            $table->dropColumn('group_id');
            $table->integer('user_id')->nullable(false)->change();
        });

        Schema::dropIfExists('groups');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
