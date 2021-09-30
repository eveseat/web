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

namespace Seat\Web\Http\DataTables\Alliance;

use Seat\Eveapi\Models\Alliances\Alliance;
use Yajra\DataTables\Services\DataTable;

/**
 * Class CorporationDataTable.
 *
 * @package Seat\Web\Http\DataTables\Corporation
 */
class AllianceDataTable extends DataTable
{
    /**
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function ajax()
    {
        return datatables()
            ->eloquent($this->applyScopes($this->query()))
            ->editColumn('name', function ($row) {
                return view('web::partials.alliance', ['alliance' => $row])->render();
            })
            ->editColumn('action', function ($row) {
                return view('web::alliance.partials.delete', compact('row'));
            })
            ->rawColumns(['name', 'action'])
            ->make(true);
    }

    /**
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->addAction()
            ->orderBy(0, 'asc')
            ->postAjax()
            ->parameters([
                'drawCallback' => 'function() { ids_to_names(); }',
            ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Seat\Eveapi\Models\Alliances\Alliance
     */
    public function query()
    {
        return Alliance::with([])->select('name', 'ticker', 'alliance_id');
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return [
            ['data' => 'name', 'title' => trans_choice('web::seat.name', 1)],
            ['data' => 'ticker', 'title' => trans('web::seat.ticker')],
        ];
    }
}
