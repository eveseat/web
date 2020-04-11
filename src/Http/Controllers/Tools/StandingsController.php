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

use Exception;
use Illuminate\Http\Request;
use Seat\Services\Repositories\Character\Character;
use Seat\Services\Repositories\Character\Contacts as CharacterContacts;
use Seat\Services\Repositories\Corporation\Contacts as CorporationContacts;
use Seat\Services\Repositories\Corporation\Corporation;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\Validation\StandingsBuilder;
use Seat\Web\Http\Validation\StandingsElementAdd;
use Seat\Web\Http\Validation\StandingsExistingElementAdd;
use Seat\Web\Models\StandingsProfile;
use Seat\Web\Models\StandingsProfileStanding;

/**
 * Class StandingsController.
 * @package Seat\Web\Http\Controllers\Other
 */
class StandingsController extends Controller
{
    use Character, Corporation, CharacterContacts, CorporationContacts;

    /**
     * @var string
     */
    protected $cache_prefix = 'name_id:';

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
     * @param \Seat\Web\Http\Validation\StandingsBuilder $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postNewStanding(StandingsBuilder $request)
    {

        StandingsProfile::create([
            'name' => $request->input('name'),
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

        $characters = $this->getAllCharactersWithAffiliations();
        $corporations = $this->getAllCorporationsWithAffiliationsAndFilters();

        return view('web::tools.standings.edit',
            compact('standing', 'characters', 'corporations'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStandingsAjaxElementName(Request $request)
    {

        $response = [
            'results' => [],
        ];

        if (strlen($request->input('search')) > 0) {

            try {

                // Resolve the Esi client library from the IoC
                $eseye = app('esi-client')->get();
                $eseye->setVersion('v2');
                $eseye->setQueryString([
                    'categories' => $request->input('type'),
                    'search'     => $request->input('search'),
                ]);

                $entityIds = $eseye->invoke('get', '/search/');

                if (! property_exists($entityIds, $request->input('type')))
                    return response()->json([]);

                if (count($entityIds->{$request->input('type')}) < 1)
                    return response()->json();

                collect($entityIds->{$request->input('type')})->unique()
                    ->filter(function ($id) use (&$response) {

                        // Next, filter the ids we have in the cache, setting
                        // the appropriate response values as we go along.
                        if ($cached_entry = cache('name_id:' . $id)) {

                            $response['results'][] = [
                                'id'   => $id,
                                'text' => $cached_entry,
                            ];

                            // Remove this as a valid id, we already have the value we want.
                            return false;
                        }

                        // We don't have this id in the cache. Return it
                        // so that we can update it later.
                        return true;

                    })->chunk(1000)->each(function ($chunk) use (&$response, $eseye) {

                        $eseye->setVersion('v2');
                        $eseye->setBody($chunk->flatten()->toArray());
                        $names = $eseye->invoke('post', '/universe/names/');

                        collect($names)->each(function ($name) use (&$response) {

                            // Cache the name resolution for this id for a long time.
                            cache(['name_id:' . $name->id => $name->name], carbon()->addCentury());

                            $response['results'][] = [
                                'id'   => $name->id,
                                'text' => $name->name,
                            ];
                        });

                    });

            } catch (Exception $e) {
            }
        }

        return response()->json($response);
    }

    /**
     * @param \Seat\Web\Http\Validation\StandingsElementAdd $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function postAddElementToStanding(StandingsElementAdd $request)
    {

        $element_id = $request->input('element_id');
        $type = $request->input('type');
        $standing = $request->input('standing');

        // Ensure that the element we got is one what we managed
        // to resolve earlier.
        if (! cache($this->cache_prefix . $element_id))
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

    /**
     * @param \Seat\Web\Http\Validation\StandingsExistingElementAdd $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postAddStandingsFromCorpOrChar(StandingsExistingElementAdd $request)
    {

        // Get the standings profile that will be updated.
        $standings_profile = StandingsProfile::find($request->input('id'));

        // Character Contacts
        if ($request->filled('character')) {
            foreach ($this->getCharacterContacts(collect($request->input('character')))->get() as $contact) {

                // Prepare the standings entry.
                $standing = StandingsProfileStanding::firstOrNew([
                    'standings_profile_id' => $request->input('id'),
                    'elementID'            => $contact->contact_id,
                    'type'                 => $contact->contact_type,
                ])->fill([

                    // Update the standing incase its different to an
                    // existing one.
                    'standing' => $contact->standing,
                ]);

                // Save the standings entry to the profile.
                $standings_profile->standings()->save($standing);
            }
        }

        // Corporation Contacts
        if ($request->filled('corporation')) {
            foreach ($this->getCorporationContacts($request->input('corporation')) as $contact) {

                // Prepare the standings entry.
                $standing = StandingsProfileStanding::firstOrNew([
                    'standings_profile_id' => $request->input('id'),
                    'elementID'            => $contact->contact_id,
                    'type'                 => $contact->contact_type,
                ])->fill([

                    // Update the standing incase its different to an
                    // existing one.
                    'standing' => $contact->standing,
                ]);

                // Save the standings entry to the profile.
                $standings_profile->standings()->save($standing);
            }
        }

        return redirect()->back()
            ->with('success', 'Standings successfully imported from contact lists.');

    }

    /**
     * @param int $element_id
     * @param int $profile_id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getRemoveElementFromProfile(int $element_id, int $profile_id)
    {

        // Get the standings profile that will be updated.
        $standings_profile = StandingsProfile::find($profile_id);
        $standings_profile->standings()->find($element_id)->delete();

        return redirect()->back()
            ->with('success', 'Standing removed!');

    }
}
