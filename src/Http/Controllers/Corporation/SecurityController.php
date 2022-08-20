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

use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Eveapi\Models\Corporation\CorporationMemberTracking;
use Seat\Eveapi\Models\Corporation\CorporationTitle;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\DataTables\Corporation\Intel\LogDataTable;
use Seat\Web\Http\DataTables\Scopes\CorporationScope;

/**
 * Class ViewController.
 *
 * @package Seat\Web\Http\Controllers\Corporation
 */
class SecurityController extends Controller
{
    /**
     * @param  \Seat\Eveapi\Models\Corporation\CorporationInfo  $corporation
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getRoles(CorporationInfo $corporation)
    {

        $security = CorporationMemberTracking::with('roles', 'character')
            ->where('corporation_id', $corporation->corporation_id)
            ->get();

        return view('web::corporation.security.roles', compact('corporation', 'security'));
    }

    /**
     * @param  \Seat\Eveapi\Models\Corporation\CorporationInfo  $corporation
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getTitles(CorporationInfo $corporation)
    {

        $titles = CorporationTitle::with('characters')
            ->where('corporation_id', $corporation->corporation_id)
            ->get();

        return view('web::corporation.security.titles', compact('corporation', 'titles'));
    }

    /**
     * @param  \Seat\Eveapi\Models\Corporation\CorporationInfo  $corporation
     * @param  \Seat\Web\Http\DataTables\Corporation\Intel\LogDataTable  $dataTable
     * @return mixed
     */
    public function getLogs(CorporationInfo $corporation, LogDataTable $dataTable)
    {
        return $dataTable->addScope(new CorporationScope('corporation.security', [$corporation->corporation_id]))
            ->render('web::corporation.security.logs', compact('corporation'));
    }
}
