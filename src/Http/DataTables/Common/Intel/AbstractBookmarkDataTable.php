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

namespace Seat\Web\Http\DataTables\Common\Intel;

use Yajra\DataTables\Services\DataTable;

/**
 * Class AbstractBookmarkDataTable.
 *
 * @package Seat\Web\Http\DataTables\Common\Intel
 */
abstract class AbstractBookmarkDataTable extends DataTable
{
    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function ajax()
    {
        return datatables()
            ->eloquent($this->applyScopes($this->query()))
            ->editColumn('created', function ($row) {
                return view('web::partials.date', ['datetime' => $row->created]);
            })
            ->addColumn('coordinates', function ($row) {
                return view('web::common.bookmarks.coordinates', compact('row'));
            })
            ->rawColumns(['created', 'notes', 'coordinates'])
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
                'drawCallback' => "function (settings) { var api = this.api(); var rows = api.rows({page:'current'}).nodes(); var last=null; api.column(0, {page:'current'}).data().each(function (group, i) { if ( last !== group ) { $(rows).eq(i).before('<tr class=\"bg-gray\"><th colspan=\"5\">' + group + '</th></tr>'); last = group; }}); $('[data-toggle=tooltip]').tooltip(); }",
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
            ['data' => 'folder.name', 'visible' => false],
            ['data' => 'created', 'title' => trans('web::bookmark.created')],
            ['data' => 'label', 'title' => trans('web::bookmark.label')],
            ['data' => 'map_name', 'title' => trans('web::bookmark.location')],
            ['data' => 'notes', 'title' => trans('web::bookmark.notes')],
            ['data' => 'coordinates', 'title' => trans('web::bookmark.coordinates')],
        ];
    }
}
