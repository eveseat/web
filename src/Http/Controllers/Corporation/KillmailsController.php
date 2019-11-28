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

use Seat\Services\Repositories\Corporation\Killmails;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\DataTables\Corporation\Military\KillMailDataTable;
use Seat\Web\Http\DataTables\Scopes\KillMailCorporationScope;

/**
 * Class KillmailsController.
 * @package Seat\Web\Http\Controllers\Corporation
 */
class KillmailsController extends Controller
{
    use Killmails;

    /**
     * @param int $corporation_id
     * @param \Seat\Web\Http\DataTables\Corporation\Military\KillMailDataTable $dataTable
     * @return mixed
     */
    public function index(int $corporation_id, KillMailDataTable $dataTable)
    {

        return $dataTable->addScope(new KillMailCorporationScope([$corporation_id]))
            ->render('web::corporation.killmails');
    }
}
