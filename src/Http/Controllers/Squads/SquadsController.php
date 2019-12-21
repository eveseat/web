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
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\DataTables\Scopes\SquadScope;
use Seat\Web\Http\DataTables\Squads\CandidatesDataTable;
use Seat\Web\Http\DataTables\Squads\MembersDataTable;
use Seat\Web\Http\DataTables\Squads\SquadsDataTable;
use Seat\Web\Models\Squads\Squad;
use Seat\Web\Models\Squads\SquadApplication;

/**
 * Class SquadsController.
 *
 * @package Seat\Web\Http\Controllers\Squads
 */
class SquadsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(SquadsDataTable $dataTable)
    {
        // in case authenticated user is not superuser
        // exclude all hidden squad for which he's not either member or moderators
        if (! auth()->user()->hasSuperUser())
            $dataTable->addScope(new SquadScope());

        return $dataTable->render('web::squads.list');
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
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showApplication(int $id)
    {
        $application = SquadApplication::with('user')->find($id);

        return view('web::squads.modals.applications.read.content', compact('application'));
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function approveApplication(int $id)
    {
        $application = SquadApplication::with('squad', 'user')->find($id);

        $application->squad->members()->save($application->user);
        $application->delete();

        return redirect()->back();
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function rejectApplication(int $id)
    {
        $application = SquadApplication::find($id)->delete();

        return redirect()->back();
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
    public function kickMember(int $id, int $user_id)
    {
        $squad = Squad::find($id);
        $squad->members()->detach($user_id);

        return redirect()->back();
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
     * @param \Seat\Web\Http\DataTables\Squads\CandidatesDataTable $dataTable
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getSquadCandidates(CandidatesDataTable $dataTable, int $id)
    {
        $squad = Squad::with('members', 'moderators', 'moderators.main_character')->find($id);

        return $dataTable->render('web::squads.show', compact('squad'));
    }
}
