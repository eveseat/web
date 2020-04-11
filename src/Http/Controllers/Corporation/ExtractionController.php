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

namespace Seat\Web\Http\Controllers\Corporation;

use Illuminate\Database\QueryException;
use Seat\Eveapi\Models\Universe\UniverseMoonContent;
use Seat\Services\Repositories\Corporation\Extractions;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\Validation\ProbeReport;

/**
 * Class ExtractionController.
 * @package Seat\Web\Http\Controllers\Corporation
 */
class ExtractionController extends Controller
{
    use Extractions;

    /**
     * @param int $corporation_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getExtractions(int $corporation_id)
    {
        // retrieve any valid extraction for the current corporation
        $extractions = $this->getCorporationExtractions($corporation_id)->get();

        return view('web::corporation.extraction.extraction', compact('extractions'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postProbeReport(ProbeReport $request)
    {
        $moons = [];

        $report = $request->input('moon-report');

        $processed_counter = 0;

        $report_lines = explode(PHP_EOL, $report);

        foreach ($report_lines as $line) {
            $fields = explode("\t", $line);

            if ($fields[0] !== '')
                continue;

            if (count($fields) !== 7) {
                logger()->debug('Inconsistent probe report has been posted.', [
                    'report'      => $report,
                    'line'        => $line,
                    'parsed_line' => $fields,
                ]);

                return redirect()->back()
                    ->with('error', trans('web::seat.wrong_probe_report_structure', ['fields_number' => 7]));
            }

            try {
                // if the moon related to the current line has not been processed yet
                // delete any existing records regarding it
                if (! in_array($fields[6], $moons)) {
                    UniverseMoonContent::where('moon_id', (int) $fields[6])
                        ->delete();

                    array_push($moons, intval($fields[6]));
                }

                // create new moon content
                UniverseMoonContent::create([
                    'moon_id' => (int) $fields[6],
                    'type_id' => (int) $fields[3],
                    'rate'    => (float) $fields[2],
                ]);

                $processed_counter++;
            } catch (QueryException $e) {
                logger()->error($e->getMessage(), $e->getTrace());

                return redirect()->back()
                    ->with('error', trans('web::seat.insert_error_probe_report'));
            }
        }

        return redirect()->back()
            ->with('success', trans('web::seat.probe_report_posted', ['lines' => $processed_counter]));
    }
}
