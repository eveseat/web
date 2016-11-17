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

namespace Seat\Web\Http\Controllers\Tools;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Models\StandingsProfile;
use Seat\Web\Models\StandingsProfileStanding;
use Seat\Web\Validation\StandingsBuilder;
use Seat\Web\Validation\StandingsElementAdd;

/**
 * Class StandingsController
 * @package Seat\Web\Http\Controllers\Other
 */
class StandingsController extends Controller
{

    /**
     * @var string
     */
    protected $cache_prefix = 'standingbuilder';

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAvailableProfiles()
    {

        $standings = StandingsProfile::with('standings')
            ->get();

        return view('web::tools.standings.list', compact('standings'));

    }

    /**
     * @param \Seat\Web\Validation\StandingsBuilder $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postNewStanding(StandingsBuilder $request)
    {

        StandingsProfile::create([
            'name' => $request->input('name')
        ]);

        return redirect()->back()
            ->with('success', 'Created!');

    }

    /**
     * @param int $profile_id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getDeleteStandingsProfile(int $profile_id)
    {

        $standing = StandingsProfile::findOrFail($profile_id);
        $standing->delete();

        return redirect()->back()
            ->with('success', 'Standings profile deleted.');
    }

    /**
     * @param int $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getStandingEdit(int $id)
    {

        $standing = StandingsProfile::with('standings')
            ->where('id', $id)
            ->first();

        return view('web::tools.standings.edit', compact('standing', 'id'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStandingsAjaxElementName(Request $request)
    {

        $response = ['results' => []];

        if (strlen($request->input('q')) > 0) {

            try {

                $pheal = app()
                    ->make('Seat\Eveapi\Helpers\PhealSetup')
                    ->getPheal();

                $names = $pheal->eveScope->CharacterID([
                    'names' => $request->input('q')
                ]);

                foreach ($names->characters as $result) {

                    $response['results'] = [
                        [
                            'id'   => $result->characterID,
                            'text' => $result->name
                        ]];

                    // Cache the entry
                    Cache::forever(
                        $this->cache_prefix . $result->characterID,
                        $result->name
                    );
                }

            } catch (Exception $e) {
            }
        }

        return response()->json($response);
    }

    /**
     * @param \Seat\Web\Validation\StandingsElementAdd $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postAddElementToStanding(StandingsElementAdd $request)
    {

        $element_id = $request->input('element_id');
        $type = $request->input('type');
        $standing = $request->input('standing');

        // Ensure that the element we got is one what we managed
        // to resolve earlier.
        if (!cache($this->cache_prefix . $element_id))
            return redirect()->back()
                ->with('error', 'Invalid Element ID');

        $standings_profile = StandingsProfile::find($request->input('id'));
        $standings_profile->standings()->save(new StandingsProfileStanding([
            'type'      => $type,
            'elementID' => $element_id,
            'standing'  => $standing,
        ]));

        return redirect()->back()
            ->with('success', 'Element Added to Profile!');

    }

}
