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

use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Services\Repositories\Corporation\Corporation;
use Seat\Web\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;

/**
 * Class CorporationsController.
 * @package Seat\Web\Http\Controllers\Corporation
 */
class CorporationsController extends Controller
{
    use Corporation;

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getCorporation()
    {
        // by default, redirect user to corporation sheet
        if (auth()->user()->has('corporation.summary'))
            return redirect()->route('corporation.view.summary', [
                'corporation_id' => request()->corporation_id,
            ]);

        // collect all registered routes for corporation scope and sort them alphabetically
        $configured_routes = array_values(array_sort(config('package.corporation.menu'), function ($menu) {
            return $menu['name'];
        }));

        // for each route, check if the current user got a valid access and redirect him to the first valid entry
        foreach ($configured_routes as $menu) {
            $permissions = $menu['permission'];

            if (! is_array($permissions))
                $permissions = [$permissions];

            foreach ($permissions as $permission) {
                if (auth()->user()->has($permission))
                    return redirect()->route($menu['route'], [
                        'corporation_id' => request()->corporation_id,
                    ]);
            }
        }

        $message = sprintf('Request to %s was denied by the corporationbouncer.', request()->path());

        event('security.log', [$message, 'authorization']);

        // Redirect away from the original request
        return redirect()->route('auth.unauthorized');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCorporations()
    {

        return view('web::corporation.list');

    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getCorporationsData()
    {

        $corporations = $this->getAllCorporationsWithAffiliationsAndFilters();

        return DataTables::of($corporations)
            // Edit some columns to include links and icons
            ->editColumn('name', function ($row) {

                return view('web::corporation.partials.corporationname', compact('row'))
                    ->render();
            })
            ->editColumn('ceo_id', function ($row) {

                return view('web::corporation.partials.ceoname', compact('row'))
                    ->render();
            })
            ->editColumn('alliance_id', function ($row) {

                return view('web::corporation.partials.alliancename', compact('row'))
                    ->render();
            })
            ->editColumn('tax_rate', function ($row) {

                return sprintf('%d%%', $row->tax_rate * 100);
            })
            ->editColumn('actions', function ($row) {

                return view('web::corporation.partials.delete', compact('row'))
                    ->render();
            })
            ->rawColumns(['name', 'ceo_id', 'alliance_id', 'actions'])
            ->make(true);
    }

    /**
     * @param int $corporation_id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteCorporation(int $corporation_id)
    {

        CorporationInfo::find($corporation_id)->delete();

        return redirect()->back()->with(
            'success', 'Corporation deleted!'
        );
    }
}
