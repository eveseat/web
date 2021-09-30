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

namespace Seat\Web\Http\DataTables\Character\Industrial;

use Seat\Eveapi\Models\Industry\CharacterMining;
use Seat\Web\Http\DataTables\Common\Industrial\AbstractMiningDataTable;

/**
 * Class MiningDataTable.
 *
 * @package Seat\Web\Http\DataTables\Character\Industrial
 */
class MiningDataTable extends AbstractMiningDataTable
{
    /**
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function ajax()
    {
        return $this->data()
            ->rawColumns(['date', 'action', 'solar_system.name', 'type.typeName'])
            ->make(true);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return CharacterMining::with('solar_system', 'type', 'type.price')
            ->select('date', 'character_id', 'solar_system_id', 'type_id')
            ->selectRaw('SUM(quantity) as quantity')
            ->groupBy('date', 'solar_system_id', 'type_id', 'character_id');
    }

    /**
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return parent::html()
            ->postAjax([
                'data' => 'function(d) { d.characters = $("#dt-character-selector").val(); }',
            ]);
    }
}
