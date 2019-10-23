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

use Seat\Eveapi\Models\Corporation\CorporationStructure;
use Seat\Services\Repositories\Corporation\Structures;
use Seat\Web\Http\Controllers\Controller;

/**
 * Class StructureController.
 * @package Seat\Web\Http\Controllers\Corporation
 */
class StructureController extends Controller
{
    use Structures;

    /**
     * @param int $corporation_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getStructures(int $corporation_id)
    {

        $structures = $this->getCorporationStructures($corporation_id);

        return view('web::corporation.structures.list', compact('structures'));
    }

    /**
     * @param int $corporation_id
     * @param int $structure_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(int $corporation_id, int $structure_id)
    {
        $structure = CorporationStructure::with('info', 'type', 'services', 'items', 'items.type', 'items.type.dogma_attributes', 'system')
            ->where('corporation_id', $corporation_id)
            ->where('structure_id', $structure_id)
            ->first();

        return view('web::corporation.structures.modals.fitting.content', compact('structure'));
    }
}
