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

namespace Seat\Web\Http\DataTables\Squads;

use Seat\Eveapi\Models\Character\CharacterInfo;
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
        return datatables()
            ->eloquent($this->query())
            ->addColumn('characters', function ($row) {
                // disclose alts only if the user is a moderator
                if (! auth()->user()->can('squads.manage_members', $this->request->squad))
                    return '';

                return $row->characters->reject(function ($character) use ($row) {
                    return $character->character_id == $row->main_character_id;
                })->map(function ($character) {
                    return view('web::partials.character-icon-hover', compact('character'))->render();
                })->join(' ');
            })
            ->addColumn('member_since', function ($row) {
                return view('web::partials.date', ['datetime' => $row->squads->first()->pivot->created_at])->render();
            })
            ->editColumn('name', function ($row) {
                $character = CharacterInfo::firstOrNew(['character_id' => $row->main_character_id], ['name' => $row->name]);

                return view('web::partials.character', compact('character'))->render();
            })
            ->editColumn('action', function ($row) {
                return view('web::squads.buttons.squads.kick', compact('row'))->render();
            })
            ->filterColumn('characters', function ($query, $keyword) {
                // disclose alts only if the user is a moderator
                if (auth()->user()->can('squads.manage_members', $this->request->squad)) {
                    $query->whereHas('characters', function ($sub_query) use ($keyword) {
                        $sub_query->whereRaw('name LIKE ?', ["%{$keyword}%"]);
                    });
                }
            })
            ->rawColumns(['characters', 'name', 'member_since', 'action'])
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
        return User::with(['characters', 'squads' => function ($query) {
                $query->where('id', $this->request->squad->id);
            }])
            ->standard()
            ->whereHas('squads', function ($query) {
                $query->where('id', $this->request->squad->id);
            });
    }

    /**
     * @return array
     */
    public function columns()
    {
        return [
            ['data' => 'name', 'title' => trans_choice('web::squads.name', 1)],
            ['data' => 'characters', 'title' => trans_choice('web::squads.character', 0), 'orderable' => false],
            ['data' => 'member_since', 'title' => trans('web::squads.member_since'), 'orderable' => false, 'searchable' => false],
        ];
    }
}
