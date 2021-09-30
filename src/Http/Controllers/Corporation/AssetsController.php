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
use Seat\Eveapi\Models\Assets\CorporationAsset;
use Seat\Eveapi\Models\Corporation\CorporationDivision;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\DataTables\Corporation\Intel\Assets\DataTable;
use Seat\Web\Http\DataTables\Scopes\CorporationAssetDivisionsScope;
use Seat\Web\Http\DataTables\Scopes\CorporationScope;

/**
 * Class AssetsController.
 *
 * @package Seat\Web\Http\Controllers\Corporation
 */
class AssetsController extends Controller
{
    /**
     * @param  \Seat\Eveapi\Models\Corporation\CorporationInfo  $corporation
     * @param  \Seat\Web\Http\DataTables\Corporation\Intel\Assets\DataTable  $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAssets(CorporationInfo $corporation, DataTable $dataTable)
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

        return $dataTable->addScope(new CorporationScope('corporation.asset', [$corporation->corporation_id]))
            ->addScope(new CorporationAssetDivisionsScope($division_ids))
            ->render('web::corporation.assets.assets', compact('corporation'));
    }

    /**
     * @param  \Seat\Eveapi\Models\Corporation\CorporationInfo  $corporation
     * @param  int  $item_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getFitting(CorporationInfo $corporation, int $item_id)
    {
        $asset = CorporationAsset::find($item_id);

        return view('web::common.assets.modals.fitting.content', compact('asset'));
    }

    /**
     * @param  \Seat\Eveapi\Models\Corporation\CorporationInfo  $corporation
     * @param  int  $item_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getContainer(CorporationInfo $corporation, int $item_id)
    {
        $asset = CorporationAsset::find($item_id);
        $divisions = CorporationDivision::where('corporation_id', $corporation->corporation_id)->where('type', 'hangar')->get();

        return view('web::corporation.assets.modals.container.content', compact('asset', 'divisions'));
    }
}
