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

namespace Seat\Web\Http\DataTables\Squads;

use Seat\Web\Models\Squads\SquadApplication;
use Yajra\DataTables\Services\DataTable;

/**
 * Class CandidatesDataTable.
 *
 * @package Seat\Web\Http\DataTables\Squads
 */
class CandidatesDataTable extends DataTable
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return datatables()
            ->eloquent($this->query())
            ->addColumn('characters', function ($row) {
                return $row->user->characters->reject(function ($character) use ($row) {
                    return $character->character_id == $row->main_character_id;
                })->map(function ($character) {
                    return view('web::partials.character-icon-hover', compact('character'))->render();
                })->join(' ');
            })
            ->editColumn('user.name', function ($row) {
                return view('web::partials.character', ['character' => $row->user->main_character])->render();
            })
            ->editColumn('created_at', function ($row) {
                return view('web::partials.date', ['datetime' => $row->created_at])->render();
            })
            ->editColumn('action', function ($row) {
                return view('web::squads.buttons.application.action', compact('row'))->render();
            })
            ->filterColumn('characters', function ($query, $keyword) {
                $query->whereHas('user.characters', function ($sub_query) use ($keyword) {
                    $sub_query->whereRaw('name LIKE ?', ["%{$keyword}%"]);
                });
            })
            ->rawColumns(['characters', 'user.name', 'created_at', 'action'])
            ->make(true);
    }

    /**
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->postAjax()
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
        return SquadApplication::with('user', 'user.characters')
            ->where('squad_id', $this->request->squad->id)
            ->select('squad_applications.created_at', 'user_id', 'application_id');
    }

    /**
     * @return array
     */
    public function columns()
    {
        return [
            ['data' => 'user.name', 'title' => trans_choice('web::squads.name', 1)],
            ['data' => 'characters', 'title' => trans_choice('web::squads.character', 0), 'orderable' => false],
            ['data' => 'created_at', 'title' => trans('web::squads.applied_at')],
        ];
    }
}
