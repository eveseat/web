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

namespace Seat\Web\Http\DataTables\Configuration;

use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Web\Models\User;
use Yajra\DataTables\Services\DataTable;

/**
 * Class UsersDataTable.
 *
 * @package Seat\Web\Http\DataTables\Configuration
 */
class UsersDataTable extends DataTable
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return datatables()->eloquent($this->query())
            ->editColumn('users.name', function ($row) {
                $main_character = new CharacterInfo([
                    'character_id' => $row->main_character_id,
                    'name' => $row->name,
                ]);

                return view('web::partials.character', ['character' => $main_character])->render();
            })
            ->editColumn('characters.name', function ($row) {
                return $row->characters->reject(function ($character) use ($row) {
                    return $character->character_id == $row->main_character_id;
                })->map(function ($character) {
                    return view('web::partials.character-icon-hover', compact('character'))->render();
                })->join(' ');
            })
            ->editColumn('created_at', function ($row) {
                return view('web::partials.date', ['datetime' => $row->created_at])->render();
            })
            ->editColumn('last_login', function ($row) {
                return view('web::partials.date', ['datetime' => $row->last_login])->render();
            })
            ->editColumn('last_login_source', function ($row) {
                return $row->last_login_source ?: '';
            })
            ->editColumn('admin', function ($row) {
                return view('web::configuration.users.partials.admin', compact('row'))->render();
            })
            ->editColumn('roles', function ($row) {
                return view('web::configuration.users.partials.roles', compact('row'))->render();
            })
            ->editColumn('action', function ($row) {
                return view('web::configuration.users.partials.actions', compact('row'))->render();
            })
            ->filterColumn('characters', function ($query, $keyword) {
                return $query->whereHas('characters', function ($sub_query) use ($keyword) {
                    return $sub_query->where('name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('roles', function ($query, $keyword) {
                $query->whereHas('roles', function ($sub_query) use ($keyword) {
                    $sub_query->where('title', 'like', "%{$keyword}%")
                        ->orWhereHas('permissions', function ($query) use ($keyword) {
                            $query->where('title', 'like', "%{$keyword}%");
                        });
                });
            })
            ->rawColumns(['users.name', 'characters.name', 'created_at', 'last_login', 'admin', 'roles', 'action'])
            ->make(true);
    }

    /**
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->postAjax()
            ->columns($this->getColumns())
            ->orderBy(0, 'asc')
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
        return User::with('characters', 'roles', 'roles.permissions')
            ->standard();
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return [
            ['data' => 'users.name', 'title' => trans_choice('web::seat.name', 1)],
            ['data' => 'characters.name', 'title' => trans_choice('web::seat.character', 0), 'orderable' => false],
            ['data' => 'created_at', 'title' => trans('web::seat.member_since')],
            ['data' => 'last_login', 'title' => trans('web::seat.last_login')],
            ['data' => 'last_login_source', 'title' => trans('web::seat.from')],
            ['data' => 'email', 'title' => trans('web::seat.email'), 'searchable' => false, 'orderable' => false],
            ['data' => 'admin', 'title' => trans_choice('web::settings.admin', 1)],
            ['data' => 'roles', 'title' => trans_choice('web::seat.role', 0), 'orderable' => false],
        ];
    }
}
