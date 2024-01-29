<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to present Leon Jacobs
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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Seat\Eveapi\Models\Contacts\AllianceContact;
use Seat\Eveapi\Models\Contacts\CharacterContact;
use Seat\Eveapi\Models\Contacts\CorporationContact;
use Seat\Eveapi\Models\Universe\UniverseName;
use Seat\Services\Contracts\EsiClient;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\DataTables\Scopes\Filters\StandingsProfileScope;
use Seat\Web\Http\DataTables\Tools\StandingsDataTable;
use Seat\Web\Http\Validation\StandingsBuilder;
use Seat\Web\Http\Validation\StandingsElementAdd;
use Seat\Web\Http\Validation\StandingsExistingElementAdd;
use Seat\Web\Models\StandingsProfile;
use Seat\Web\Models\StandingsProfileStanding;

/**
 * Class StandingsController.
 *
 * @package Seat\Web\Http\Controllers\Other
 */
class StandingsController extends Controller
{
    const ENTITY_CACHE_PREFIX = 'name_id';

    /**
     * @var \Seat\Services\Contracts\EsiClient
     */
    private EsiClient $esi;

    public function __construct(EsiClient $client)
    {
        $this->esi = $client;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAvailableProfiles()
    {

        $standings = StandingsProfile::all();

        return view('web::tools.standings.list', compact('standings'));

    }

    /**
     * @param  \Seat\Web\Http\Validation\StandingsBuilder  $request
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
     * @param  int  $profile_id
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
     * @param  int  $id
     * @param  StandingsDataTable  $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getStandingEdit(int $id, StandingsDataTable $dataTable)
    {
        $standing = StandingsProfile::findOrFail($id);

        return $dataTable->addScope(new StandingsProfileScope($id))
            ->render('web::tools.standings.edit', compact('standing'));
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStandingsAjaxElementName(Request $request)
    {

        $payload = [
            'results' => [],
        ];

        if (strlen($request->input('search')) > 0 && auth()->user()->main_character->refresh_token !== null) {

            try {

                // Resolve the Esi client library from the IoC
                $this->esi->setAuthentication(auth()->user()->main_character->refresh_token);
                $this->esi->setVersion('v3');
                $this->esi->setQueryString([
                    'categories' => $request->input('type'),
                    'search' => $request->input('search'),
                ]);

                $response = $this->esi->invoke('get', '/characters/{character_id}/search/', [
                    'character_id' => auth()->user()->main_character_id,
                ]);
                $entityIds = $response->getBody();

                if (! property_exists($entityIds, $request->input('type')))
                    return response()->json([]);

                if (count($entityIds->{$request->input('type')}) < 1)
                    return response()->json();

                collect($entityIds->{$request->input('type')})->unique()
                    ->filter(function ($id) use (&$payload) {

                        // Next, filter the ids we have in the cache, setting
                        // the appropriate response values as we go along.
                        if ($cached_entry = cache(sprintf('%s:%d', self::ENTITY_CACHE_PREFIX, $id))) {

                            $payload['results'][] = [
                                'id' => $id,
                                'text' => $cached_entry,
                            ];

                            // Remove this as a valid id, we already have the value we want.
                            return false;
                        }

                        // We don't have this id in the cache. Return it
                        // so that we can update it later.
                        return true;

                    })->chunk(1000)->each(function ($chunk) use (&$payload) {

                        $this->esi->setVersion('v2');
                        $this->esi->setBody($chunk->flatten()->toArray());
                        $response = $this->esi->invoke('post', '/universe/names/');
                        $names = collect($response->getBody());

                        $names->each(function ($name) use (&$payload) {

                            // Cache the name resolution for this id for a long time.
                            cache(
                                [sprintf('%s:%d', self::ENTITY_CACHE_PREFIX, $name->id) => $name->name],
                                now()->addHour());

                            $payload['results'][] = [
                                'id' => $name->id,
                                'text' => $name->name,
                            ];
                        });

                    });

            } catch (Exception $e) {
                logger()->error($e->getMessage(), $e->getTrace());
            } finally {
                if ($this->esi->isAuthenticated()) {
                    $last_auth = $this->esi->getAuthentication();

                    if (! empty($last_auth->getRefreshToken()))
                        auth()->user()->main_character->refresh_token->refresh_token = $last_auth->getRefreshToken();

                    auth()->user()->main_character->refresh_token->token = $this->esi->getAuthentication()->getAccessToken();
                    auth()->user()->main_character->refresh_token->expires_on = $this->esi->getAuthentication()->getExpiresOn();
                    auth()->user()->main_character->save();
                }
            }
        }

        return response()->json($payload);
    }

    /**
     * @param  \Seat\Web\Http\Validation\StandingsElementAdd  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Exception
     */
    public function postAddElementToStanding(StandingsElementAdd $request)
    {

        $standing = $request->input('standing');
        $entity_id = $request->input('entity_id');
        $entity_name = $request->input('name');
        $entity_type = $request->input('type');

        // Ensure that the element we got is one what we managed
        // to resolve earlier.
        if (! cache(sprintf('%s:%d', self::ENTITY_CACHE_PREFIX, $entity_id)))
            return redirect()->back()
                ->with('error', 'Invalid entity ID');

        UniverseName::firstOrCreate([
            'entity_id' => $entity_id,
        ], [
            'name' => $entity_name,
            'category' => $entity_type,
        ]);

        $standings_profile = StandingsProfile::find($request->input('id'));

        $standing = new StandingsProfileStanding([
            'entity_id' => $entity_id,
            'standing' => $standing,
            'category' => $entity_type,
        ]);
        $standings_profile->entities()->save($standing);

        return redirect()->back()
            ->with('success', 'Element Added to Profile!');

    }

    /**
     * @param  \Seat\Web\Http\Validation\StandingsExistingElementAdd  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postAddStandingsFromCorpOrChar(StandingsExistingElementAdd $request)
    {

        // Get the standings profile that will be updated.
        $standings_profile = StandingsProfile::find($request->input('id'));

        // Character Contacts
        if ($request->filled('character')) {
            foreach ($this->getCharacterContacts(collect($request->input('character')))->get() as $contact) {
                $standing = new StandingsProfileStanding([
                    'entity_id' => $contact->contact_id,
                    'standing' => $contact->standing,
                    'category' => $contact->contact_type,
                ]);
                $standings_profile->entities()->save($standing);
            }
        }

        // Corporation Contacts
        if ($request->filled('corporation')) {
            foreach ($this->getCorporationContacts($request->input('corporation')) as $contact) {
                $standing = new StandingsProfileStanding([
                    'entity_id' => $contact->contact_id,
                    'standing' => $contact->standing,
                    'category' => $contact->contact_type,
                ]);
                $standings_profile->entities()->save($standing);
            }
        }

        // Alliance Contacts
        if ($request->filled('alliance')) {
            foreach ($this->getAllianceContacts($request->input('alliance')) as $contact) {
                $standing = new StandingsProfileStanding([
                    'entity_id' => $contact->contact_id,
                    'standing' => $contact->standing,
                    'category' => $contact->contact_type,
                ]);
                $standings_profile->entities()->save($standing);
            }
        }

        return redirect()->back()
            ->with('success', 'Standings successfully imported from contact lists.');

    }

    /**
     * @param  int  $element_id
     * @param  int  $profile_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getRemoveElementFromProfile(int $element_id, int $profile_id)
    {

        StandingsProfileStanding::where('standings_profile_id', $profile_id)
            ->where('entity_id', $element_id)->delete();

        return redirect()->back()
            ->with('success', 'Standing removed!');

    }

    /**
     * Get a characters contact list.
     *
     * @param  \Illuminate\Support\Collection  $character_ids
     * @param  array|null  $standings
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function getCharacterContacts(Collection $character_ids, ?array $standings = null): Builder
    {

        $contacts = CharacterContact::whereIn('character_contacts.character_id', $character_ids->toArray());

        if (! is_null($standings))
            $contacts->whereIn('standing', $standings);

        return $contacts;
    }

    /**
     * Return the contacts list for a corporation.
     *
     * @param  int  $corporation_id
     * @return \Illuminate\Support\Collection
     */
    private function getCorporationContacts(int $corporation_id): Collection
    {

        return CorporationContact::where('corporation_id', $corporation_id)
            ->orderBy('standing', 'desc')
            ->get();
    }

    /**
     * Return the contacts list for an alliance.
     *
     * @param  int  $alliance_id
     * @return \Illuminate\Support\Collection
     */
    private function getAllianceContacts(int $alliance_id): Collection
    {

        return AllianceContact::where('alliance_id', $alliance_id)
            ->orderBy('standing', 'desc')
            ->get();
    }
}
