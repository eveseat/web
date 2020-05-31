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

namespace Seat\Web\Http\Controllers\Corporation;

use Illuminate\Database\Eloquent\Builder;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Eveapi\Models\Corporation\CorporationMemberTracking;
use Seat\Eveapi\Models\Sde\SolarSystem;
use Seat\Eveapi\Models\Sde\StaStation;
use Seat\Eveapi\Models\Universe\UniverseStructure;
use Seat\Web\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;

/**
 * Class TrackingController.
 *
 * @package Seat\Web\Http\Controllers\Corporation
 */
class TrackingController extends Controller
{
    /**
     * @param \Seat\Eveapi\Models\Corporation\CorporationInfo $corporation
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getTracking(CorporationInfo $corporation)
    {
        return view('web::corporation.tracking', compact('corporation'));
    }

    /**
     * @param \Seat\Eveapi\Models\Corporation\CorporationInfo $corporation
     *
     * @return mixed
     * @throws \Exception
     */
    public function getMemberTracking(CorporationInfo $corporation)
    {
        $selected_status = collect(request('selected_refresh_token_status'));

        $tracking = $this->getCorporationMemberTracking($corporation->corporation_id);

        if($selected_status->contains('valid_token'))
            $tracking->has('refresh_token');

        if($selected_status->contains('invalid_token'))
            $tracking->doesntHave('refresh_token');

        return DataTables::of($tracking)
            ->editColumn('refresh_token.user.main_character.name', function ($row) {
                if(is_null(optional($row)->refresh_token))
                    return '';

                return view('web::partials.character', ['character' => $row->refresh_token->user->main_character])->render();
            })
            ->editColumn('character.name', function ($row) {
                return view('web::partials.character', ['character' => $row->character])->render();
            })
            ->addColumn('location', function ($row) {
                return view('web::corporation.partials.location', compact('row'))->render();
            })
            ->editColumn('ship.typeName', function ($row) {
                if(is_null(optional($row)->ship))
                    return '';

                return view('web::partials.type', ['type_id' => $row->ship->typeID, 'type_name' => $row->ship->typeName])->render();
            })
            ->addColumn('refresh_token_status', function ($row) {

                $refresh_token = false;

                if(! is_null(optional($row)->refresh_token))
                    $refresh_token = true;

                return view('web::corporation.partials.refresh-token', compact('refresh_token'))->render();
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

                $station_ids = StaStation::where('stationName', 'like', '%' . $keyword . '%')->get()->map(function ($station) { return $station->stationID; });
                $structure_ids = UniverseStructure::where('name', 'like', '%' . $keyword . '%')->get()->map(function ($structure) { return $structure->structure_id; });

                $query->whereIn('location_id', array_merge($system_ids->toArray(), $station_ids->toArray(), $structure_ids->toArray()));

            })
            ->rawColumns([
                'refresh_token.user.main_character.name', 'character.name', 'location',
                'ship.typeName', 'refresh_token_status',
            ])
            ->make(true);

    }

    /**
     * Return the Member Tracking for a Corporation.
     *
     * @param int $corporation_id
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function getCorporationMemberTracking(int $corporation_id): Builder
    {

        return CorporationMemberTracking::with(
            'refresh_token',
            'refresh_token.user',
            'refresh_token.user.main_character',
            'ship',
            'character'
        )
            ->select('corporation_member_trackings.*')
            ->where('corporation_id', $corporation_id);
    }
}
