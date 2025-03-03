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

namespace Seat\Web\Listeners;

use Seat\Eveapi\Models\RefreshToken;
use Seat\Web\Events\CharacterFilterDataUpdate;
use Seat\Web\Models\Squads\Squad;

class CharacterFilterDataUpdatedSquads
{
    public static function handle(CharacterFilterDataUpdate $event)
    {
        $user = RefreshToken::where('character_id', $event->character->character_id)->withTrashed()->first()?->user()->with('squads')->first();
        if (! $user)
            return;

        $member_squads = $user->squads;

        // retrieve all auto squads from which the user is not already a member.
        $other_squads = Squad::where('type', 'auto')->whereDoesntHave('members', function ($query) use ($user) {
            $query->where('id', $user->id);
        })->get();

        // remove the user from squads to which he's non longer eligible.
        $member_squads->each(function (Squad $squad) use ($user) {
            if (! $squad->isUserEligible($user))
                $squad->members()->detach($user->id);
        });

        // add the user to squads from which he's not already a member.
        $other_squads->each(function (Squad $squad) use ($user) {
            if ($squad->isUserEligible($user))
                $squad->members()->save($user);
        });
    }
}
