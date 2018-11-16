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
use Seat\Services\Repositories\Character\Contracts;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Models\User;
use Yajra\Datatables\Datatables;

/**
 * Class ContractsController.
 * @package Seat\Web\Http\Controllers\Character
 */
class ContractsController extends Controller
{
    use Contracts;

    /**
     * @param $character_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getContracts(int $character_id)
    {

        return view('web::character.contracts');

    }

    /**
     * @param int $character_id
     *
     * @return mixed
     */
    public function getContractsData(int $character_id)
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

        $contracts = $this->getCharacterContracts($character_ids);

        return Datatables::of($contracts)
            ->editColumn('issuer_id', function ($row) {

                $character_id = $row->character_id;

                if($row->for_corporation){

                    $corporation = CorporationInfo::find($row->issuer_corporation_id) ?: $row->issuer_corporation_id;
                    $character = CharacterInfo::find($row->issuer_id) ?: $row->issuer_id;

                    return view('web::partials.corporation', compact('corporation', 'character_id'))
                        . ' (' . view('web::partials.character', compact('character', 'character_id')) . ')';
                }

                $character = CharacterInfo::find($row->issuer_id) ?: $row->issuer_id;

                return view('web::partials.character', compact('character', 'character_id'));

            })
            ->editColumn('assignee_id', function ($row) {

                $character_id = $row->character_id;
                $character = CharacterInfo::find($row->assignee_id) ?: null;

                if(! is_null($character))
                    return view('web::partials.character', compact('character', 'character_id'));

                $corporation = CorporationInfo::find($row->assignee_id) ?: null;

                if(! is_null($corporation))
                    return view('web::partials.corporation', compact('corporation', 'character_id'));

                return view('web::partials.unknown', [
                    'unknown_id' => $row->assignee_id,
                    'character_id' => $row->character_id,
                ]);

            })
            ->editColumn('acceptor_id', function ($row) {

                if($row->acceptor_id === 0)
                    return '';

                $character_id = $row->character_id;
                $character = CharacterInfo::find($row->acceptor_id) ?: null;

                if(! is_null($character))
                    return view('web::partials.character', compact('character', 'character_id'));

                $corporation = CorporationInfo::find($row->acceptor_id) ?: null;

                if(! is_null($corporation))
                    return view('web::partials.corporation', compact('corporation', 'character_id'));

                return view('web::partials.unknown', [
                    'unknown_id' => $row->acceptor_id,
                    'character_id' => $row->character_id,
                ]);

            })
            ->editColumn('type', function ($row) {

                return view('web::partials.contracttype', compact('row'))
                    ->render();
            })
            ->editColumn('price', function ($row) {

                return number($row->price);
            })
            ->editColumn('reward', function ($row) {

                return number($row->reward);
            })
            ->editColumn('collateral', function ($row) {

                return number($row->collateral);
            })
            ->addColumn('contents', function ($row) {

                return view('web::partials.contractcontentsbutton', compact('row'))
                    ->render();
            })
            ->addColumn('is_in_group', function ($row) use ($user_group) {

                if (in_array($row->issuer_id, $user_group->toArray()) && in_array($row->acceptor_id, $user_group->toArray()))
                    return true;

                if (in_array($row->issuer_id, $user_group->toArray()) && in_array($row->assignee_id, $user_group->toArray()))
                    return true;

                return false;
            })
            ->make(true);

    }

    /**
     * @param int $character_id
     * @param int $contract_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getContractsItemsData(int $character_id, int $contract_id)
    {

        $assets = $this->getCharacterContractsItems($character_id, $contract_id);

        return view('web::character.contractitems', compact('assets'));
    }
}
