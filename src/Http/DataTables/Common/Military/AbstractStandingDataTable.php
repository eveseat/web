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

namespace Seat\Web\Http\DataTables\Common\Military;

use Yajra\DataTables\Services\DataTable;

/**
 * Class AbstractStandingDataTable.
 *
 * @package Seat\Web\Http\DataTables\Common\Military
 */
abstract class AbstractStandingDataTable extends DataTable
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
            ->editColumn('from_type', function ($row) {
                if ($row->from_type == 'npc_corp')
                    return trans_choice('web::seat.corporation', 0);

                return ucfirst($row->from_type);
            })
            ->editColumn('from.name', function ($row) {
                switch ($row->from_type) {
                    case 'agent':
                        return view('web::partials.character', ['character' => $row->from])->render();
                    case 'npc_corp':
                        return view('web::partials.corporation', ['corporation' => $row->from])->render();
                    case 'faction':
                        return view('web::partials.faction', ['faction' => $row->from])->render();
                }

                return $row->from->name;
            })
            ->editColumn('standing', function ($row) {
                if($row->standing > 5)
                    return '<span class="label label-primary">' . $row->standing . '</span>';
                elseif($row->standing >= 1)
                    return '<span class="label label-info">' . $row->standing . '</span>';
                elseif($row->standing > -1)
                    return '<span class="label label-default">' . $row->standing . '</span>';
                elseif($row->standing >= -5)
                    return '<span class="label label-warning">' . $row->standing . '</span>';
                else
                    return '<span class="label label-danger">' . $row->standing . '</span>';
            })
            ->rawColumns(['from.name', 'standing'])
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
                'drawCallback' => "function (settings) { var api = this.api(); var rows = api.rows({page:'current'}).nodes(); var last=null; api.column(0, {page:'current'}).data().each(function (group, i) { if ( last !== group ) { $(rows).eq(i).before('<tr class=\"bg-gray\"><th colspan=\"5\">' + group + '</th></tr>'); last = group; }}); ids_to_names(); }",
            ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    abstract public function query();

    /**
     * @return array
     */
    public function getColumns()
    {
        return [
            ['data' => 'from_type', 'visible' => false],
            ['data' => 'from.name', 'title' => trans('web::seat.from')],
            ['data' => 'standing', 'title' => trans_choice('web::seat.standings', 1)],
        ];
    }
}
