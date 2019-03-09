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

namespace Seat\Web\Http\Controllers\Character;

use Illuminate\Support\Facades\DB;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Contacts\CharacterContactLabel;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Eveapi\Models\Universe\UniverseName;
use Seat\Services\Repositories\Character\Contacts;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Models\User;
use Yajra\DataTables\DataTables;

class ContactsController extends Controller
{
    use Contacts;

    /**
     * @param int $character_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function getContacts(int $character_id)
    {

        if(! request()->ajax())
            return view('web::character.contacts');

        if(! request()->has('all_linked_characters'))
            return response('required url parameter is missing!', 400);

        if(request('all_linked_characters') === 'false')
            $character_ids = collect($character_id);

        $user_group = User::find($character_id)->group->users
            ->filter(function ($user) {
                if(! $user->name === 'admin' || $user->id === 1)
                    return false;

                return true;
            })
            ->pluck('id');

        if(request('all_linked_characters') === 'true')
            $character_ids = $user_group;

        $standings = array_map('intval', explode(',', request('selected_standings')));

        $contacts = $this->getCharacterContacts($character_ids, $standings);

        return DataTables::of($contacts)
            ->editColumn('name', function ($row) {

                $character_id = $row->character_id;

                if($row->contact_type === 'character'){
                    $character = CharacterInfo::find($row->contact_id) ?: $row->contact_id;

                    return view('web::partials.character', compact('character', 'character_id'));
                }

                if($row->contact_type === 'corporation'){
                    $corporation = CorporationInfo::find($row->contact_id) ?: $row->contact_id;

                    return view('web::partials.corporation', compact('corporation', 'character_id'));
                }

                return view('web::partials.unknown', [
                    'unknown_id' => $row->contact_id,
                    'character_id' => $character_id,
                    ]);
            })
            ->editColumn('label_ids', function ($row) {

                if(isset($row->label_ids)) {
                    $labels = $this->getCharacterContactLabels($row->character_id);

                    return $labels->whereIn('label_id', $row->label_ids)->implode('label_name', ', ');
                }

                return '';
            })
            ->addColumn('standing_view', function ($row) {

                if($row->standing > 0)
                    return "<b class='text-success'>" . $row->standing . '</b>';

                if($row->standing < 0)
                    return "<b class='text-danger'>" . $row->standing . '</b>';

                return '<b>' . $row->standing . '</b>';

            })
            ->addColumn('links', function ($row) {

                return view('web::partials.links', ['id' => $row->contact_id, 'type' => $row->contact_type]);
            })
            ->addColumn('is_in_group', function ($row) use ($user_group) {

                return $user_group->intersect(collect($row->contact_id))->isNotEmpty();
            })
            ->filterColumn('name', function ($query, $keyword) {
                $resolved_ids = UniverseName::where('name', 'like', '%' . $keyword . '%')->get()->map(function ($resolved_id) { return $resolved_id->entity_id; });
                $character_info_ids = CharacterInfo::where('name', 'like', '%' . $keyword . '%')->get()->map(function ($character_info) { return $character_info->character_id; });
                $corporation_info_ids = CorporationInfo::where('name', 'like', '%' . $keyword . '%')->get()->map(function ($corporation_info) { return $corporation_info->corproation_id; });

                $query->whereIn('contact_id', array_merge($resolved_ids->toArray(), $character_info_ids->toArray(), $corporation_info_ids->toArray()));
            })
            ->filterColumn('label_ids', function ($query, $keyword) use ($character_ids) {

                $labels = CharacterContactLabel::where('label_name', 'like', '%' . $keyword . '%')
                    ->whereIn('character_id', $character_ids)
                    ->get();

                foreach ($labels as $label)
                    $query->whereRaw(DB::raw('JSON_CONTAINS(label_ids, ' . $label->label_id . ')'));
            })
            ->rawColumns(['name', 'standing_view', 'links'])
            ->make(true);

    }
}
