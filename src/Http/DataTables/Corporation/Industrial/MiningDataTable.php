<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2020 Leon Jacobs
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

namespace Seat\Web\Http\DataTables\Corporation\Industrial;

use Seat\Eveapi\Models\Industry\CharacterMining;
use Seat\Web\Http\DataTables\Common\Industrial\AbstractMiningDataTable;

/**
 * Class IndustryDataTable.
 *
 * @package Seat\Web\Http\DataTables\Corporation\Industrial
 */
class MiningDataTable extends AbstractMiningDataTable
{
    public function ajax()
    {
        return $this->data()
            ->addColumn('character', function ($row) {
                return view('web::partials.character', ['character' => $row->character]);
            })
            ->filterColumn('character', function ($query, $keyword) {
                return $query->whereHas('character', function ($sub_query) use ($keyword) {
                    $sub_query->whereRaw('name LIKE ?', ["%$keyword%"]);
                });
            })
            ->rawColumns(['date', 'system', 'ore', 'character', 'main_character'])
            ->make(true);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return CharacterMining::with('character', 'system', 'type', 'type.price')
            ->groupBy('date', 'solar_system_id', 'type_id', 'character_id');
    }

    public function getColumns()
    {
        return array_merge(parent::getColumns(), [
            ['data' => 'character', 'title' => trans_choice('web::seat.character', 1), 'orderable' => false],
        ]);
    }
}
