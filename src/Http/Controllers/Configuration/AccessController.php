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

namespace Seat\Web\Http\Controllers\Configuration;

use Illuminate\Http\Request;
use Intervention\Image\Exception\NotReadableException;
use Seat\Web\Acl\AccessManager;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\DataTables\Configuration\RolesDataTable;
use Seat\Web\Models\Acl\Permission;
use Seat\Web\Models\Acl\Role;

/**
 * Class AccessController.
 * @package Seat\Web\Http\Controllers\Configuration
 */
class AccessController extends Controller
{
    use AccessManager;

    /**
     * @param \Seat\Web\Http\DataTables\Configuration\RolesDataTable $data_table
     * @return \Illuminate\View\View
     */
    public function index(RolesDataTable $data_table)
    {
        $nb_roles = $data_table->query()->count();

        return $data_table->render('web::configuration.access.list', compact('nb_roles'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:roles,title|max:255',
        ]);

        $role = Role::create([
            'title' => $request->input('title'),
        ]);

        return redirect()
            ->route('configuration.access.roles.edit', [
                $role->id,
            ])->with('success', trans('web::seat.role_added'));
    }

    /**
     * @param Role $role
     * @return \Illuminate\View\View
     */
    public function edit(Role $role)
    {

        // Get the role. We don't get the full one
        // as we need to mangle some of the data to
        // arrays for easier processing in the view

        $role_permissions = $role->permissions()->get()->pluck('title')->toArray();

        return view('web::configuration.access.edit', compact('role', 'role_permissions'));
    }

    /**
     * @param Request $request
     * @param Role $role
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'title' => 'string|required',
            'description' => 'string|nullable',
            'permissions' => 'array',
            'filters' => 'array',
            'logo' => 'mimes:jpeg,jpg,png|max:2000',
            'members' => 'json',
        ]);

        //
        // updating role information
        //

        try {
            $role->title = $request->input('title');
            $role->description = $request->input('description');
            $role->logo = $request->file('logo');
        } catch (NotReadableException $e) {
            return redirect()->route('configuration.access.roles.edit', [$role->id])
                ->with('error', $e->getMessage());
        }

        //
        // updating role members
        //

        $this->giveUsersRole(json_decode($request->input('members'), true), $role->id);

        //
        // updating role permissions and filters
        //

        $new_permissions = $request->input('permissions', []);
        $new_filters = $request->input('filters', []);

        $added_counter = 0;
        $removed_counter = 0;
        $filter_counter = 0;

        foreach (config('seat.permissions') as $scope => $permissions) {

            // in case the permission does not have any scope, cast it into an array
            if (! is_array($permissions))
                $permissions = [$permissions];

            foreach ($permissions as $permission => $permission_meta) {

                $permission_filters = null;

                $permission_title = $permission;

                // in case the permission is using vanilla description, use meta_data instead of key
                if (! is_array($permission_meta))
                    $permission_title = $permission_meta;

                // in case we have a scope, concatenate it to the permission itself
                if (! is_int($scope))
                    $permission_title = sprintf('%s.%s', $scope, $permission_title);

                // search the permission in system or create a new one
                $acl_permission = Permission::firstOrCreate([
                    'title' => $permission_title,
                ]);

                // the permission has been removed from the role
                if (! array_key_exists($acl_permission->title, $new_permissions) && $role->permissions->contains($acl_permission->id)) {
                    $role->permissions()->detach($acl_permission->id);
                    $removed_counter++;

                    continue;
                }

                // retrieve any filters set to the permission
                if (array_key_exists($acl_permission->title, $new_filters)) {
                    if ($new_filters[$acl_permission->title] != '{}')
                        $permission_filters = $new_filters[$acl_permission->title];
                }

                if (array_key_exists($acl_permission->title, $new_permissions)) {

                    if ($role->permissions->contains($acl_permission->id)) {

                        // the permission has been updated
                        $role->permissions()->syncWithoutDetaching([
                            $acl_permission->id => [
                                'filters' => $permission_filters,
                            ],
                        ]);

                    } else {

                        // the permission has been added to the role
                        $role->permissions()->attach($acl_permission->id, [
                            'filters' => $permission_filters,
                        ]);

                        $added_counter++;
                    }
                }

                if (! is_null($permission_filters))
                    $filter_counter++;
            }
        }

        $role->save();

        return redirect()->route('configuration.access.roles.edit', [$role->id])
            ->with('success', trans('web::seat.role_updated', [
                'added'   => $added_counter,
                'removed' => $removed_counter,
                'filtered' => $filter_counter,
            ]));
    }

    /**
     * @param Role $role
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Role $role)
    {
        Role::destroy($role->id);

        return redirect()->route('configuration.access.roles')
            ->with('success', trans('web::seat.role_removed'));
    }

    /**
     * @param int $role_id
     * @param int $user_id
     */
    public function removeUser(int $role_id, int $user_id)
    {
        $this->removeUserFromRole($user_id, $role_id);
    }
}
