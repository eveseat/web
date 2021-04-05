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

namespace Seat\Web\Http\DataTables\Character\Industrial;

use Seat\Eveapi\Models\Character\CharacterAgentResearch;
use Yajra\DataTables\Services\DataTable;

/**
 * Class ResearchDataTable.
 *
 * @package Seat\Web\Http\DataTables\Character\Industrial
 */
class ResearchDataTable extends DataTable
{
    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function ajax()
    {
        return datatables()
            ->eloquent($this->applyScopes($this->query()))
            ->editColumn('started_at', function ($row) {
                return view('web::partials.date', ['datetime' => $row->started_at])->render();
            })
            ->editColumn('agent.name', function ($row) {
                return view('web::partials.character', ['character' => $row->agent])->render();
            })
            ->editColumn('skill.typeName', function ($row) {
                return view('web::partials.type', ['type_id' => $row->skill->typeID, 'type_name' => $row->skill->typeName])->render();
            })
            ->filterColumn('skill', function ($query, $keyword) {
                return $query->whereHas('skill', function ($sub_query) use ($keyword) {
                    return $sub_query->whereRaw('typeName LIKE ?', ["%$keyword%"]);
                });
            })
            ->rawColumns(['started_at', 'agent.name', 'skill.typeName'])
            ->make(true);
    }

    /**
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->parameters([
                'drawCallback' => 'function() { $("[data-toggle=tooltip]").tooltip(); }',
            ])
            ->postAjax([
                'data' => 'function(d) { d.characters = $("#dt-character-selector").val(); }',
            ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return CharacterAgentResearch::with('agent', 'skill');
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return [
            ['data' => 'started_at', 'title' => trans('web::research.start')],
            ['data' => 'agent.name', 'title' => trans('web::research.agent')],
            ['data' => 'skill.typeName', 'title' => trans('web::research.skill')],
            ['data' => 'points_per_day', 'title' => trans_choice('web::research.point_per_day', 0)],
            ['data' => 'remainder_points', 'title' => trans('web::research.remainder')],
        ];
    }
}
