<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2026 to present Leon Jacobs
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

namespace Seat\Web\Http\DataTables\Corporation\Financial;

use Illuminate\Http\JsonResponse;
use Seat\Eveapi\Models\CorporationProjects\CorporationProject;
use Yajra\DataTables\Services\DataTable;

/**
 * Class StructureDataTable.
 *
 * @package Seat\Web\Http\DataTables\Corporation\Financial
 */
class ProjectDataTable extends DataTable
{
    /**
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function ajax(): JsonResponse
    {
        return datatables()
            ->eloquent($this->applyScopes($this->query()))
            ->editColumn('state', function ($row) {
                return ucfirst(str_replace('_', ' ', $row->state));
            })
            ->addColumn('progress', function ($raw) {
                $row = (object)[
                    'min' => 0,
                    'value' => $raw->progress_current,
                    'max' => $raw->progress_desired,
                    'showval' => true,
                ];
                return view('web::partials.progress', compact('row'))->render();
            })
            ->addColumn('reward', function ($raw) use (&$rawColumns) {
                if (is_null($raw->reward_initial) || $raw->reward_initial == 0) {
                    return 'No Reward'; // TODO localise
                } else {
                    $row = (object)[
                        'min' => 0,
                        'value' => $raw->reward_initial - $raw->reward_remaining,
                        'max' => $raw->reward_initial,
                        'showval' => true,
                    ];
                    $rawColumns[] = 'reward';
                    return view('web::partials.progress', compact('row'))->render();
                }
            })
            ->editColumn('action', function ($row) use ($rawColumns){
                return view('web::corporation.projects.buttons.action', compact('row'))->render();
            })
            ->rawColumns(['action', 'progress', 'reward'])
            ->toJson();
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
            ->addTableClass('table-striped table-hover')
            ->parameters([
                'drawCallback' => 'function() { $("[data-toggle=tooltip]").tooltip(); }',
            ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return CorporationProject::withCount('contributors');
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return [
            ['data' => 'name', 'title' => trans_choice('web::seat.name', 1)],
            ['data' => 'last_modified', 'title' => trans_choice('web::seat.last_modified', 1)],
            ['data' => 'state', 'title' => trans('web::seat.state')],
            ['data' => 'progress', 'title' => trans('web::seat.progress')],
            ['data' => 'reward', 'title' => trans('web::seat.reward')],
            ['data' => 'contributors_count', 'title' => trans_choice('web::seat.contributor', 2)],
        ];
    }
}
