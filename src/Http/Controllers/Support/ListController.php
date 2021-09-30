<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2021 Leon Jacobs
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

namespace Seat\Web\Http\Controllers\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Seat\Web\Http\Controllers\Controller;

/**
 * Class ListController.
 *
 * @package Seat\Web\Http\Controllers\Support
 */
class ListController extends Controller
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInvTypes(Request $request)
    {

        return response()->json([
            'results' => DB::table('invTypes')
                ->select('typeName as id', 'typeName as text')
                ->where('typeName', 'like', '%' . $request->q . '%')->get(),
        ]);

    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSeatUserList(Request $request)
    {

        return response()->json([
            'results' => DB::table('users')
                ->select('id', 'name as text')
                ->where('name', 'like', '%' . $request->q . '%')
                ->orderBy('name', 'asc')
                ->get(),
        ]);
    }
}
