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
use Seat\Web\Http\Validation\Squad as SquadValidation;
use Seat\Web\Models\Squads\Squad;

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
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(int $id)
    {
        $squad = Squad::with('members', 'moderators', 'moderators.main_character')->find($id);

        return view('web::squads.show', compact('squad'));
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
    public function store(SquadValidation $request)
    {
        $squad = Squad::create([
            'name'        => $request->input('name'),
            'type'        => $request->input('type'),
            'description' => $request->input('description'),
            'filters'     => $request->input('filters'),
            'logo'        => $request->file('logo'),
        ]);

        return redirect()->route('squads.show', $squad->id)
            ->with('success', 'Squad has successfully been created. Please complete its setup by providing roles & moderators.');
    }

    /**
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(int $id)
    {
        $squad = Squad::find($id);

        return view('web::squads.edit', compact('squad'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, int $id)
    {
        $squad = Squad::find($id);

        $squad->name = $request->input('name');
        $squad->type = $request->input('type');
        $squad->description = $request->input('description');
        $squad->filters = $request->input('filters');

        if ($request->hasFile('logo'))
            $squad->logo = $request->file('logo');

        $squad->save();

        return redirect()
            ->route('squads.show', [$id])
            ->with('success', 'Squad has successfully been updated.');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $id)
    {
        Squad::destroy($id);

        return redirect()->route('squads.index')
            ->with('success', 'Squad has been deleted.');
    }
}
