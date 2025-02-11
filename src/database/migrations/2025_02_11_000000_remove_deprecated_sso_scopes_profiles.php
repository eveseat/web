<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to present Leon Jacobs
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
use Illuminate\Support\Facades\DB;

class RemoveDeprecatedSsoScopesProfiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Extends https://github.com/eveseat/web/pull/698

        $remove_scopes = [
            'esi-bookmarks.read_character_bookmarks.v1',
            'esi-bookmarks.read_corporation_bookmarks.v1',
            'esi-characters.read_opportunities.v1',
        ];

        $sso_scopes_setting = DB::table('global_settings')->where('name', 'sso_scopes')->first();

        // if no sso scope settings have been configured, we don't have anything to migrate
        if($sso_scopes_setting === null) return;

        // Fix the global_settings
        $profiles = json_decode($sso_scopes_setting->value);
        foreach($profiles as &$profile) {
            foreach($remove_scopes as $rs) {
                foreach(array_keys($profile->scopes, $rs, true) as $key) {
                    unset($profile->scopes[$key]);
                }
                $profile->scopes = array_values($profile->scopes);
            }
        }
        DB::table('global_settings')
            ->where('name', 'sso_scopes')
            ->update(['value' => json_encode($profiles)]);
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
