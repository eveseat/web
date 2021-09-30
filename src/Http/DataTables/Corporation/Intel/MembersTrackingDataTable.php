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

namespace Seat\Web\Http\DataTables\Corporation\Intel;

use Seat\Eveapi\Models\Corporation\CorporationMemberTracking;
use Seat\Eveapi\Models\Sde\SolarSystem;
use Seat\Eveapi\Models\Sde\StaStation;
use Seat\Eveapi\Models\Universe\UniverseStructure;
use Seat\Web\Models\User;
use Yajra\DataTables\Services\DataTable;

/**
 * Class MembersTrackingDataTable.
 *
 * @package Seat\Web\Http\DataTables\Corporation\Intel
 */
class MembersTrackingDataTable extends DataTable
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
            ->editColumn('refresh_token.expires_on', function ($row) {
                return view('web::character.partials.token_status', ['refresh_token' => $row->refresh_token])
                    ->render();
            })
            ->editColumn('character.name', function ($row) {
                return view('web::partials.character', ['character' => $row->character])->render();
            })
            ->editColumn('ship.typeName', function ($row) {
                return view('web::partials.type', ['type_id' => $row->ship->typeID, 'type_name' => $row->ship->typeName])
                    ->render();
            })
            ->editColumn('start_date', function ($row) {
                return view('web::partials.date', ['datetime' => $row->start_date])->render();
            })
            ->editColumn('logon_date', function ($row) {
                return view('web::partials.date', ['datetime' => $row->logon_date])->render();
            })
            ->addColumn('location', function ($row) {
                return $row->location->name ?? trans('web::seat.unknown');
            })
            ->filterColumn('location', function ($query, $keyword) {
                $system_ids = collect();

                if(strlen($keyword) > 1) {
                    $system_ids = SolarSystem::where('name', 'like', '%' . $keyword . '%')
                        ->select('system_id')
                        ->get()
                        ->map(function ($system) {
                            return $system->system_id;
                        });
                }

                $station_ids = StaStation::where('stationName', 'like', '%' . $keyword . '%')->get()
                    ->map(function ($station) { return $station->stationID; });
                $structure_ids = UniverseStructure::where('name', 'like', '%' . $keyword . '%')->get()
                    ->map(function ($structure) { return $structure->structure_id; });

                $location_ids = array_merge($system_ids->toArray(), $station_ids->toArray(), $structure_ids->toArray());

                $query->whereIn('location_id', $location_ids);
            })
            ->filterColumn('character.name', function ($query, $keyword) {
                $characters = collect();

                // in case authenticated user is an admin, grant him search by main character
                // we prevent that general feature since a main can be outside a corporation
                // and therefore, a ceo, director or anything else than admin might not be granted
                // to get access to such information.
                if (auth()->user()->isAdmin()) {
                    $characters = User::where('name', 'like', "%{$keyword}%")->get()
                        ->map(function ($user) {
                            return $user->characters->pluck('character_id');
                        });
                }

                $query->whereIn('character_id', $characters->toArray())
                    ->orWhereHas('character', function ($query) use ($keyword) {
                        $query->where('name', 'like', "%{$keyword}%");
                    });
            })
            ->rawColumns(['refresh_token.expires_on', 'character.name', 'ship.typeName', 'start_date', 'logon_date'])
            ->make(true);
    }

    /**
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->postAjax([
                'data' => 'function(d) { d.filters = {}; $("[data-filter-field].dt-filters.active").each(function (i, e) { var a = $(e); var field = a.data("filter-field"); var value = a.data("filter-value"); if (! d.filters[field]) { d.filters[field] = []; } d.filters[field].push(value); }); }',
            ])
            ->columns($this->getColumns())
            ->orderBy(1, 'asc')
            ->parameters([
                'drawCallback' => 'function() { ids_to_names(); $("[data-toggle=tooltip]").tooltip(); }',
            ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return CorporationMemberTracking::with('character', 'refresh_token', 'ship');
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return [
            ['data' => 'refresh_token.expires_on', 'title' => trans('web::seat.token'), 'orderable' => false, 'searchable' => false],
            ['data' => 'character.name', 'title' => trans_choice('web::seat.name', 1)],
            ['data' => 'location', 'title' => trans('web::seat.location'), 'orderable' => false],
            ['data' => 'ship.typeName', 'title' => trans('web::seat.current_ship')],
            ['data' => 'start_date', 'title' => trans('web::seat.joined')],
            ['data' => 'logon_date', 'title' => trans('web::seat.last_login')],
        ];
    }
}
