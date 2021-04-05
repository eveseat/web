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

namespace Seat\Web\Http\Controllers\Corporation;

use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Eveapi\Models\Corporation\CorporationStructure;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\DataTables\Corporation\Military\StructureDataTable;
use Seat\Web\Http\DataTables\Scopes\CorporationScope;

/**
 * Class StructureController.
 * @package Seat\Web\Http\Controllers\Corporation
 */
class StructureController extends Controller
{
    /**
     * @param \Seat\Eveapi\Models\Corporation\CorporationInfo $corporation
     * @param \Seat\Web\Http\DataTables\Corporation\Military\StructureDataTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getStructures(CorporationInfo $corporation, StructureDataTable $dataTable)
    {

        return $dataTable->addScope(new CorporationScope('corporation.structure', [$corporation->corporation_id]))
            ->render('web::corporation.structures.list', compact('corporation'));
    }

    /**
     * @param \Seat\Eveapi\Models\Corporation\CorporationInfo $corporation
     * @param int $structure_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(CorporationInfo $corporation, int $structure_id)
    {
        $structure = CorporationStructure::with('info', 'type', 'services', 'items', 'items.type', 'items.type.dogma_attributes', 'solar_system')
            ->where('corporation_id', $corporation->corporation_id)
            ->where('structure_id', $structure_id)
            ->first();

        return view('web::corporation.structures.modals.fitting.content', compact('corporation', 'structure'));
    }
}
