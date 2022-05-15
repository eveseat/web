<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2022 Leon Jacobs
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

namespace Seat\Web\Http\Controllers\Tools;

use Seat\Services\Models\Note;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\Validation\NewIntelNote;

/**
 * Class NotesController.
 *
 * @package Seat\Web\Http\Controllers\Tools
 */
class NotesController extends Controller
{
    /**
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        return response()->json(Note::find($id));
    }

    /**
     * @param  \Seat\Web\Http\Validation\NewIntelNote  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(NewIntelNote $request)
    {
        Note::create([
            'object_type' => $request->input('object_type'),
            'object_id'   => $request->input('object_id'),
            'title'       => $request->input('title'),
            'note'        => $request->input('note'),
        ]);

        return redirect()->back()
            ->with('success', 'Note added');
    }

    /**
     * @param  \Seat\Web\Http\Validation\NewIntelNote  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(NewIntelNote $request, int $id)
    {
        $note = Note::find($id);

        if (! is_null($request->input('title')))
            $note->title = $request->input('title');

        if (! is_null($request->input('note')))
            $note->note = $request->input('note');

        $note->save();

        return redirect()->back()
            ->with('success', 'Note updated');
    }

    /**
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $id)
    {
        Note::destroy($id);

        return redirect()->back()
            ->with('success', 'Note deleted');
    }
}
