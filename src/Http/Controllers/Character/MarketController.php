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

use Seat\Services\Repositories\Character\Market;
use Seat\Services\Repositories\Eve\EveRepository;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Models\User;
use Yajra\DataTables\DataTables;

/**
 * Class MarketController.
 * @package Seat\Web\Http\Controllers\Character
 */
class MarketController extends Controller
{
    use Market;
    use EveRepository;

    /**
     * @param $character_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getMarket(int $character_id)
    {

        return view('web::character.market');

    }

    /**
     * @param int $character_id
     *
     * @return mixed
     * @throws \Exception
     */
    public function getMarketData(int $character_id)
    {

        if (! request()->has('all_linked_characters'))
            return abort(500);

        if (request('all_linked_characters') === 'false')
            $character_ids = collect($character_id);

        $user_group = User::find($character_id)->group->users
            ->filter(function ($user) {
                return $user->name !== 'admin' && $user->id !== 1;
            })
            ->pluck('id');

        if (request('all_linked_characters') === 'true')
            $character_ids = $user_group;

        $orders = $this->getCharacterMarketOrders($character_ids);

        return DataTables::of($orders)
            ->addColumn('bs', function ($row) {

                return view('web::partials.marketbuysell', compact('row'));
            })
            ->addColumn('vol', function ($row) {

                if ($row->is_buy_order)
                    return number($row->volume_total, 0);

                return number($row->volume_remain, 0) . ' / ' . number($row->volume_total, 0);
            })
            ->editColumn('price', function ($row) {

                return number($row->price);
            })
            ->addColumn('total', function ($row) {

                return number($row->price * $row->volume_total);
            })
            ->editColumn('typeName', function ($row) {

                return view('web::partials.markettype', compact('row'));
            })
            ->rawColumns(['bs', 'typeName'])
            ->make(true);

    }
}
