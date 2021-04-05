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

namespace Seat\Web\Observers;

use Seat\Web\Models\Squads\SquadMember;
use Seat\Web\Models\Squads\SquadRole;

/**
 * Class SquadMemberObserver.
 *
 * @package Seat\Web\Observers
 */
class SquadMemberObserver
{
    /**
     * @param \Seat\Web\Models\Squads\SquadMember $member
     */
    public function created(SquadMember $member)
    {
        // retrieve roles from pivot
        $roles = SquadRole::where('squad_id', $member->squad_id)->get();

        // add squad roles to user
        $member->user->roles()->syncWithoutDetaching($roles->pluck('role_id'));
    }

    /**
     * @param \Seat\Web\Models\Squads\SquadMember $member
     */
    public function deleted(SquadMember $member)
    {
        // retrieve roles from pivot that need to be removed
        $roles = SquadRole::where('squad_id', $member->squad_id)
            ->whereNotIn('role_id', $member->user->squads->where('id', '<>', $member->squad_id)
                ->map(function ($squad) {
                    return $squad->roles->pluck('id');
                })->flatten()
            )
            ->get();

        // remove squad roles from user
        $member->user->roles()->detach($roles->pluck('role_id'));
    }
}
