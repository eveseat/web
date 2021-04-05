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

use Seat\Web\Models\Acl\Role;
use Yajra\DataTables\Services\DataTable;

/**
 * Class RolesDataTable.
 *
 * @package Seat\Web\Http\DataTables\Configuration
 */
class RolesDataTable extends DataTable
{
    /**
     * @return \Illuminate\Http\JsonResponse|\Yajra\DataTables\DataTableAbstract|\Yajra\DataTables\EloquentDataTable
     */
    public function ajax()
    {
        return datatables()->eloquent($this->query())
            ->editColumn('users', function ($row) {
                return $row->users->filter(function ($user) { return $user->name !== 'admin'; })->count();
            })
            ->editColumn('permissions', function ($row) {
                return $row->permissions->count();
            })
            ->editColumn('action', function ($row) {
                return view('web::configuration.access.partials.action', compact('row'))->render();
            })
            ->filterColumn('users', function ($query, $keyword) {
                return $query->whereHas('users', function ($sub_query) use ($keyword) {
                    return $sub_query->whereRaw('name LIKE ?', ["%{$keyword}%"]);
                })->orWhereHas('users.characters', function ($sub_query) use ($keyword) {
                    return $sub_query->whereRaw('name LIKE ?', ["%{$keyword}%"]);
                });
            })
            ->filterColumn('permissions', function ($query, $keyword) {
                return $query->whereHas('permissions', function ($sub_query) use ($keyword) {
                    return $sub_query->whereRaw('title LIKE ?', ["%{$keyword}%"]);
                });
            })
            ->rawColumns(['action'])
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
            ->addAction();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return Role::with('permissions', 'users', 'users.characters');
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return [
            ['data' => 'title', 'title' => trans_choice('web::seat.name', 2)],
            ['data' => 'users', 'title' => trans_choice('web::seat.member', 2), 'orderable' => false],
            ['data' => 'permissions', 'title' => trans_choice('web::seat.permission', 2), 'orderable' => false],
        ];
    }
}
