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

namespace Seat\Web\Http\Controllers\Alliance;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
use Seat\Eveapi\Models\Alliances\Alliance;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\DataTables\Alliance\AllianceDataTable;
use Seat\Web\Http\DataTables\Alliance\Intel\ContactDataTable;
use Seat\Web\Http\DataTables\Alliance\Intel\TrackingDataTable;
use Seat\Web\Http\DataTables\Scopes\AllianceScope;
use Seat\Web\Http\DataTables\Scopes\Filters\ContactCategoryScope;
use Seat\Web\Http\DataTables\Scopes\Filters\ContactStandingLevelScope;

/**
 * Class CorporationsController.
 *
 * @package Seat\Web\Http\Controllers\Corporation
 */
class AlliancesController extends Controller
{
    /**
     * @param  \Seat\Web\Http\DataTables\Alliance\AllianceDataTable  $dataTable
     * @return mixed
     */
    public function index(AllianceDataTable $dataTable)
    {
        if (Gate::allows('global.superuser'))
            return $dataTable->render('web::alliance.list');

        return $dataTable
            ->addScope(new AllianceScope)
            ->render('web::alliance.list');
    }

    /**
     * @param  \Seat\Eveapi\Models\Alliances\Alliance  $alliance
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show(Alliance $alliance)
    {
        // by default, redirect user to alliance summary
        if (Gate::allows('alliance.summary', $alliance))
            return redirect()->route('alliance.view.summary', [
                'alliance' => $alliance,
            ]);

        // collect all registered routes for corporation scope and sort them alphabetically
        $configured_routes = array_values(Arr::sort(config('package.alliance.menu'), function ($menu) {
            return $menu['name'];
        }));

        // for each route, check if the current user got a valid access and redirect him to the first valid entry
        foreach ($configured_routes as $menu) {
            $permissions = $menu['permission'];

            if (Gate::any(is_array($permissions) ? $permissions : [$permissions], $alliance)) {
                return redirect()->route($menu['route'], [
                    'alliance' => $alliance,
                ]);
            }
        }

        $message = sprintf('Request to %s was denied by the alliancebouncer.', request()->path());

        event('security.log', [$message, 'authorization']);

        // Redirect away from the original request
        return redirect()->route('auth.unauthorized');
    }

    /**
     * @param  \Seat\Eveapi\Models\Alliances\Alliance  $alliance
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Exception
     */
    public function destroy(Alliance $alliance)
    {
        $alliance->delete();

        return redirect()->back()
            ->with('success', sprintf('Alliance %s has been successfully removed.', $corporation->name));
    }

    /**
     * @param  \Seat\Eveapi\Models\Alliance\Alliance  $alliance
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function showSummary(Alliance $alliance)
    {

        $sheet = $alliance;

        // Check if we managed to get any records for
        // this alliance. If not, redirect back with
        // an error.
        if (empty($sheet))
            return redirect()->back()
                ->with('error', trans('web::seat.unknown_alliance'));

        $tracked = 0;
        $trackings = $alliance->corporations->each(function ($corp, $key) use (&$tracked) {
            $tracked += $corp->characters->reject(function ($char) {
                return is_null($char->refresh_token);
            })->count();
        });

        return view('web::alliance.summary',
            compact('alliance', 'sheet', 'tracked'));

    }

    /**
     * @param  \Seat\Eveapi\Models\Alliance\Alliance  $alliance
     * @param  \Seat\Web\Http\DataTables\Alliances\Intel\ContactDataTable  $dataTable
     * @return mixed
     */
    public function showContacts(Alliance $alliance, ContactDataTable $dataTable)
    {

        return $dataTable->addScope(new AllianceScope('alliance.contact', [$alliance->alliance_id]))
            ->addScope(new ContactCategoryScope(request()->input('filters.category')))
            ->addScope(new ContactStandingLevelScope(request()->input('filters.standing')))
            ->render('web::alliance.contacts', compact('alliance'));
    }

    /**
     * @param  \Seat\Eveapi\Models\Corporation\CorporationInfo  $corporation
     * @param  \Seat\Web\Http\DataTables\Alliances\TrackingDataTable  $dataTable
     * @return mixed
     */
    public function showTracking(Alliance $alliance, TrackingDataTable $dataTable)
    {
        return $dataTable->addScope(new AllianceScope('alliance.tracking', [$alliance->alliance_id]))
            ->render('web::alliance.tracking', compact('alliance'));
    }
}
