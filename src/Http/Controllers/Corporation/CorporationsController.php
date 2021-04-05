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

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\DataTables\Corporation\CorporationDataTable;
use Seat\Web\Http\DataTables\Scopes\CorporationScope;

/**
 * Class CorporationsController.
 * @package Seat\Web\Http\Controllers\Corporation
 */
class CorporationsController extends Controller
{
    /**
     * @param \Seat\Web\Http\DataTables\Corporation\CorporationDataTable $dataTable
     *
     * @return mixed
     */
    public function index(CorporationDataTable $dataTable)
    {
        if (Gate::allows('global.superuser'))
            return $dataTable->render('web::corporation.list');

        return $dataTable
            ->addScope(new CorporationScope)
            ->render('web::corporation.list');
    }

    /**
     * @param \Seat\Eveapi\Models\Corporation\CorporationInfo $corporation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show(CorporationInfo $corporation)
    {
        // by default, redirect user to corporation sheet
        if (Gate::allows('corporation.summary', $corporation))
            return redirect()->route('corporation.view.summary', [
                'corporation' => $corporation,
            ]);

        // collect all registered routes for corporation scope and sort them alphabetically
        $configured_routes = array_values(Arr::sort(config('package.corporation.menu'), function ($menu) {
            return $menu['name'];
        }));

        // for each route, check if the current user got a valid access and redirect him to the first valid entry
        foreach ($configured_routes as $menu) {
            $permissions = $menu['permission'];

            if (Gate::any(is_array($permissions) ? $permissions : [$permissions], $corporation)) {
                return redirect()->route($menu['route'], [
                    'corporation' => $corporation,
                ]);
            }
        }

        $message = sprintf('Request to %s was denied by the corporationbouncer.', request()->path());

        event('security.log', [$message, 'authorization']);

        // Redirect away from the original request
        return redirect()->route('auth.unauthorized');
    }

    /**
     * @param \Seat\Eveapi\Models\Corporation\CorporationInfo $corporation
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(CorporationInfo $corporation)
    {
        $corporation->delete();

        return redirect()->back()
            ->with('success', sprintf('Corporation %s has been successfully removed.', $corporation->name));
    }
}
