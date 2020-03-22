<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015, 2016, 2017, 2018, 2019  Leon Jacobs
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

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\DataTables\Squads\MembersDataTable;
use Seat\Web\Models\Squads\Squad;
use Seat\Web\Models\User;

/**
 * Class MembersController.
 *
 * @package Seat\Web\Http\Controllers\Squads
 */
class MembersController extends Controller
{
    /**
     * @param \Seat\Web\Http\DataTables\Squads\MembersDataTable $dataTable
     * @param int $id
     * @return mixed
     */
    public function index(MembersDataTable $dataTable, int $id)
    {
        $squad = Squad::with('members', 'moderators', 'moderators.main_character')->find($id);

        return $dataTable->render('web::squads.show', compact('squad'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function lookup(Request $request, int $id)
    {
        $users = User::standard()
            ->whereDoesntHave('squads', function (Builder $query) use ($id) {
                $query->where('id', $id);
            })
            ->where(function ($query) use ($request) {
                $query->where('name', 'like', ["%{$request->query('q', '')}%"]);
                $query->orWhereHas('characters', function ($sub_query) use ($request) {
                    $sub_query->where('name', 'like', ["%{$request->query('q', '')}%"]);
                });
            })
            ->orderBy('name')
            ->get()
            ->map(function ($user) {
                return [
                    'id'   => $user->id,
                    'text' => $user->name,
                ];
            });

        return response()->json([
            'results' => $users,
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, int $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $squad = Squad::find($id);
        $user = User::find($request->input('user_id'));
        $squad->members()->attach($user);

        return redirect()->back()
            ->with('success', sprintf('%s has been invited to this Squad.', $user->name));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, int $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $squad = Squad::find($id);
        $user = User::find($request->input('user_id'));
        $squad->members()->detach($user->id);

        $message = sprintf('%s has been kicked from squad %s.',
            $user->name, $squad->name);

        event('security.log', [$message, 'squads']);

        return redirect()->back()
            ->with('success', sprintf('%s has been kicked from the Squad.', $user->name));
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function leave(int $id)
    {
        $squad = Squad::find($id);
        $squad->members()->detach(auth()->user()->id);

        return redirect()->route('squads.index');
    }
}
