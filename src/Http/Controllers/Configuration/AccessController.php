<?php
/*
This file is part of SeAT

Copyright (C) 2015, 2016  Leon Jacobs

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/

namespace Seat\Web\Http\Controllers\Configuration;

use App\Http\Controllers\Controller;
use Seat\Services\Repositories\Character\Character;
use Seat\Services\Repositories\Configuration\UserRespository;
use Seat\Services\Repositories\Corporation\Corporation;
use Seat\Web\Acl\Pillow;
use Seat\Web\Validation\Permission;
use Seat\Web\Validation\Role;
use Seat\Web\Validation\RoleAffilliation;
use Seat\Web\Validation\RolePermission;
use Seat\Web\Validation\RoleUser;

/**
 * Class AccessControllerr
 * @package Seat\Web\Http\Controllers\Configuration
 */
class AccessController extends Controller
{

    use Pillow, UserRespository, Character,
        Corporation {

        // Resolve the where_filter method conflict that comes from the
        // Seat\Services\Helpers\Filterable trait that they both use.
        // Technically, this actually does not mean anything when
        // looked at from the perspective of the AccessController.
        Character::where_filter insteadof Corporation;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function getAll()
    {

        $roles = $this->getCompleteRole();

        return view('web::configuration.access.list', compact('roles'));
    }

    /**
     * @param \Seat\Web\Validation\Role $request
     *
     * @return mixed
     */
    public function newRole(Role $request)
    {

        $this->addRole($request->input('title'));

        return redirect()->back()
            ->with('success', trans('web::access.role_added'));
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
            ->with('success', trans('web::access.role_removed'));
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

        $role_permissions = $role->permissions()
            ->get()
            ->pluck('title')
            ->toArray();

        $role_affiliations = $role->affiliations();

        $role_users = $role->users()
            ->get()
            ->pluck('name')
            ->toArray();

        $all_users = $this->getAllUsers()
            ->pluck('name')
            ->toArray();

        $all_characters = $this->getAllCharacters();

        $all_corporations = $this->getAllCorporations();

        return view(
            'web::configuration.access.edit',
            compact(
                'role', 'role_permissions', 'role_users', 'all_users',
                'role_affiliations', 'all_characters', 'all_corporations'
            ));
    }

    /**
     * @param \Seat\Web\Validation\RolePermission $request
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
            ->with('success', trans('web::access.permissions_granted'));
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
            ->with('success', trans('web::access.permission_revoked'));
    }

    /**
     * @param \Seat\Web\Validation\RoleUser $request
     *
     * @return mixed
     */
    public function addUsers(RoleUser $request)
    {

        $this->giveUsernamesRole(
            $request->input('users'), $request->input('role_id'));

        return redirect()->back()
            ->with('success', trans('web::access.user_added'));

    }

    /**
     * @param $role_id
     * @param $user_id
     *
     * @return mixed
     */
    public function removeUser($role_id, $user_id)
    {

        $this->removeUserFromRole($user_id, $role_id);

        return redirect()->back()
            ->with('success', trans('web::access.user_removed'));
    }

    /**
     * @param \Seat\Web\Validation\RoleAffilliation $request
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
            ->with('success', trans('web::access.affiliation_removed'));
    }
}
