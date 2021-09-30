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

namespace Seat\Web\Http\DataTables\Common\Intel;

use Yajra\DataTables\Services\DataTable;

/**
 * Class AbstractNoteDataTable.
 *
 * @package Seat\Web\Http\DataTables\Common\Intel
 */
abstract class AbstractNoteDataTable extends DataTable
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
            ->editColumn('created_at', function ($row) {
                return view('web::partials.date', ['datetime' => $row->created])->render();
            })
            ->editColumn('action', function ($row) {
                return view('web::common.notes.buttons.action', compact('row'))->render();
            })
            ->rawColumns(['created_at', 'action'])
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
    abstract public function query();

    /**
     * @return array
     */
    public function getColumns()
    {
        return [
            ['data' => 'created_at', 'title' => trans('web::seat.created')],
            ['data' => 'title', 'title' => trans_choice('web::seat.title', 1)],
            ['data' => 'note', 'title' => trans('web::seat.note')],
        ];
    }
}
