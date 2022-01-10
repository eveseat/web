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

namespace Seat\Web\Http\Controllers\Corporation;

use Illuminate\Support\Collection;
use Seat\Eveapi\Models\Assets\CorporationAsset;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Eveapi\Models\Corporation\CorporationStarbase;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\Validation\StarbaseModule;

/**
 * Class StarbaseController.
 *
 * @package Seat\Web\Http\Controllers\Corporation
 */
class StarbaseController extends Controller
{
    /**
     * @param  \Seat\Eveapi\Models\Corporation\CorporationInfo  $corporation
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getStarbases(CorporationInfo $corporation)
    {

        // The basic strategy here is that we will first try and get
        // as much information as possible about the starbases.
        // After that we will take the list of starbases and
        // attempt to determine the fuel usage as well as
        // the tower name as per the assets list.
        $starbases = $this->getCorporationStarbases($corporation->corporation_id);
        $sheet = $corporation;

        return view('web::corporation.starbases', compact('corporation', 'sheet', 'starbases'));
    }

    /**
     * @param  \Seat\Web\Http\Validation\StarbaseModule  $request
     * @param  int  $corporation_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function postStarbaseModules(StarbaseModule $request, int $corporation_id)
    {

        $starbase = $this->getCorporationStarbases($corporation_id, $request->starbase_id)->first();
        $starbase_modules = $this->getStarbaseModules($corporation_id, $request->starbase_id);

        return view('web::corporation.starbase.ajax.modules-tab',
            compact('starbase', 'starbase_modules'));

    }

    /**
     * Return a list of starbases for a Corporation. If a starbaseID is provided,
     * then only data for that starbase is returned.
     *
     * @param  int  $corporation_id
     * @param  int  $starbase_id
     * @return Collection
     */
    private function getCorporationStarbases(int $corporation_id, ?int $starbase_id = null)
    {

        return CorporationStarbase::where('corporation_id', $corporation_id)->get();
    }

    /**
     * Retrieving all modules which are inside a starbase area (forcefield and maximum control range).
     *
     * @param  int  $corporation_id
     * @param  int  $starbase_id
     * @return Collection
     */
    private function getStarbaseModules(int $corporation_id, int $starbase_id): Collection
    {

        // retrieving starbase location
        $starbase = CorporationStarbase::where('starbase_id', $starbase_id)
            ->where('corporation_id', $corporation_id)
            ->first();

        // retrieving all modules candidate filtering on item located on same place than starbase
        // and starbase modules category (23)
        $candidates = CorporationAsset::join('invTypes', 'type_id', '=', 'typeID')
            ->join('invGroups', 'invGroups.groupID', '=', 'invTypes.groupID')
            ->where('corporation_id', $corporation_id)
            ->where('location_id', $starbase->system_id)
            ->where('categoryID', 23)
            ->where('item_id', '<>', $starbase_id)
            ->select('item_id', 'quantity', 'location_id', 'x', 'y', 'z', 'name', 'type_id', 'typeName', 'capacity', 'volume')
            ->get();

        // get maximum distance between starbase and module
        $max_structure_distance = 0.0;
        $attribute = $starbase->type->dogmaAttributes->where('attributeID', 650)->first();

        if (! is_null($attribute))
            $max_structure_distance = (is_null($attribute->valueFloat)) ? $attribute->valueInt : $attribute->valueFloat;

        // computing allowed starbase area
        $starbaseArea = [
            'x' => [$starbase->item->x - $max_structure_distance, $starbase->item->x + $max_structure_distance],
            'y' => [$starbase->item->y - $max_structure_distance, $starbase->item->y + $max_structure_distance],
            'z' => [$starbase->item->z - $max_structure_distance, $starbase->item->z + $max_structure_distance],
        ];

        // filtering candidates and keep only those which are inside the starbase area
        return $candidates->filter(function ($candidate) use ($starbaseArea) {

            if (is_null($candidate->x) || is_null($candidate->y) || is_null($candidate->z))
                return false;

            return $candidate->x >= $starbaseArea['x'][0] && $candidate->x <= $starbaseArea['x'][1] &&
                $candidate->y >= $starbaseArea['y'][0] && $candidate->y <= $starbaseArea['y'][1] &&
                $candidate->z >= $starbaseArea['z'][0] && $candidate->z <= $starbaseArea['z'][1];
        });
    }
}
