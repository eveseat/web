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

namespace Seat\Web\Http\DataTables\Tools;

use Seat\Web\Models\StandingsProfileStanding;
use Yajra\DataTables\Services\DataTable;

/**
 * Class StandingsDataTable.
 *
 * @package Seat\Web\Http\DataTables\Tools
 */
class StandingsDataTable extends DataTable
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return datatables()
            ->eloquent($this->applyScopes($this->query()))
            ->editColumn('category', function ($row) {
                switch($row->category) {
                    case 'character':
                        return '<span><i class="fas fa-user"></i> ' . ucfirst($row->category) . '</span>';
                    case 'corporation':
                        return '<span><i class="fas fa-building"></i> ' . ucfirst($row->category) . '</span>';
                    case 'alliance':
                        return '<span><i class="fas fa-star"></i> ' . ucfirst($row->category) . '</span>';
                }

                return '<span class="badge badge-dark">' . ucfirst($row->category) . '</span>';
            })
            ->editColumn('standing', function ($row) {
                if ($row->standing > 0)
                    return '<span class="badge badge-success">' . $row->standing . '</span>';
                if ($row->standing < 0)
                    return '<span class="badge badge-danger">' . $row->standing . '</span>';

                return '<span class="badge badge-dark">' . $row->standing . '</span>';
            })
            ->editColumn('entity_name.name', function ($row) {
                switch($row->category) {
                    case 'character':
                        return view('web::partials.character', ['character' => $row])->render();
                    case 'corporation':
                        return view('web::partials.corporation', ['corporation' => $row])->render();
                    case 'alliance':
                        return view('web::partials.alliance', ['alliance' => $row])->render();
                }

                return view('web::partials.unknown', ['unknown_id' => $row->entity_id])->render();
            })
            ->addColumn('action', function ($row) {
                return view('web::tools.standings.partials.delete_button', ['id' => $row->standings_profile_id, 'entity_id' => $row->entity_id])->render();
            })
            ->rawColumns(['action', 'entity_name.name', 'standing', 'category'])
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
                'drawCallback' => 'function() { ids_to_names(); }',
            ])
            ->addAction();
    }

    /**
     * @return mixed
     */
    public function query()
    {
        return StandingsProfileStanding::with('entity_name')
            ->orderBy('standing');
    }

    /**
     * @return array
     */
    public function columns() {
        return [
            ['data' => 'category', 'title' => 'Category'],
            ['data' => 'entity_name.name', 'title' => 'Entity'],
            ['data' => 'standing', 'title' => 'Standing'],
        ];
    }
}
