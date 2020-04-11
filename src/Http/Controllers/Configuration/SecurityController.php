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

namespace Seat\Web\Http\Controllers\Configuration;

use Seat\Services\Repositories\Configuration\SecurityRepository;
use Seat\Web\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;

/**
 * Class SecurityController.
 * @package Seat\Web\Http\Controllers\Configuration
 */
class SecurityController extends Controller
{
    use SecurityRepository;

    /**
     * @return \Illuminate\View\View
     */
    public function getLogs()
    {

        return view('web::configuration.security.logs');

    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getLogsData()
    {

        $logs = $this->getAllSecurityLogs();

        return DataTables::of($logs)
            ->editColumn('user', function ($row) {

                if ($row->user)
                    return $row->user->name;
            })
            ->make(true);
    }
}
