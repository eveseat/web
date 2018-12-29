<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015, 2016, 2017, 2018  Leon Jacobs
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
use Seat\Eveapi\Models\Industry\CorporationIndustryMiningExtraction;
use Seat\Eveapi\Models\Universe\UniverseMoonContent;
use Seat\Web\Http\Controllers\Controller;

/**
 * Class ExtractionController.
 * @package Seat\Web\Http\Controllers\Corporation
 */
class ExtractionController extends Controller
{
    /**
     * @param int $corporation_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getExtractions(int $corporation_id)
    {
        // retrieve any valid extraction for the current corporation
        $extractions = CorporationIndustryMiningExtraction::with(
            'moon', 'moon.system', 'moon.constellation', 'moon.region', 'moon.moon_contents', 'moon.moon_contents.type',
                'structure', 'structure.info', 'structure.services')
            ->where('corporation_id', $corporation_id)
            ->where('natural_decay_time', '>', carbon()->subSeconds(CorporationIndustryMiningExtraction::THEORETICAL_DEPLETION_COUNTDOWN))
            ->orderBy('chunk_arrival_time')
            ->get();

        return view('web::corporation.extraction.extraction', compact('extractions'));
    }

    public function postProbeReport()
    {
        $this->validate(request(), [
            'moon-report' => 'required',
        ]);

        $report = request()->input('moon-report');

        $processed_counter = 0;

        $report_lines = explode(PHP_EOL, $report);

        foreach ($report_lines as $line) {
            $fields = explode("\t", $line);

            if ($fields[0] !== '')
                continue;

            if (count($fields) !== 7) {
                logger()->debug('Inconsistent probe report has been posted.', [
                    'report' => $report,
                    'line' => $line,
                    'parsed_line' => $fields,
                ]);

                return redirect()->back()
                    ->with('error', 'There is a problem with your probe report structure - 7 fields were expected per line. The issue has been recorded.');
            }

            try {
                UniverseMoonContent::updateOrCreate(
                    ['moon_id' => $fields[6], 'type_id' => $fields[3]],
                    ['rate' => $fields[2]]
                );

                $processed_counter++;
            } catch (QueryException $e) {
                logger()->error($e->getMessage(), $e->getTrace());

                return redirect()->back()
                    ->with('error', 'An issue as been encountered while posting your report. Please contact your administrator.');
            }
        }

        return redirect()->back()
            ->with('success', sprintf('Your probe report has been successfully posted. %d Moon composition lines has been updated.', $processed_counter));
    }
}
