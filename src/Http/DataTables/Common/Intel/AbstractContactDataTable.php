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
     *
     * @throws \Exception
     */
    public function ajax()
    {

        return datatables()
            ->eloquent($this->applyScopes($this->query()))
            ->editColumn('standing', function ($row) {
                return view('web::partials.standing', ['standing' => $row->standing])->render();
            })
            ->editColumn('entity.name', function ($row) {
                switch ($row->contact_type) {
                    case 'alliance':
                        return view('web::partials.alliance', ['alliance' => $row->entity])->render();
                    case 'corporation':
                        return view('web::partials.corporation', ['corporation' => $row->entity])->render();
                    case 'character':
                        return view('web::partials.character', ['character' => $row->entity])->render();
                    default:
                        return '';
                }
            })
            ->editColumn('entity.affiliation.corporation.name', function ($row) {
                if ($row->entity->affiliation->corporation->entity_id)
                    return view('web::partials.corporation', ['corporation' => $row->entity->affiliation->corporation])->render();

                return '';
            })
            ->editColumn('entity.affiliation.alliance.name', function ($row) {
                if ($row->entity->affiliation->alliance->entity_id)
                    return view('web::partials.alliance', ['alliance' => $row->entity->affiliation->alliance])->render();

                return '';
            })
            ->addColumn('labels', function ($row) {
                return $row->labels->implode('name', ', ');
            })
            ->addColumn('action', function ($row) {
                return view('web::partials.links', ['type' => $row->contact_type, 'id' => $row->contact_id])->render();
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
            ->rawColumns([
                'standing', 'entity.name', 'entity.affiliation.corporation.name', 'entity.affiliation.alliance.name',
                'action',
            ])
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
            ['data' => 'entity.name', 'title' => trans_choice('web::seat.name', 1)],
            ['data' => 'entity.affiliation.corporation.name', 'title' => trans_choice('web::seat.corporation', 1), 'orderable' => false],
            ['data' => 'entity.affiliation.alliance.name', 'title' => trans_choice('web::seat.alliance', 1), 'orderable' => false],
            ['data' => 'contact_type', 'title' => trans_choice('web::seat.type', 1)],
            ['data' => 'standing', 'title' => trans_choice('web::seat.standings', 1)],
            ['data' => 'labels', 'title' => trans('web::seat.labels')],
        ];
    }
}
