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

namespace Seat\Web\Http\Controllers\Squads;

use Illuminate\Http\Request;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\DataTables\Squads\RolesDataTable;
use Seat\Web\Models\Acl\Role;
use Seat\Web\Models\Squads\Squad;

/**
 * Class RolesController.
 *
 * @package Seat\Web\Http\Controllers\Squads
 */
class RolesController extends Controller
{
    /**
     * @param  \Seat\Web\Http\DataTables\Squads\RolesDataTable  $dataTable
     * @param  \Seat\Web\Models\Squads\Squad  $squad
     * @return mixed
     */
    public function show(RolesDataTable $dataTable, Squad $squad)
    {
        return $dataTable->render('web::squads.show', compact('squad'));
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Seat\Web\Models\Squads\Squad  $squad
     * @return \Illuminate\Http\JsonResponse
     */
    public function lookup(Request $request, Squad $squad)
    {
        $roles = Role::whereNotIn('id', $squad->roles->pluck('id'))
            ->where('title', 'like', ["%{$request->query('q', '')}%"])
            ->orderBy('title')
            ->get()
            ->map(function ($role) {
                return [
                    'id'   => $role->id,
                    'text' => $role->title,
                ];
            });

        return response()->json([
            'results' => $roles,
        ]);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Seat\Web\Models\Squads\Squad  $squad
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Squad $squad)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $role = Role::find($request->input('role_id'));
        $squad->roles()->save($role);

        return redirect()->back()
            ->with('success', sprintf('%s has been successfully added as role to this Squad.', $role->title));
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Seat\Web\Models\Squads\Squad  $squad
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, Squad $squad)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $role = Role::find($request->input('role_id'));

        $squad->roles()->detach($role->id);

        return redirect()->back()
            ->with('success', sprintf('%s has been successfully removed from roles of that Squad.', $role->title));
    }
}
