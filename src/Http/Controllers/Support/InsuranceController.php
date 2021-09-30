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

use Seat\Eveapi\Models\Sde\InvType;
use Seat\Web\Http\Controllers\Controller;

/**
 * Class CommonController.
 *
 * @package Seat\Web\Http\Controllers\Support
 */
class InsuranceController extends Controller
{
    /**
     * @param  int  $type_id
     */
    public function show(int $type_id)
    {
        $ship = InvType::find($type_id) ?? new InvType();

        return view('web::common.fittings.modals.insurances.content', compact('ship'));
    }
}
