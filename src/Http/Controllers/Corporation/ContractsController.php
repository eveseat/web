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

use Seat\Eveapi\Models\Contracts\ContractDetail;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\DataTables\Corporation\Financial\ContractDataTable;
use Seat\Web\Http\DataTables\Scopes\CorporationScope;

/**
 * Class ContractsController.
 * @package Seat\Web\Http\Controllers\Corporation
 */
class ContractsController extends Controller
{
    /**
     * @param $corporation_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(int $corporation_id, ContractDataTable $dataTable)
    {

        return $dataTable->addScope(new CorporationScope([$corporation_id]))
            ->render('web::corporation.contracts');
    }

    /**
     * @param int $corporation_id
     * @param int $contract_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(int $corporation_id, int $contract_id)
    {
        $contract = ContractDetail::with(
            'acceptor',
            'assignee',
            'issuer',
            'lines',
            'lines.type',
            'lines.type.group',
            'start_location',
            'start_location.system',
            'start_location.system.region',
            'end_location',
            'end_location.system',
            'end_location.system.region'
        )->find($contract_id);

        return view('web::common.contracts.modals.details.content', compact('contract'));
    }
}
