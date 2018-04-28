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

use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Seat\Eveapi\Models\Corporation\CorporationMemberTracking;
use Seat\Eveapi\Models\Industry\CharacterMining;
use Seat\Web\Http\Controllers\Controller;

class MiningLedgerController extends Controller
{
    public function getLedger(int $corporation_id, int $year = null, int $month = null) : View
    {
        if (is_null($year))
            $year = date('Y');

        if (is_null($month))
            $month = date('m');

        $members = CorporationMemberTracking::where('corporation_id', $corporation_id)
                                            ->select('character_id')
                                            ->get();

        $ledgers = CharacterMining::select(DB::raw('DISTINCT YEAR(date) as year, MONTH(date) as month'))
                     ->whereIn('character_id', $members)
                     ->orderBy('year', 'desc')
                     ->orderBy('month', 'desc')
                     ->get();

        $entries = CharacterMining::select('character_minings.character_id', 'year', 'month',
                DB::raw('sum(quantity) as quantities'), DB::raw('sum(quantity * volume) as volumes'),
                DB::raw('sum(quantity * average_price) as amounts'))
            ->join('market_prices', 'market_prices.type_id', 'character_minings.type_id')
            ->join('invTypes', 'typeID', 'character_minings.type_id')
            ->join('corporation_member_trackings', 'corporation_member_trackings.character_id', 'character_minings.character_id')
            ->where('corporation_id', $corporation_id)
            ->where('year', '=', $year)
            ->where('month', '=', $month)
            ->groupBy('character_id', 'year', 'month')
            ->get();

        return view('web::corporation.mining.ledger', compact('ledgers', 'entries'));
    }

    public function getTracking(int $corporation_id) : View
    {
        $members = MemberTracking::where('corporationID', $corporation_id)->get();

        return view('mining-ledger::corporation.views.tracking', compact('members'));
    }
}
