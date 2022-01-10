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

namespace Seat\Web\Acl\Policies;

use Seat\Web\Acl\Response;
use Seat\Web\Models\User;

/**
 * Class GlobalPolicy.
 *
 * @package Seat\Web\Acl\Policies
 */
class GlobalPolicy extends AbstractPolicy
{
    /**
     * @param  string  $method
     * @param  array  $args
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function __call(string $method, array $args)
    {
        if (count($args) < 1)
            return false;

        $user = $args[0];

        $message = sprintf('Request to %s was denied. The permission required is %s', request()->path(), $this->ability);

        return $this->userHasPermission($user, $this->ability, function () use ($user) {

            // retrieve defined authorization for the requested user
            $acl = $this->permissionsFrom($user);

            // filter out roles without required permission
            $permissions = $acl->filter(function ($permission) {
                return $permission->title === $this->ability;
            });

            // if we have at least one valid permission - grant access
            return $permissions->isNotEmpty();
        }) ? Response::allow() : Response::deny($message);
    }

    /**
     * @param  \Seat\Web\Models\User  $user
     * @return \Illuminate\Auth\Access\Response
     */
    public function superuser(User $user)
    {
        $message = sprintf('Request to %s was denied. The permission required is %s', request()->path(), $this->ability);

        return $user->isAdmin() ? Response::allow() : Response::deny($message);
    }
}
