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

use Illuminate\Support\Facades\Gate;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\DataTables\Corporation\Industrial\Blueprints\DataTable;
use Seat\Web\Http\DataTables\Scopes\CorporationAssetDivisionsScope;
use Seat\Web\Http\DataTables\Scopes\CorporationScope;
use Seat\Web\Http\DataTables\Scopes\Filters\BlueprintTypeScope;

/**
 * Class BlueprintController.
 *
 * @package Seat\Web\Http\Controllers\Character
 */
class BlueprintController extends Controller
{
    /**
     * @param  \Seat\Eveapi\Models\Corporation\CorporationInfo  $corporation
     * @param  \Seat\Web\Http\DataTables\Corporation\Industrial\Blueprints\DataTable  $dataTable
     * @return mixed
     */
    public function index(CorporationInfo $corporation, DataTable $dataTable)
    {
        $division_ids = [];
        $division_permissions = [
            'asset_first_division', 'asset_second_division', 'asset_third_division', 'asset_fourth_division',
            'asset_fifth_division', 'asset_sixth_division', 'asset_seventh_division',
        ];

        foreach ($division_permissions as $key => $permission) {
            $ability = sprintf('corporation.%s', $permission);

            if (Gate::allows($ability, $corporation))
                array_push($division_ids, ($key + 1));
        }

        return $dataTable->addScope(new CorporationScope('corporation.blueprint', [$corporation->corporation_id]))
            ->addScope(new CorporationAssetDivisionsScope($division_ids))
            ->addScope(new BlueprintTypeScope(request()->input('filters.type')))
            ->render('web::corporation.blueprint', compact('corporation'));
    }
}
