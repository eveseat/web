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

use Illuminate\Support\Facades\DB;
use Seat\Web\Models\Acl\Role;
use Yajra\DataTables\Services\DataTable;

/**
 * Class RolesDataTable.
 *
 * @package Seat\Web\Http\DataTables\Squads
 */
class RolesDataTable extends DataTable
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return datatables()
            ->eloquent($this->query())
            ->editColumn('permissions', function ($row) {
                return view('web::squads.partials.permissions', compact('row'))->render();
            })
            ->editColumn('action', function ($row) {
                return view('web::squads.buttons.roles.remove', compact('row'))->render();
            })
            ->filterColumn('permissions', function ($query, $keyword) {
                $query->whereHas('permissions', function ($sub_query) use ($keyword) {
                    $sub_query->whereRaw('title LIKE ?', ["%$keyword%"]);
                });
            })
            ->orderColumn('permissions', function ($query, $order) {
                $query->select('id', 'title', 'description')
                    ->leftJoin('squad_role', 'roles.id', 'squad_role.role_id')
                    ->leftJoin('permission_role', 'squad_role.role_id', 'permission_role.role_id')
                    ->orderBy(DB::raw('COUNT(permission_id)'), $order)
                    ->groupBy('id', 'title', 'description');
            })
            ->rawColumns(['permissions', 'action'])
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
            ->addAction();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return Role::with('permissions')
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
            ['data' => 'title', 'title' => trans_choice('web::squads.name', 1)],
            ['data' => 'description', 'title' => trans('web::squads.description')],
            ['data' => 'permissions', 'title' => trans_choice('web::squads.permission', 0)],
        ];
    }
}
