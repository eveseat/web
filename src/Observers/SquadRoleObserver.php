<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2022 Leon Jacobs
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

use Seat\Web\Models\Squads\Squad;
use Seat\Web\Models\Squads\SquadRole;

/**
 * Class SquadRoleObserver.
 *
 * @package Seat\Web\Observers
 */
class SquadRoleObserver
{
    /**
     * @param  \Seat\Web\Models\Squads\SquadRole  $squad_role
     */
    public function created(SquadRole $squad_role)
    {
        // retrieve squad members from pivot
        $squad = Squad::with('members')->find($squad_role->squad_id);

        // add new role to each squad members
        $squad->members->each(function ($member) use ($squad_role) {
            $member->roles()->syncWithoutDetaching([$squad_role->role_id]);
        });
    }

    /**
     * @param  \Seat\Web\Models\Squads\SquadRole  $squad_role
     */
    public function deleted(SquadRole $squad_role)
    {
        // retrieve squad members from pivot
        $squad = Squad::with('members')->find($squad_role->squad_id);

        // remove role from each squad member
        $squad->members->each(function ($member) use ($squad_role) {
            $member->roles()->detach($squad_role->role_id);
        });
    }
}
