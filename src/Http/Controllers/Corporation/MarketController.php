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

namespace Seat\Web\Http\Controllers\Corporation;

use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Universe\UniverseName;
use Seat\Services\Repositories\Corporation\Market;
use Seat\Services\Repositories\Eve\EveRepository;
use Seat\Web\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;

/**
 * Class MarketController.
 * @package Seat\Web\Http\Controllers\Corporation
 */
class MarketController extends Controller
{
    use EveRepository;
    use Market;

    /**
     * @param $corporation_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getMarket(int $corporation_id)
    {

        return view('web::corporation.market');
    }

    /**
     * @param int $corporation_id
     *
     * @return mixed
     * @throws \Exception
     */
    public function getMarketData(int $corporation_id)
    {

        $orders = $this->getCorporationMarketOrders($corporation_id, false);

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
            ->addColumn('price_total', function ($row) {

                return number($row->price_total);
            })
            ->editColumn('typeName', function ($row) {

                return view('web::partials.markettype', compact('row'));
            })
            ->editColumn('issued_by', function ($row) {

                $character = CharacterInfo::find($row->issued_by) ?: $row->issued_by;

                return view('web::partials.character', compact('character', 'character_id'));
            })
            ->filterColumn('issued_by', function ($query, $keyword) {

                $resolved_ids = UniverseName::where('name', 'like', '%' . $keyword . '%')
                    ->get()
                    ->map(function ($resolved_id) {
                        return $resolved_id->entity_id;
                    });

                $character_info_ids = CharacterInfo::where('name', 'like', '%' . $keyword . '%')
                    ->get()
                    ->map(function ($character_info) {
                        return $character_info->character_id;
                    });

                $query->whereIn('a.issued_by', array_merge($resolved_ids->toArray(), $character_info_ids->toArray()));
            })
            ->rawColumns(['bs', 'vol', 'typeName', 'issued_by'])
            ->make(true);

    }
}
