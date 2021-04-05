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

namespace Seat\Web\Http\DataTables\Character\Intel;

use Illuminate\Support\Facades\Lang;
use Seat\Eveapi\Models\Calendar\CharacterCalendarEvent;
use Yajra\DataTables\Services\DataTable;

/**
 * Class CalendarDataTable.
 *
 * @package Seat\Web\Http\DataTables\Character\Intel
 */
class CalendarDataTable extends DataTable
{
    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function ajax()
    {
        return datatables()
            ->eloquent($this->applyScopes($this->query()))
            ->editColumn('event_date', function ($row) {
                return view('web::partials.date', ['datetime' => $row->event_date])->render();
            })
            ->editColumn('title', function ($row) {
                return view('web::character.calendar-detail', compact('row'))->render();
            })
            ->editColumn('event_response', function ($row) {
                return trans(sprintf('web::calendar.%s', $row->event_response));
            })
            ->editColumn('owner.name', function ($row) {
                switch ($row->detail->owner->category) {
                    case 'alliance':
                        return view('web::partials.alliance', ['alliance' => $row->detail->owner])->render();
                    case 'corporation':
                        return view('web::partials.corporation', ['corporation' => $row->detail->owner])->render();
                    case 'character':
                        return view('web::partials.character', ['character' => $row->detail->owner])->render();
                }

                return $row->detail->owner->name;
            })
            ->filterColumn('event_response', function ($query, $keyword) {
                $captions = Lang::get('web::calendar');
                $status = array_keys(array_filter($captions, function ($value) use ($keyword) {
                    return strpos(strtoupper($value), strtoupper($keyword)) !== false;
                }));

                $query->whereIn('event_response', $status);
            })
            ->rawColumns(['event_date', 'title', 'owner.name'])
            ->make(true);
    }

    /**
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->postAjax([
                'data' => 'function(d) { d.characters = $("#dt-character-selector").val(); }',
            ])
            ->parameters([
                'drawCallback' => 'function() { $("[data-toggle=tooltip]").tooltip(); $("[data-toggle=popover]").popover(); ids_to_names(); }',
            ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return CharacterCalendarEvent::with('detail', 'detail.owner', 'attendees');
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return [
            ['data' => 'event_date', 'title' => trans('web::calendar.date')],
            ['data' => 'owner.name', 'title' => trans('web::calendar.owner')],
            ['data' => 'title', 'title' => trans('web::calendar.description')],
            ['data' => 'event_response', 'title' => trans('web::calendar.status')],
        ];
    }
}
