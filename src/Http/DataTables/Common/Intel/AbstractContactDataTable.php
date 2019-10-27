<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015, 2016, 2017, 2018, 2019  Leon Jacobs
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
 * Class AbstractContactDataTable.
 *
 * @package Seat\Web\Http\DataTables\Common\Intel
 */
abstract class AbstractContactDataTable extends DataTable
{
    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function ajax()
    {

        return datatables()
            ->eloquent($this->applyScopes($this->query()))
            ->editColumn('standing', function ($row) {
                return view('web::partials.standing', ['standing' => $row->standing]);
            })
            ->editColumn('entity.name', function ($row) {
                switch ($row->entity->category) {
                    case 'alliance':
                        return view('web::partials.alliance', ['alliance' => $row->entity]);
                    case 'corporation':
                        return view('web::partials.corporation', ['corporation' => $row->entity]);
                    case 'character':
                        return view('web::partials.character', ['character' => $row->entity]);
                    default:
                        return '';
                }
            })
            ->addColumn('labels', function ($row) {
                return $row->labels->implode('name', ', ');
            })
            ->addColumn('action', function ($row) {
                return view('web::partials.links', ['type' => $row->contact_type, 'id' => $row->contact_id]);
            })
            ->filterColumn('name', function ($query, $keyword) {
                $query->whereHas('entity', function ($sub_query) use ($keyword) {
                    $sub_query->whereRaw('name LIKE ?', ["%$keyword%"]);
                });
            })
            ->filterColumn('labels', function ($query, $keyword) {
                $query->whereHas('labels', function ($sub_query) use ($keyword) {
                    $sub_query->whereRaw('name LIKE ?', ["%$keyword%"]);
                });
            })
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
            ->addAction()
            ->parameters([
                'drawCallback' => 'function () { $("img").unveil(100); ids_to_names(); }',
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
            'entity.name',
            'contact_type',
            'standing',
            'labels',
        ];
    }
}
