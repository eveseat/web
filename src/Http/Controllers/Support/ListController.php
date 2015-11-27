<?php
/*
This file is part of SeAT

Copyright (C) 2015  Leon Jacobs

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

namespace Seat\Web\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class ListController
 * @package Seat\Web\Http\Controllers\Support
 */
class ListController extends Controller
{

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInvTypes(Request $request)
    {

        return response()->json([
            'results' => DB::table('invTypes')
                ->select('typeName as id', 'typeName as text')
                ->where('typeName', 'like', '%' . $request->q . '%')->get()
        ]);

    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param                          $character_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCharacterTransactionClientNames(Request $request, $character_id)
    {

        return response()->json([
            'results' => DB::table('character_wallet_transactions')
                ->select('clientName as id', 'clientName as text')
                ->where('characterID', $character_id)
                ->where('clientName', 'like', '%' . $request->q . '%')
                ->groupBy('clientName')->get()
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param                          $corporation_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCorporationTransactionClientNames(Request $request, $corporation_id)
    {

        return response()->json([
            'results' => DB::table('corporation_wallet_transactions')
                ->select('clientName as id', 'clientName as text')
                ->where('corporationID', $corporation_id)
                ->where('clientName', 'like', '%' . $request->q . '%')
                ->groupBy('clientName')->get()
        ]);

    }

}
