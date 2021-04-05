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

use Seat\Eveapi\Models\Corporation\CorporationDivision;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Web\Http\Controllers\Controller;

/**
 * Class SummaryController.
 *
 * @package Seat\Web\Http\Controllers\Corporation
 */
class SummaryController extends Controller
{
    /**
     * @param \Seat\Eveapi\Models\Corporation\CorporationInfo $corporation
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function show(CorporationInfo $corporation)
    {

        $sheet = $corporation;

        // Check if we managed to get any records for
        // this character. If not, redirect back with
        // an error.
        if (empty($sheet))
            return redirect()->back()
                ->with('error', trans('web::seat.unknown_corporation'));

        $asset_divisions = CorporationDivision::where('corporation_id', $corporation->corporation_id)
            ->where('type', 'hangar')
            ->orderBy('division')
            ->get();

        $wallet_divisions = CorporationDivision::where('corporation_id', $corporation->corporation_id)
            ->where('type', 'wallet')
            ->orderBy('division')
            ->get();

        $trackings = $corporation->characters->reject(function ($char) {
            return is_null($char->refresh_token);
        })->count();

        return view('web::corporation.summary',
            compact('corporation', 'sheet', 'asset_divisions', 'wallet_divisions', 'trackings'));

    }
}
