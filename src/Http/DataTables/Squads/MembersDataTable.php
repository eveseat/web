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

namespace Seat\Web\Http\DataTables\Squads;

use Seat\Web\Models\Squads\Squad;
use Seat\Web\Models\User;
use Yajra\DataTables\Services\DataTable;

/**
 * Class MembersDataTable.
 *
 * @package Seat\Web\Http\DataTables\Squads
 */
class MembersDataTable extends DataTable
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $squad = Squad::find(request()->route('id'));

        return datatables()
            ->eloquent($this->query())
            ->addColumn('characters', function ($row) {
                return $row->characters->reject(function ($character) use ($row) {
                    return $character->character_id == $row->main_character_id;
                })->map(function ($character) {
                    return view('web::partials.character-icon-hover', compact('character'))->render();
                })->join(' ');
            })
            ->editColumn('name', function ($row) {
                return sprintf('%s %s',
                    img('characters', 'portrait', $row->main_character_id, 64, ['class' => 'img-circle eve-icon small-icon'], false),
                    $row->name);
            })
            ->editColumn('action', function ($row) use ($squad) {
                return view('web::squads.buttons.squads.kick', compact('row', 'squad'));
            })
            ->rawColumns(['name', 'characters'])
            ->make(true);
    }

    /**
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->columns())
            ->parameters([
                'drawCallback' => 'function() { $("[data-toggle=tooltip]").tooltip(); }',
            ])
            ->addAction();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return User::with('characters')
            ->standard()
            ->whereHas('squads', function ($query) {
                $query->where('id', $this->request->id);
            });
    }

    /**
     * @return array
     */
    public function columns()
    {
        return [
            ['data' => 'name', 'title' => trans_choice('web::squads.name', 1)],
            ['data' => 'characters', 'title' => trans_choice('web::squads.character', 0)],
        ];
    }
}
