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

namespace Seat\Web\Http\Controllers\Corporation;

use Seat\Eveapi\Models\Sde\MapDenormalize;
use Seat\Eveapi\Models\Sde\StaStation;
use Seat\Eveapi\Models\Universe\UniverseStructure;
use Seat\Services\Repositories\Corporation\Members;
use Seat\Web\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;

class TrackingController extends Controller
{
    use Members;

    /**
     * @param int $corporation_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getTracking(int $corporation_id)
    {

        return view('web::corporation.tracking');
    }

    /**
     * @param int $corporation_id
     *
     * @return mixed
     * @throws \Exception
     */
    public function getMemberTracking(int $corporation_id)
    {

        $selected_status = collect(request('selected_refresh_token_status'));

        $tracking = $this->getCorporationMemberTracking($corporation_id);

        if($selected_status->contains('valid_token'))
            $tracking->has('refresh_token');

        if($selected_status->contains('invalid_token'))
            $tracking->doesntHave('refresh_token');

        return DataTables::of($tracking)
            ->editColumn('refresh_token.user.main_character.name', function ($row) {
                if(is_null(optional($row)->refresh_token))
                    return '';

                return view('web::partials.character', ['character' => $row->refresh_token->user->main_character]);
            })
            ->editColumn('character.name', function ($row) {
                return view('web::partials.character', ['character' => $row->character]);
            })
            ->addColumn('location', function ($row) {
                return view('web::corporation.partials.location', compact('row'));
            })
            ->editColumn('ship.typeName', function ($row) {
                if(is_null(optional($row)->ship))
                    return '';

                return view('web::partials.type', ['type_id' => $row->ship->typeID, 'type_name' => $row->ship->typeName]);
            })
            ->addColumn('refresh_token_status', function ($row) {

                $refresh_token = false;

                if(! is_null(optional($row)->refresh_token))
                    $refresh_token = true;

                return view('web::corporation.partials.refresh-token', compact('refresh_token'));
            })
            ->filterColumn('location', function ($query, $keyword) {

                $system_ids = collect();

                if(strlen($keyword) > 1)
                    $system_ids = MapDenormalize::where('itemName', 'like', '%' . $keyword . '%')->select('itemID')->get()->map(function ($system) { return $system->itemID; });

                $station_ids = StaStation::where('stationName', 'like', '%' . $keyword . '%')->get()->map(function ($station) { return $station->stationID; });
                $structure_ids = UniverseStructure::where('name', 'like', '%' . $keyword . '%')->get()->map(function ($structure) { return $structure->structure_id; });

                $query->whereIn('location_id', array_merge($system_ids->toArray(), $station_ids->toArray(), $structure_ids->toArray()));

            })
            ->rawColumns(['refresh_token_status', 'location'])
            ->make(true);

    }
}
