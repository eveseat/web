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
use Seat\Web\Models\Squads\Squad;
use Seat\Web\Models\User;

/**
 * Class UpgradeSquadsMaj4Min4Hf2.
 */
class UpgradeSquadsMaj4Min4Hf2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Squad::where('type', 'auto')->get()->each(function ($squad) {
            User::chunk(100, function ($users) use ($squad) {
                $users->each(function ($user) use ($squad) {
                    $is_member = $squad->members()->where('id', $user->id)->exists();

                    if ($is_member && ! $squad->isEligible($user))
                        $squad->members()->detach($user->id);

                    if (! $is_member && $squad->isEligible($user))
                        $squad->members()->attach($user->id);
                });
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

    }
}
