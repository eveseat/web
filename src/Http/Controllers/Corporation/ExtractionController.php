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

namespace Seat\Web\Http\Controllers\Corporation;

use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Eveapi\Models\Industry\CorporationIndustryMiningExtraction;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Models\UniverseMoonReport;

/**
 * Class ExtractionController.
 * @package Seat\Web\Http\Controllers\Corporation
 */
class ExtractionController extends Controller
{
    /**
     * @param \Seat\Eveapi\Models\Corporation\CorporationInfo $corporation
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getExtractions(CorporationInfo $corporation)
    {
        // retrieve any valid extraction for the current corporation
        $moons = UniverseMoonReport::with(
            'content', 'moon', 'moon.solar_system', 'moon.constellation',
                'moon.region', 'moon.extraction', 'moon.extraction.structure', 'moon.extraction.structure.info'
            )->whereHas('moon.extraction.structure', function ($query) use ($corporation) {
                $query->where('corporation_id', $corporation->corporation_id);
            })->whereHas('moon.extraction', function ($query) {
                $query->where('natural_decay_time', '>', carbon()->subSeconds(CorporationIndustryMiningExtraction::THEORETICAL_DEPLETION_COUNTDOWN));
            })->get();

        return view('web::corporation.extraction.extraction', compact('moons', 'corporation'));
    }
}
