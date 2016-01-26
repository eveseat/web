<?php
/*
This file is part of SeAT

Copyright (C) 2015, 2016  Leon Jacobs

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/

namespace Seat\Web\Http\Controllers\Configuration;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use League\Csv\Reader;
use Seat\Eveapi\Models\Eve\ApiKey;
use Seat\Web\Validation\CsvImport;
use Validator;

/**
 * Class ImportController
 * @package Seat\Web\Http\Controllers\Configuration
 */
class ImportController extends Controller
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndex()
    {

        return view('web::configuration.import.list');
    }

    /**
     * @param \Seat\Web\Validation\CsvImport $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCsv(CsvImport $request)
    {

        $csv = Reader::createFromFileObject(
            $request->file('csv')->openFile());

        // Keep tabs on the amount of keys that have
        // been inserted, and how many have been
        // considered erroneous
        $updated = 0;
        $errored = 0;

        // Loop the CSV, validating the lines
        // and inserting into the database
        foreach ($csv as $k => $data) {

            // Assign the $data to readable names
            $key_id = $data[0];
            $v_code = $data[1];

            // Validate the keys. We check that we dont
            // already have this key in the database to ensure
            // that we dont mess up the ownership by accident.
            $validator = Validator::make([
                'key_id' => $key_id,
                'v_code' => $v_code
            ], [
                'key_id' => 'required|numeric|unique:eve_api_keys,key_id',
                'v_code' => 'required|size:64|alpha_num',
            ]);

            // Ensure the format was ok
            if ($validator->fails()) {

                $errored++;
                continue;
            }

            // Add the API Key
            ApiKey::create([
                'key_id'  => $key_id,
                'v_code'  => $v_code,
                'user_id' => auth()->user()->id,
                'enabled' => true,
            ]);

            $updated++;

        }

        return redirect()->back()
            ->with('success', 'Import complete! Success: ' . $updated . '. Error: ' . $errored);

    }
}
