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

namespace Seat\Web\Http\Controllers\Configuration;

use Seat\Services\Repositories\Character\Character;
use Seat\Services\Repositories\Configuration\UserRespository;
use Seat\Services\Repositories\Corporation\Corporation;
use Seat\Web\Acl\AccessManager;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\Validation\Role;
use Seat\Web\Http\Validation\RoleAffilliation;
use Seat\Web\Http\Validation\RoleGroup;
use Seat\Web\Http\Validation\RolePermission;

/**
 * Class AccessController.
 * @package Seat\Web\Http\Controllers\Configuration
 */
class AccessController extends Controller
{
    use AccessManager, UserRespository, Character, Corporation;

    /**
     * @return \Illuminate\View\View
     */
    public function getAll()
    {

        $roles = $this->getCompleteRole();

        return view('web::configuration.access.list', compact('roles'));
    }

    /**
     * @param \Seat\Web\Http\Validation\Role $request
     *
     * @return mixed
     */
    public function newRole(Role $request)
    {

        $role = $this->addRole($request->input('title'));

        return redirect()
            ->route('configuration.access.roles.edit', ['id' => $role->id])
            ->with('success', trans('web::seat.role_added'));
    }

    /**
     * @param $role_id
     *
     * @return mixed
     */
    public function deleteRole($role_id)
    {

        $this->removeRole($role_id);

        return redirect()->back()
            ->with('success', trans('web::seat.role_removed'));
    }

    /**
     * @param $role_id
     *
     * @return \Illuminate\View\View
     */
    public function editRole($role_id)
    {

        // Get the role. We don't get the full one
        // as we need to mangle some of the data to
        // arrays for easier processing in the view
        $role = $this->getRole($role_id);

        $role_permissions = $role->permissions()->get()->pluck('title')->toArray();
        $role_affiliations = $role->affiliations();
        $role_groups = $role->groups()->get()->pluck('id')->toArray();
        $all_groups = $this->getAllGroups();
        $all_characters = $this->getAllCharacters();
        $all_corporations = $this->getAllCorporations();

        return view(
            'web::configuration.access.edit',
            compact(
                'role', 'role_permissions', 'role_groups', 'all_groups',
                'role_affiliations', 'all_characters', 'all_corporations'
            ));
    }

    /**
     * @param \Seat\Web\Http\Validation\RolePermission $request
     *
     * @return mixed
     */
    public function grantPermissions(RolePermission $request)
    {

        $this->giveRolePermissions(
            $request->input('role_id'),
            $request->input('permissions'),
            $request->input('inverse') ? true : false);

        return redirect()->back()
            ->with('success', trans('web::seat.permissions_granted'));
    }

    /**
     * @param $role_id
     * @param $permission_id
     *
     * @return mixed
     */
    public function removePermissions($role_id, $permission_id)
    {

        $this->removePermissionFromRole($permission_id, $role_id);

        return redirect()->back()
            ->with('success', trans('web::seat.permission_revoked'));
    }

    /**
     * @param \Seat\Web\Http\Validation\RoleGroup $request
     *
     * @return mixed
     */
    public function addGroups(RoleGroup $request)
    {

        $this->giveGroupsRole(
            $request->input('groups'), $request->input('role_id'));

        return redirect()->back()
            ->with('success', trans('web::seat.user_added'));

    }

    /**
     * @param $role_id
     * @param $group_id
     *
     * @return mixed
     */
    public function removeGroup($role_id, $group_id)
    {

        $this->removeGroupFromRole($group_id, $role_id);

        return redirect()->back()
            ->with('success', trans('web::seat.user_removed'));
    }

    /**
     * @param \Seat\Web\Http\Validation\RoleAffilliation $request
     *
     * @return mixed
     */
    public function addAffiliations(RoleAffilliation $request)
    {

        if ($request->input('corporations'))
            $this->giveRoleCorporationAffiliations(
                $request->input('role_id'),
                $request->input('corporations'),
                $request->input('inverse') ? true : false);

        if ($request->input('characters'))
            $this->giveRoleCharacterAffiliations(
                $request->input('role_id'),
                $request->input('characters'),
                $request->input('inverse') ? true : false);

        return redirect()->back()
            ->with('success', 'Affiliations were added to this role');
    }

    /**
     * @param $role_id
     * @param $affiliation_id
     *
     * @return mixed
     */
    public function removeAffiliation($role_id, $affiliation_id)
    {

        $this->removeAffiliationFromRole($role_id, $affiliation_id);

        return redirect()->back()
            ->with('success', trans('web::seat.affiliation_removed'));
    }
}
