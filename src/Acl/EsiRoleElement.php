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

namespace Seat\Web\Acl;

class EsiRoleElement
{
    /**
     * @var string
     */
    private $esi_role_name;

    /**
     * @var array
     */
    private $seat_permissions;

    /**
     * EsiRoleElement constructor.
     *
     * @param string $role_name
     * @param array $permissions
     */
    public function __construct(string $role_name, array $permissions)
    {
        $this->esi_role_name = $role_name;
        $this->seat_permissions = $permissions;
    }

    /**
     * Return the role name to which the element is referring.
     *
     * @return string
     */
    public function role(): string
    {
        return $this->esi_role_name;
    }

    /**
     * Return the list of SeAT permissions associated to the EVE role.
     *
     * @return array
     */
    public function permissions(): array
    {
        return $this->seat_permissions;
    }

    /**
     * Add the specified SeAT permission to the permissions list.
     *
     * @param string $permission
     * @return array
     */
    public function add(string $permission)
    {
        return $this->seat_permissions = array_merge($this->seat_permissions, [$permission]);
    }

    /**
     * Remove the specified SeAT permission from the permissions list.
     *
     * @param string $permission
     */
    public function remove(string $permission)
    {
        $this->seat_permissions = array_diff($this->seat_permissions, [$permission]);
    }
}
