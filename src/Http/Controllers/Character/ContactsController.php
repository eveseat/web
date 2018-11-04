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

namespace Seat\Web\Http\Controllers\Character;

use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Services\Repositories\Character\Contacts;
use Seat\Web\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;

class ContactsController extends Controller
{
    use Contacts;

    /**
     * @param $character_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getContacts(int $character_id)
    {

        if(! request()->ajax())
            return view('web::character.contacts');

        $contacts = $this->getCharacterContacts($character_id);

        return Datatables::of($contacts)
            ->editColumn('name', function ($row) {

                if($row->contact_type === 'character'){
                    $character = CharacterInfo::find($row->contact_id) ?: $row->contact_id;

                    return view('web::partials.character', compact('character'));
                }

                if($row->contact_type === 'corporation'){
                    $corporation = CorporationInfo::find($row->contact_id) ?: $row->contact_id;

                    return view('web::partials.corporation', compact('corporation'));
                }

                return view('web::partials.unknown', ['unknown_id' => $row->contact_id]);
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
            ->addColumn('name', function ($row) {
                return cache('name_id:' . $row->contact_id);
            })
            ->make(true);

    }
}
