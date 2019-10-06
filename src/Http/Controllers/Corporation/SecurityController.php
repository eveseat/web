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

use Seat\Services\Repositories\Corporation\Corporation;
use Seat\Services\Repositories\Corporation\Security;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\DataTables\Corporation\Intel\LogDataTable;
use Seat\Web\Http\DataTables\Scopes\CorporationScope;

/**
 * Class ViewController.
 * @package Seat\Web\Http\Controllers\Corporation
 */
class SecurityController extends Controller
{
    use Corporation;
    use Security;

    /**
     * @param $corporation_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getRoles(int $corporation_id)
    {

        $security = $this->getCorporationMemberRoles($corporation_id);

        return view('web::corporation.security.roles', compact('security'));
    }

    /**
     * @param $corporation_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getTitles(int $corporation_id)
    {

        $titles = $this->getCorporationMemberSecurityTitles($corporation_id);

        return view('web::corporation.security.titles', compact('titles'));
    }

    /**
     * @param $corporation_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getLogs(int $corporation_id, LogDataTable $dataTable)
    {

        return $dataTable->addScope(new CorporationScope([$corporation_id]))
            ->render('web::corporation.security.logs');
    }
}
