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

use Seat\Eveapi\Models\Character\CharacterNotification;
use Yajra\DataTables\Services\DataTable;

/**
 * Class NotificationDataTable.
 *
 * @package Seat\Web\Http\DataTables\Character\Intel
 */
class NotificationDataTable extends DataTable
{
    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function ajax()
    {
        return datatables()
            ->eloquent($this->applyScopes($this->query()))
            ->editColumn('timestamp', function ($row) {
                return view('web::partials.date', ['datetime' => $row->timestamp])->render();
            })
            ->editColumn('type', function ($row) {
                return view('web::character.notification', compact('row'))->render();
            })
            ->editColumn('sender.name', function ($row) {
                switch ($row->sender->category) {
                    case 'alliance':
                        return view('web::partials.alliance', ['alliance' => $row->sender])->render();
                    case 'corporation':
                        return view('web::partials.corporation', ['corporation' => $row->sender])->render();
                    case 'character':
                        return view('web::partials.character', ['character' => $row->sender])->render();
                }

                return $row->sender->name;
            })
            ->rawColumns(['timestamp', 'type', 'sender.name'])
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
                'drawCallback' => 'function() { $("[data-toggle=tooltip]").tooltip(); $("[data-toggle=popover]").popover(); ids_to_names(); }',
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
        return CharacterNotification::with('sender');
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return [
            ['data' => 'timestamp', 'title' => trans('web::notifications.date')],
            ['data' => 'sender.name', 'title' => trans('web::notifications.sender')],
            ['data' => 'type', 'title' => trans('web::notifications.type')],
        ];
    }
}
