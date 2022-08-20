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

use Illuminate\Support\Facades\Gate;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\DataTables\Corporation\Financial\MarketDataTable;
use Seat\Web\Http\DataTables\Scopes\CorporationMarketDivisionsScope;
use Seat\Web\Http\DataTables\Scopes\CorporationScope;
use Seat\Web\Http\DataTables\Scopes\Filters\MarketOrderTypeScope;
use Seat\Web\Http\DataTables\Scopes\Filters\MarketStatusScope;

/**
 * Class MarketController.
 *
 * @package Seat\Web\Http\Controllers\Corporation
 */
class MarketController extends Controller
{
    /**
     * @param  \Seat\Eveapi\Models\Corporation\CorporationInfo  $corporation
     * @param  \Seat\Web\Http\DataTables\Corporation\Financial\MarketDataTable  $dataTable
     * @return mixed
     */
    public function index(CorporationInfo $corporation, MarketDataTable $dataTable)
    {
        $division_ids = [];
        $division_permissions = [
            'wallet_first_division', 'wallet_second_division', 'wallet_third_division', 'wallet_fourth_division',
            'wallet_fifth_division', 'wallet_sixth_division', 'wallet_seventh_division',
        ];

        foreach ($division_permissions as $key => $permission) {
            $ability = sprintf('corporation.%s', $permission);

            if (Gate::allows($ability, $corporation))
                array_push($division_ids, ($key + 1));
        }

        return $dataTable->addScope(new CorporationScope('corporation.market', [$corporation->corporation_id]))
            ->addScope(new MarketStatusScope(request()->input('filters.status')))
            ->addScope(new MarketOrderTypeScope(request()->input('filters.type')))
            ->addScope(new CorporationMarketDivisionsScope($division_ids))
            ->render('web::corporation.market', compact('corporation'));
    }
}
