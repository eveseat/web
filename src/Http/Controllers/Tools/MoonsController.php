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

namespace Seat\Web\Http\Controllers\Tools;

use Seat\Eveapi\Models\Sde\MapDenormalize;
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

/**
 * Class MoonsController.
 *
 * @package Seat\Web\Http\Controllers\Tools
 */
class MoonsController extends Controller
{
    /**
     * @param \Seat\Web\Http\DataTables\Tools\MoonsDataTable $dataTable
     * @return mixed
     */
    public function index(MoonsDataTable $dataTable)
    {
        $stats = (object) [
            'ubiquitous' => MapDenormalize::ubiquitous()->count(),
            'common' => MapDenormalize::common()->count(),
            'uncommon' => MapDenormalize::uncommon()->count(),
            'rare' => MapDenormalize::rare()->count(),
            'exceptional' => MapDenormalize::exceptional()->count(),
            'standard' => MapDenormalize::standard()->count(),
        ];

        $groups = [
            MapDenormalize::UBIQUITOUS  => trans('web::moons.ubiquitous'),
            MapDenormalize::COMMON      => trans('web::moons.common'),
            MapDenormalize::UNCOMMON    => trans('web::moons.uncommon'),
            MapDenormalize::RARE        => trans('web::moons.rare'),
            MapDenormalize::EXCEPTIONAL => trans('web::moons.exceptional'),
        ];

        $region_id = intval(request()->query('region_id', 0));
        $constellation_id = intval(request()->query('constellation_id', 0));
        $system_id = intval(request()->query('system_id', 0));
        $rank_selection = request()->query('rank_selection', []);
        $product_selection = request()->query('product_selection', []);

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
                ->render('web::tools.moons.list', compact('stats', 'groups'));
    }

    /**
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(int $id)
    {
        $moon = MapDenormalize::with(
            'moon_content', 'moon_content.price', 'moon_content.materials', 'moon_content.materials.price',
            'moon_content.materials.reactions', 'moon_content.materials.reactions.components')
            ->find($id);

        return view('web::tools.moons.modals.components.content', compact('moon'));
    }

    /**
     * @param \Seat\Web\Http\Validation\ProbeReport $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ProbeReport $request)
    {
        $report = $request->input('moon-report');

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

                    $universe_moon = MapDenormalize::find($component->moonID);

                    if ($loop_first) {
                        $loop_first = false;

                        // search for any existing and outdated report regarding current moon
                        $universe_moon->moon_content()->detach();
                    }

                    $universe_moon->moon_content()->attach($component->oreTypeID, [
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
}
