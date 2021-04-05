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
use Seat\Web\Http\DataTables\Squads\CandidatesDataTable;
use Seat\Web\Models\Squads\Squad;
use Seat\Web\Models\Squads\SquadApplication;

/**
 * Class ApplicationsController.
 *
 * @package Seat\Web\Http\Controllers\Squads
 */
class ApplicationsController extends Controller
{
    /**
     * @param \Seat\Web\Http\DataTables\Squads\CandidatesDataTable $dataTable
     * @param \Seat\Web\Models\Squads\Squad $squad
     * @return mixed
     */
    public function index(CandidatesDataTable $dataTable, Squad $squad)
    {
        return $dataTable->render('web::squads.show', compact('squad'));
    }

    /**
     * @param \Seat\Web\Models\Squads\Squad $squad
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Squad $squad, int $id)
    {
        $application = SquadApplication::with('user')->find($id);

        return view('web::squads.modals.applications.read.content', compact('application'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \Seat\Web\Models\Squads\Squad $squad
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Squad $squad)
    {
        // in case the squad is manual and does not contain any moderator
        // applications are self-approved.
        if ($squad->type == 'manual' && $squad->moderators->isEmpty()) {
            $squad->members()->save(auth()->user());

            $message = sprintf('Approved application from %s into squad %s.',
                auth()->user()->name, $squad->name);

            event('security.log', [$message, 'squads']);

            return redirect()->back();
        }

        $request->validate([
            'message' => 'required',
        ]);

        $application = new SquadApplication([
            'message' => $request->input('message', ''),
        ]);

        $application->user()->associate(auth()->user());
        $application->squad()->associate($squad);

        $application->save();

        if ($squad->moderators_count == 0) {
            $this->approve($application->application_id);
        }

        return redirect()->back();
    }

    /**
     * @param \Seat\Web\Models\Squads\Squad $squad
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function approve(Squad $squad, int $id)
    {
        $application = SquadApplication::with('squad', 'user')->find($id);

        $squad->members()->save($application->user);

        $message = sprintf('Approved application from %s into squad %s.',
            $application->user->name, $application->squad->name);

        event('security.log', [$message, 'squads']);

        $application->delete();

        return redirect()->back();
    }

    /**
     * @param \Seat\Web\Models\Squads\Squad $squad
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(Squad $squad, int $id)
    {
        $application = SquadApplication::find($id);

        $message = sprintf('Reject application from %s into squad %s.',
            $application->user->name, $application->squad->name);

        event('security.log', [$message, 'squads']);

        $application->delete();

        return redirect()->back();
    }

    /**
     * @param \Seat\Web\Models\Squads\Squad $squad
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel(Squad $squad)
    {
        SquadApplication::where('squad_id', $squad->id)
            ->where('user_id', auth()->user()->id)
            ->delete();

        return redirect()->back();
    }
}
