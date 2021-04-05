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

use Seat\Web\Events\UserRoleAdded;
use Seat\Web\Events\UserRoleRemoved;
use Seat\Web\Models\Acl\Permission as PermissionModel;
use Seat\Web\Models\Acl\Role as RoleModel;
use Seat\Web\Models\User;

/**
 * Class AccessManager.
 * @package Seat\Web\Acl
 */
trait AccessManager
{
    /**
     * Return everything related to the Role
     * with eager loading.
     *
     * @param int $role_id
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Collection
     */
    public function getCompleteRole(int $role_id = null)
    {

        $roles = RoleModel::with('permissions', 'users');

        if (! is_null($role_id)) {

            $roles = $roles->where('id', $role_id)->first();

            if (! $roles) abort(404);

            return $roles;
        }

        return $roles->get();
    }

    /**
     * Add a new role.
     *
     * @param string $title
     *
     * @return \Seat\Web\Models\Acl\Role
     */
    public function addRole(string $title): RoleModel
    {

        return RoleModel::create([
            'title' => $title,
        ]);

    }

    /**
     * Remove a role by title.
     *
     * @param string $title
     */
    public function removeRoleByTitle(string $title)
    {

        $role = RoleModel::where('title', $title)->first();
        $this->removeRole($role->id);
    }

    /**
     * Remove a role.
     *
     * @param int $id
     *
     * @return int
     */
    public function removeRole(int $id): int
    {

        return RoleModel::destroy($id);
    }

    /**
     * Give a role many permissions.
     *
     * @param int   $role_id
     * @param array $permissions
     * @param bool  $inverse
     */
    public function giveRolePermissions(int $role_id, array $permissions, bool $inverse)
    {

        foreach ($permissions as $key => $permission_name)
            $this->giveRolePermission($role_id, $permission_name, $inverse);

    }

    /**
     * Give a Role a permission.
     *
     * @param int    $role_id
     * @param string $permission_name
     * @param bool   $inverse
     */
    public function giveRolePermission(int $role_id, string $permission_name, bool $inverse)
    {

        $role = $this->getRole($role_id);

        $permission = PermissionModel::firstOrNew([
            'title' => $permission_name,
        ]);

        // If the role does not already have the permission
        // add it. We will also apply the inverse rule as an
        // extra attribute on save()
        if (! $role->permissions->contains($permission->id))
            $role->permissions()->save($permission, ['not' => $inverse]);

    }

    /**
     * Get a role.
     *
     * @param int $id
     *
     * @return \Seat\Web\Models\Acl\Role
     */
    public function getRole(int $id): RoleModel
    {

        return RoleModel::findOrFail($id);
    }

    /**
     * Remove a permission from a Role.
     *
     * @param int $permission_id
     * @param int $role_id
     */
    public function removePermissionFromRole(int $permission_id, int $role_id)
    {

        $role = $this->getRole($role_id);
        $role->permissions()->detach($permission_id);

    }

    /**
     * Give an array of user_ids a role.
     *
     * @param array $user_ids
     * @param int $role_id
     */
    public function giveUsersRole(array $user_ids, int $role_id)
    {
        foreach ($user_ids as $user_id) {
            $this->giveUserRole($user_id, $role_id);
        }
    }

    /**
     * Give to an user a Role.
     *
     * @param int $user_id
     * @param int $role_id
     */
    public function giveUserRole(int $user_id, int $role_id)
    {
        $user = User::find($user_id);
        $role = RoleModel::firstOrNew(['id' => $role_id]);

        // If the role does not already have the user, add it.
        if (! $role->users->contains($user_id)) {
            $role->users()->save($user);
            event(new UserRoleAdded($user_id, $role));
        }
    }

    /**
     * Remove an user from a role.
     *
     * @param int $user_id
     * @param int $role_id
     */
    public function removeUserFromRole(int $user_id, int $role_id)
    {
        $role = $this->getRole($role_id);
        if ($role->users()->detach($user_id) > 0)
            event(new UserRoleRemoved($user_id, $role));
    }
}
