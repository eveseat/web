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

namespace Seat\Web\Http\Controllers\Tools;

use Seat\Eveapi\Models\Sde\Moon;
use Seat\Services\ReportParser\Exceptions\InvalidReportException;
use Seat\Services\ReportParser\Parsers\MoonReport;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\DataTables\Scopes\Filters\ConstellationScope;
use Seat\Web\Http\DataTables\Scopes\Filters\MoonProductScope;
use Seat\Web\Http\DataTables\Scopes\Filters\MoonRankScope;
use Seat\Web\Http\DataTables\Scopes\Filters\RegionScope;
use Seat\Web\Http\DataTables\Scopes\Filters\SystemScope;
use Seat\Web\Http\DataTables\Tools\MoonsDataTable;
use Seat\Web\Http\Validation\ProbeReport;
use Seat\Web\Models\UniverseMoonReport;

/**
 * Class MoonsController.
 *
 * @package Seat\Web\Http\Controllers\Tools
 */
class MoonsController extends Controller
{
    /**
     * @param  \Seat\Web\Http\DataTables\Tools\MoonsDataTable  $dataTable
     * @return mixed
     */
    public function index(MoonsDataTable $dataTable)
    {
        $groups = [
            Moon::UBIQUITOUS  => trans('web::moons.ubiquitous'),
            Moon::COMMON      => trans('web::moons.common'),
            Moon::UNCOMMON    => trans('web::moons.uncommon'),
            Moon::RARE        => trans('web::moons.rare'),
            Moon::EXCEPTIONAL => trans('web::moons.exceptional'),
        ];

        $region_id = intval(request()->input('region_id', 0));
        $constellation_id = intval(request()->input('constellation_id', 0));
        $system_id = intval(request()->input('system_id', 0));
        $rank_selection = request()->input('rank_selection', []);
        $product_selection = request()->input('product_selection', []);

        if ($region_id != 0)
            $dataTable->addScope(new RegionScope($region_id));

        if ($constellation_id != 0)
            $dataTable->addScope(new ConstellationScope($constellation_id));

        if ($system_id != 0)
            $dataTable->addScope(new SystemScope($system_id));

        if (! empty($rank_selection))
            $dataTable->addScope(new MoonRankScope($rank_selection));

        if (! empty($product_selection))
            $dataTable->addScope(new MoonProductScope($product_selection));

        return $dataTable
                ->render('web::tools.moons.list', compact('groups'));
    }

    /**
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(int $id)
    {
        $moon = UniverseMoonReport::with(
            'content', 'content.price', 'content.materials', 'content.materials.price',
            'content.materials.reactions', 'content.materials.reactions.components')
            ->find($id);

        return view('web::tools.moons.modals.components.content', compact('moon'));
    }

    /**
     * @param  \Seat\Web\Http\Validation\ProbeReport  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ProbeReport $request)
    {
        $report = $request->input('moon-report');

        // enforce tabulation spacer
        $report = preg_replace('/[ ]{4}/', "\t", $report);

        $parser = new MoonReport();
        $parser->parse($report);

        // ensure the report is valid
        try {
            $parser->validate();

            // iterate over each moons
            foreach ($parser->getGroups() as $moon) {

                $loop_first = true;

                // iterate over each moons components
                foreach ($moon->getElements() as $component) {
                    if ($loop_first) {
                        $loop_first = false;
                        $universe_moon = UniverseMoonReport::firstOrNew(['moon_id' => $component->moonID]);
                        $universe_moon->user_id = auth()->user()->getAuthIdentifier();
                        $universe_moon->updated_at = now();
                        $universe_moon->save();

                        // search for any existing and outdated report regarding current moon
                        $universe_moon->content()->detach();
                    }

                    $universe_moon->content()->attach($component->oreTypeID, [
                        'rate' => (float) $component->quantity,
                    ]);
                }
            }
        } catch (InvalidReportException $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }

        return redirect()->back()
            ->with('success', trans('web::seat.probe_report_posted', ['lines' => count($parser->getGroups())]));
    }

    /**
     * @param  \Seat\Web\Models\UniverseMoonReport  $report
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Exception
     */
    public function destroy(UniverseMoonReport $report)
    {
        $report->content()->detach();
        $report->delete();

        return redirect()->back();
    }
}
