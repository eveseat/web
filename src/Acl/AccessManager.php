<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2020 Leon Jacobs
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

use Seat\Web\Events\UserGroupRoleAdded;
use Seat\Web\Events\UserGroupRoleRemoved;
use Seat\Web\Models\Acl\Affiliation as AffiliationModel;
use Seat\Web\Models\Acl\Permission as PermissionModel;
use Seat\Web\Models\Acl\Role as RoleModel;
use Seat\Web\Models\Group;

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

        $roles = RoleModel::with('permissions', 'groups', 'affiliations');

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
     * @param       $role_id
     * @param array $permissions
     * @param bool  $inverse
     */
    public function giveRolePermissions($role_id, array $permissions, bool $inverse)
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
     * Give an array of group_ids a role.
     *
     * @param array $group_ids
     * @param int   $role_id
     */
    public function giveGroupsRole(array $group_ids, int $role_id)
    {

        foreach ($group_ids as $group_id) {

            $group = Group::where('id', $group_id)->first();
            $this->giveGroupRole($group->id, $role_id);
        }
    }

    /**
     * Give a group a Role.
     *
     * @param int $group_id
     * @param int $role_id
     */
    public function giveGroupRole(int $group_id, int $role_id)
    {

        $group = Group::find($group_id);
        $role = RoleModel::firstOrNew(['id' => $role_id]);

        // If the role does not already have the group add it.
        if (! $role->groups->contains($group_id)) {
            $role->groups()->save($group);
            event(new UserGroupRoleAdded($group_id, $role));
        }
    }

    /**
     * Remove a group from a role.
     *
     * @param int $group_id
     * @param int $role_id
     */
    public function removeGroupFromRole(int $group_id, int $role_id)
    {

        $role = $this->getRole($role_id);
        if ($role->groups()->detach($group_id) > 0)
            event(new UserGroupRoleRemoved($group_id, $role));

    }

    /**
     * @param int   $role_id
     * @param array $affiliations
     * @param bool  $inverse
     */
    public function giveRoleCorporationAffiliations(int $role_id, array $affiliations, bool $inverse)
    {

        foreach ($affiliations as $affiliation)
            $this->giveRoleCorporationAffiliation($role_id, $affiliation, $inverse);

    }

    /**
     * @param int  $role_id
     * @param int  $corporation_id
     * @param bool $inverse
     */
    public function giveRoleCorporationAffiliation(int $role_id, int $corporation_id, bool $inverse)
    {

        $role = $this->getRole($role_id);

        $affiliation = AffiliationModel::firstOrNew([
            'affiliation' => $corporation_id,
            'type'        => 'corp',
        ]);

        if (! $role->affiliations->contains($affiliation))
            $role->affiliations()->save($affiliation, ['not' => $inverse]);

    }

    /**
     * @param int   $role_id
     * @param array $affiliations
     * @param bool  $inverse
     */
    public function giveRoleCharacterAffiliations(int $role_id, array $affiliations, bool $inverse)
    {

        foreach ($affiliations as $affiliation)
            $this->giveRoleCharacterAffiliation($role_id, $affiliation, $inverse);

    }

    /**
     * @param int  $role_id
     * @param int  $character_id
     * @param bool $inverse
     */
    public function giveRoleCharacterAffiliation(int $role_id, int $character_id, bool $inverse)
    {

        $role = $this->getRole($role_id);

        $affiliation = AffiliationModel::firstOrNew([
            'affiliation' => $character_id,
            'type'        => 'char',
        ]);

        if (! $role->affiliations->contains($affiliation))
            $role->affiliations()->save($affiliation, ['not' => $inverse]);
    }

    /**
     * @param int $role_id
     * @param int $affiliation_id
     */
    public function removeAffiliationFromRole(int $role_id, int $affiliation_id)
    {

        $role = $this->getRole($role_id);
        $role->affiliations()->detach($affiliation_id);

    }
}
