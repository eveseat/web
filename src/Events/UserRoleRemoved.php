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

namespace Seat\Web\Events;

use Illuminate\Queue\SerializesModels;
use Seat\Web\Models\Acl\Role;

/**
 * Class UserRoleRemoved.
 *
 * @package Seat\Web\Events
 */
class UserRoleRemoved
{
    use SerializesModels;

    /**
     * @var int
     */
    public $user_id;

    /**
     * @var Role
     */
    public $role;

    /**
     * UserRoleRemoved constructor.
     *
     * @param  int  $user_id
     * @param  \Seat\Web\Models\Acl\Role  $role
     */
    public function __construct(int $user_id, Role $role)
    {
        $this->user_id = $user_id;
        $this->role = $role;
    }
}
