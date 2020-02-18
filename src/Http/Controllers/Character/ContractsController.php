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

namespace Seat\Web\Http\Controllers\Character;

use Seat\Eveapi\Models\Contracts\ContractDetail;
use Seat\Eveapi\Models\RefreshToken;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\DataTables\Character\Financial\ContractDataTable;
use Seat\Web\Http\DataTables\Scopes\CharacterScope;
use Seat\Web\Http\DataTables\Scopes\Filters\ContractStatusScope;
use Seat\Web\Http\DataTables\Scopes\Filters\ContractTypeScope;
use Seat\Web\Models\User;

/**
 * Class ContractsController.
 * @package Seat\Web\Http\Controllers\Character
 */
class ContractsController extends Controller
{
    /**
     * @param int $character_id
     * @param \Seat\Web\Http\DataTables\Character\Financial\ContractDataTable $dataTable
     * @return mixed
     */
    public function index(int $character_id, ContractDataTable $dataTable)
    {
        $token = RefreshToken::where('character_id', $character_id)->first();
        $characters = collect();
        if ($token) {
            $characters = User::with('characters')->find($token->user_id)->characters;
        }

        return $dataTable
            ->addScope(new CharacterScope('character.contract', $character_id, request()->input('characters')))
            ->addScope(new ContractTypeScope(request()->input('filters.type')))
            ->addScope(new ContractStatusScope(request()->input('filters.status')))
            ->render('web::character.contracts', compact('characters'));
    }

    /**
     * @param int $character_id
     * @param int $contract_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(int $character_id, int $contract_id)
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
