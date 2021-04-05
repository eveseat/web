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

namespace Seat\Web\Acl\Policies;

use Seat\Web\Models\Squads\Squad;
use Seat\Web\Models\User;

/**
 * Class SquadPolicy.
 *
 * @package Seat\Web\Acl\Policies
 */
class SquadPolicy
{
    /**
     * @param \Seat\Web\Models\User $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * @param \Seat\Web\Models\User $user
     * @return bool
     */
    public function edit(User $user, Squad $squad)
    {
        return $squad->moderators->where('id', $user->id)->isNotEmpty();
    }

    /**
     * @param \Seat\Web\Models\User $user
     * @param \Seat\Web\Models\Squads\Squad $squad
     * @return bool
     */
    public function delete(User $user, Squad $squad)
    {
        return $user->isAdmin();
    }

    /**
     * @param \Seat\Web\Models\User $user
     * @param \Seat\Web\Models\Squads\Squad $squad
     * @param \Seat\Web\Models\User $member
     * @return bool
     */
    public function kick(User $user, Squad $squad, User $member)
    {
        return $user->id !== $member->id && $squad->moderators->where('id', $user->id)->isNotEmpty();
    }

    /**
     * @param \Seat\Web\Models\User $user
     * @param \Seat\Web\Models\Squads\Squad $squad
     * @return bool
     */
    public function manage_candidates(User $user, Squad $squad)
    {
        return $squad->type == 'manual' && $squad->moderators->where('id', $user->id)->isNotEmpty();
    }

    /**
     * @param \Seat\Web\Models\User $user
     * @param \Seat\Web\Models\Squads\Squad $squad
     * @return bool
     */
    public function manage_members(User $user, Squad $squad)
    {
        return $squad->moderators->where('id', $user->id)->isNotEmpty();
    }

    /**
     * @param \Seat\Web\Models\User $user
     * @param \Seat\Web\Models\Squads\Squad $squad
     * @return bool
     */
    public function manage_moderators(User $user, Squad $squad)
    {
        return $user->isAdmin();
    }

    /**
     * @param \Seat\Web\Models\User $user
     * @param \Seat\Web\Models\Squads\Squad $squad
     * @return bool
     */
    public function manage_roles(User $user, Squad $squad)
    {
        return $user->isAdmin();
    }

    /**
     * @param \Seat\Web\Models\User $user
     * @param \Seat\Web\Models\Squads\Squad $squad
     * @return bool
     */
    public function show_members(User $user, Squad $squad)
    {
        return ($squad->members->where('id', $user->id)->isNotEmpty() &&
            ! $squad->is_classified) ||
            $squad->moderators->where('id', $user->id)->isNotEmpty();
    }

    /**
     * @param \Seat\Web\Models\User $user
     * @param string $ability
     * @return bool|void
     */
    public function before(User $user, string $ability)
    {
        if ($user->isAdmin())
            return true;
    }
}
