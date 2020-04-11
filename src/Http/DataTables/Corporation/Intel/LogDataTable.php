<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2020 Leon Jacobs
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

namespace Seat\Web\Http\DataTables\Corporation\Intel;

use Seat\Eveapi\Models\Corporation\CorporationRoleHistory;
use Yajra\DataTables\Services\DataTable;

/**
 * Class LogDataTable.
 *
 * @package Seat\Web\Http\DataTables\Corporation\Intel
 */
class LogDataTable extends DataTable
{
    public function ajax()
    {
        return datatables()
            ->eloquent($this->applyScopes($this->query()))
            ->editColumn('changed_at', function ($row) {
                return view('web::partials.date', ['datetime' => $row->changed_at]);
            })
            ->editColumn('issuer.name', function ($row) {
                return view('web::partials.character', ['character' => $row->issuer->entity_id])->render();
            })
            ->editColumn('character.name', function ($row) {
                return view('web::partials.character', ['character' => $row->character->entity_id])->render();
            })
            ->editColumn('role', function ($row) {
                return str_replace('_', ' ', $row->role);
            })
            ->rawColumns(['changed_at', 'issuer.name', 'character.name'])
            ->make(true);
    }

    public function html()
    {
        return $this->builder()
            ->postAjax()
            ->columns($this->getColumns())
            ->parameters([
                'drawCallback' => 'function () { $("img").unveil(100); ids_to_names(); }',
            ]);
    }

    public function query()
    {
        return CorporationRoleHistory::with('issuer', 'character');
    }

    public function getColumns()
    {
        return [
            'changed_at',
            'issuer.name',
            'character.name',
            'state',
            'role',
        ];
    }
}
