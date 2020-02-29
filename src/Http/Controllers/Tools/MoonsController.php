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

namespace Seat\Web\Http\Controllers\Tools;

use Seat\Eveapi\Models\Sde\MapDenormalize;
use Seat\Eveapi\Models\Universe\UniverseMoonContent;
use Seat\Services\ReportParser\Exceptions\InvalidReportException;
use Seat\Services\ReportParser\Parsers\MoonReport;
use Seat\Web\Http\Controllers\Controller;
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
            'ubiquitous' => UniverseMoonContent::ubiquitous()->count(),
            'common' => UniverseMoonContent::common()->count(),
            'uncommon' => UniverseMoonContent::uncommon()->count(),
            'rare' => UniverseMoonContent::rare()->count(),
            'exceptional' => UniverseMoonContent::exceptional()->count(),
            'standard' => UniverseMoonContent::standard()->count(),
        ];

        return $dataTable->render('web::tools.moons.list', compact('stats'));
    }

    /**
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(int $id)
    {
        $moon = MapDenormalize::with('moon_contents', 'moon_contents.type', 'moon_contents.type.materials',
            'moon_contents.type.materials.type', 'moon_contents.type.materials.type.reactions',
            'moon_contents.type.materials.type.reactions.price')->find($id);

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

                    if ($loop_first) {
                        $loop_first = false;

                        // search for any existing and outdated report regarding current moon
                        UniverseMoonContent::where('moon_id', (int) $component->moonID)
                            ->delete();
                    }

                    UniverseMoonContent::create([
                        'moon_id' => (int) $component->moonID,
                        'type_id' => (int) $component->oreTypeID,
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
