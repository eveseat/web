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

namespace Seat\Web\Http\DataTables\Squads;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Seat\Web\Models\Squads\Squad;
use Yajra\DataTables\Services\DataTable;

/**
 * Class SquadsDataTable.
 *
 * @package Seat\Web\Http\DataTables\Squads
 */
class SquadsDataTable extends DataTable
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return datatables()
            ->eloquent($this->applyScopes($this->query()))
            ->addColumn('is_candidate', function ($row) {
                return view('web::squads.partials.yes_no', ['value' => $row->isCandidate()])->render();
            })
            ->addColumn('is_member', function ($row) {
                return view('web::squads.partials.yes_no', ['value' => $row->isMember()])->render();
            })
            ->addColumn('is_moderator', function ($row) {
                return view('web::squads.partials.yes_no', ['value' => $row->isModerator()])->render();
            })
            ->editColumn('name', function ($row) {
                return $row->name;
            })
            ->editColumn('description', function ($row) {
                return Str::limit(strip_tags($row->description));
            })
            ->editColumn('type', function ($row) {
                return view('web::squads.partials.type', compact('row'))->render();
            })
            ->editColumn('is_moderated', function ($row) {
                return view('web::squads.partials.yes_no', ['value' => $row->is_moderated])->render();
            })
            ->editColumn('members', function ($row) {
                return $row->members->count();
            })
            ->editColumn('moderators', function ($row) {
                return $row->moderators->count();
            })
            ->editColumn('action', function ($row) {
                return view('web::squads.buttons.squads.action', compact('row'))->render();
            })
            ->orderColumn('members', function ($query, $order) {
                $query->select('id', 'name', 'description', 'type', 'is_moderated')
                    ->leftJoin('squad_member', 'id', 'squad_id')
                    ->orderBy(DB::raw('COUNT(squad_id)'), $order)
                    ->groupBy('id', 'name', 'description', 'type', 'is_moderated');
            })
            ->orderColumn('moderators', function ($query, $order) {
                $query->select('id', 'name', 'description', 'type', 'is_moderated')
                    ->leftJoin('squad_moderator', 'id', 'squad_id')
                    ->orderBy(DB::raw('COUNT(squad_id)'), $order)
                    ->groupBy('id', 'name', 'description', 'type', 'is_moderated');
            })
            ->rawColumns(['is_candidate', 'is_member', 'is_moderator', 'is_moderated', 'action'])
            ->make(true);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return Squad::with('members', 'moderators', 'applications');
    }

    /**
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->postAjax()
            ->columns($this->columns())
            ->addAction();
    }

    /**
     * @return array
     */
    public function columns()
    {
        return [
            ['data' => 'name', 'title' => trans_choice('web::squads.name', 1)],
            ['data' => 'description', 'title' => trans('web::squads.description')],
            ['data' => 'type', 'title' => trans('web::squads.type')],
            ['data' => 'members', 'title' => trans_choice('web::squads.member', 0), 'searchable' => false],
            ['data' => 'moderators', 'title' => trans_choice('web::squads.moderator', 0), 'searchable' => false],
            ['data' => 'is_moderated', 'title' => trans('web::squads.moderated'), 'searchable' => false],
            ['data' => 'is_candidate', 'title' => trans_choice('web::squads.candidate', 1), 'searchable' => false, 'orderable' => false],
            ['data' => 'is_member', 'title' => trans_choice('web::squads.member', 1), 'searchable' => false, 'orderable' => false],
            ['data' => 'is_moderator', 'title' => trans_choice('web::squads.moderator', 1), 'searchable' => false, 'orderable' => false],
        ];
    }
}
