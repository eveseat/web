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

use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Sde\MapDenormalize;
use Seat\Eveapi\Models\Sde\StaStation;
use Seat\Eveapi\Models\Universe\UniverseName;
use Seat\Eveapi\Models\Universe\UniverseStructure;
use Seat\Services\Repositories\Corporation\Members;
use Seat\Web\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;

class TrackingController extends Controller
{
    use Members;

    /**
     * @param $corporation_id
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
            $tracking->has('user.refresh_token');

        if($selected_status->contains('invalid_token'))
            $tracking->doesntHave('user.refresh_token');

        if($selected_status->contains('missing_users'))
            $tracking->doesntHave('user');

        return DataTables::of($tracking)
            ->editColumn('character_id', function ($row) {

                $character_id = $row->character_id;

                $character = CharacterInfo::find($row->character_id) ?: $row->character_id;

                return view('web::partials.character', compact('character', 'character_id'));
            })
            ->addColumn('location', function ($row) {
                return view('web::corporation.partials.location', compact('row'));
            })

            ->addColumn('refresh_token', function ($row) {

                $refresh_token = false;

                if(! is_null(optional($row->user)->refresh_token))
                    $refresh_token = true;

                return view('web::corporation.partials.refresh-token', compact('refresh_token'));
            })
            ->addColumn('main_character', function ($row) {

                $character_id = $row->character_id;

                if(is_null($row->user))
                    return '';

                $main_character_id = $character_id;

                if (! is_null($row->user->group) && ! is_null(optional($row->user->group)->main_character_id))
                    $main_character_id = $row->user->group->main_character_id;

                $character = CharacterInfo::find($main_character_id) ?: $main_character_id;

                return view('web::partials.character', compact('character', 'character_id'));
            })
            ->filterColumn('character_id', function ($query, $keyword) {
                $resolved_ids = UniverseName::where('name', 'like', '%' . $keyword . '%')->get()->map(function ($resolved_id) { return $resolved_id->entity_id; });
                $character_info_ids = CharacterInfo::where('name', 'like', '%' . $keyword . '%')->get()->map(function ($character_info) { return $character_info->character_id; });
                $query->whereIn('character_id', array_merge($resolved_ids->toArray(), $character_info_ids->toArray()));
            })
            ->filterColumn('location', function ($query, $keyword) {

                $system_ids = collect();

                if(strlen($keyword) > 1)
                    $system_ids = MapDenormalize::where('itemName', 'like', '%' . $keyword . '%')->select('itemID')->get()->map(function ($system) { return $system->itemID; });

                $station_ids = StaStation::where('stationName', 'like', '%' . $keyword . '%')->get()->map(function ($station) { return $station->stationID; });
                $structure_ids = UniverseStructure::where('name', 'like', '%' . $keyword . '%')->get()->map(function ($structure) { return $structure->structure_id; });

                $query->whereIn('location_id', array_merge($system_ids->toArray(), $station_ids->toArray(), $structure_ids->toArray()));

            })
            ->rawColumns(['character_id', 'main_character', 'refresh_token', 'location'])
            ->make(true);

    }
}
