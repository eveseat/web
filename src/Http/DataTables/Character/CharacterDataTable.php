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

namespace Seat\Web\Http\DataTables\Character;

use Seat\Eveapi\Models\Character\CharacterInfo;
use Yajra\DataTables\Services\DataTable;

/**
 * Class CharacterDataTable.
 *
 * @package Seat\Web\Http\DataTables\Character
 */
class CharacterDataTable extends DataTable
{
    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function ajax()
    {
        return datatables()
            ->eloquent($this->applyScopes($this->query()))
            ->editColumn('name', function ($row) {
                return view('web::partials.character', ['character' => $row]);
            })
            ->addColumn('corporation', function ($row) {
                return view('web::partials.corporation', ['corporation' => $row->corporation->entity_id]);
            })
            ->addColumn('alliance', function ($row) {
                if (! is_null($row->alliance_id))
                    return view('web::partials.alliance', ['alliance' => $row->alliance->entity_id]);

                return '';
            })
            ->filterColumn('corporation', function ($query, $keyword) {
                return $query->whereHas('corporation', function ($sub_query) use ($keyword) {
                    return $sub_query->whereRaw('name LIKE ?', ["%$keyword%"]);
                });
            })
            ->filterColumn('alliance', function ($query, $keyword) {
                return $query->whereHas('alliance', function ($sub_query) use ($keyword) {
                    return $sub_query->whereRaw('name LIKE ?', ["%$keyword%"]);
                });
            })
            ->rawColumns(['name', 'corporation', 'alliance'])
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
            ->parameters([
                'drawCallback' => 'function() { ids_to_names(); }',
            ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return CharacterInfo::with('corporation', 'alliance');
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return [
            ['data' => 'name', 'title' => trans_choice('web::seat.name', 1)],
            ['data' => 'corporation', 'title' => trans_choice('web::seat.corporation', 1), 'orderable' => false],
            ['data' => 'alliance', 'title' => trans('web::seat.alliance'), 'orderable' => false],
            ['data' => 'security_status', 'title' => trans('web::seat.security_status')],
        ];
    }
}
