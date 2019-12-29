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

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\DataTables\Squads\CandidatesDataTable;
use Seat\Web\Http\DataTables\Squads\MembersDataTable;
use Seat\Web\Http\DataTables\Squads\RolesDataTable;
use Seat\Web\Models\Squads\Squad;
use Seat\Web\Models\Squads\SquadApplication;
use Seat\Web\Models\User;

/**
 * Class SquadsController.
 *
 * @package Seat\Web\Http\Controllers\Squads
 */
class SquadsController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $squads = Squad::with('members', 'moderators', 'applications');

        if ($request->has('filters')) {
            $filters = $request->query('filters');

            if (key_exists('type', $filters))
                $squads = $squads->ofType(Arr::get($filters, 'type'));

            if (key_exists('is_moderated', $filters))
                $squads = $squads->moderated();

            if (key_exists('candidates', $filters))
                $squads = $squads->candidate();

            if (key_exists('members', $filters))
                $squads = $squads->member();

            if (key_exists('moderators', $filters))
                $squads = $squads->moderator();
        }

        if ($request->has('query')) {
            $keyword = $request->query('query');

            $squads->where(function ($query) use ($keyword) {
                $query->where('name', 'like', ["%$keyword%"]);
                $query->orWhere('description', 'like', ["%$keyword%"]);
                $query->orWhereHas('moderators', function ($sub_query) use ($keyword) {
                    $sub_query->where('name', 'like', "%$keyword%");
                });
            });
        }

        $squads = $squads->paginate(6);

        if (request()->ajax())
            return response()->json($squads);

        return view('web::squads.list', compact('squads'));
    }

    /**
     * @param \Seat\Web\Http\DataTables\Squads\MembersDataTable $dataTable
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(MembersDataTable $dataTable, int $id)
    {
        return $this->getSquadMembers($dataTable, $id);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('web::squads.create');
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|unique:squads|max:255',
            'type'        => 'required|in:manual,auto,hidden',
            'description' => 'required',
            'logo'        => 'mimes:jpeg,jpg,png|max:2000',
            'filters'     => 'json',
        ]);

        $squad = Squad::create([
            'name'        => $request->input('name'),
            'type'        => $request->input('type'),
            'description' => $request->input('description'),
            'filters'     => json_decode($request->input('filters')),
            'logo'        => $request->file('logo'),
        ]);

        return redirect()->route('squads.show', $squad->id)
            ->with('success', 'Squad has successfully been created. Please complete its setup by providing roles & moderators.');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $id)
    {
        Squad::destroy($id);

        return redirect()->route('squads.list')
            ->with('success', 'Squad has been deleted.');
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function join(Request $request, int $id)
    {
        $squad = Squad::find($id);

        $application = new SquadApplication([
            'message' => $request->input('message') ?: '',
        ]);

        $application->user()->associate(auth()->user());
        $application->squad()->associate($squad);

        $application->save();

        return redirect()->back();
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function leave(int $id)
    {
        $squad = Squad::find($id);
        $squad->members()->detach(auth()->user()->id);

        return redirect()->route('squads.list');
    }

    /**
     * @param int $id
     * @param int $user_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function kick(int $id, int $user_id)
    {
        $squad = Squad::find($id);
        $squad->members()->detach($user_id);

        return redirect()->back();
    }

    /**
     * @param int $id
     * @param int $user_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addModerator(Request $request, int $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::find($request->input('user_id'));
        $squad = Squad::find($id);
        $squad->moderators()->save($user);

        return redirect()->back()
            ->with('success', sprintf('%s has been successfully added as moderator to this Squad.', $user->name));
    }

    /**
     * @param int $id
     * @param int $user_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeModerator(int $id, int $user_id)
    {
        $user = User::find($user_id);
        $squad = Squad::find($id);

        $squad->moderators()->detach($user_id);

        return redirect()->back()
            ->with('success', sprintf('%s has been successfully removed from moderators of that Squad.', $user->name));
    }

    /**
     * @param \Seat\Web\Http\DataTables\Squads\MembersDataTable $dataTable
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getSquadMembers(MembersDataTable $dataTable, int $id)
    {
        $squad = Squad::with('members', 'moderators', 'moderators.main_character')->find($id);

        return $dataTable->render('web::squads.show', compact('squad'));
    }

    /**
     * @param \Seat\Web\Http\DataTables\Squads\RolesDataTable $dataTable
     * @param int $id
     * @return mixed
     */
    public function getSquadRoles(RolesDataTable $dataTable, int $id)
    {
        $squad = Squad::with('members', 'moderators', 'moderators.main_character')->find($id);

        return $dataTable->render('web::squads.show', compact('squad'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAvailableModerators(Request $request, int $id)
    {
        $users = User::whereNotIn('id', Squad::find($id)->moderators->pluck('id'))
            ->where(function ($query) use ($request) {
                $query->where('name', 'like', ["%{$request->query('q', '')}%"]);
                $query->orWhereHas('characters', function ($sub_query) use ($request) {
                    $sub_query->where('name', 'like', ["%{$request->query('q', '')}%"]);
                });
            })
            ->where('name', '<>', 'admin')
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
}
