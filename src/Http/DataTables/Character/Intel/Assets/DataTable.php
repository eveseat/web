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

namespace Seat\Web\Http\DataTables\Character\Intel\Assets;

use Seat\Eveapi\Models\Assets\CharacterAsset;
use Seat\Web\Http\DataTables\Character\Intel\Assets\Columns\LocationFlag;
use Seat\Web\Http\DataTables\Character\Intel\Assets\Columns\Owner;
use Seat\Web\Http\DataTables\Character\Intel\Assets\Columns\Station;
use Seat\Web\Http\DataTables\Common\IColumn;
use Seat\Web\Http\DataTables\Common\Intel\AbstractAssetDataTable;

/**
 * Class DataTable.
 *
 * @package Seat\Web\Http\DataTables\Character\Intel\Assets
 */
class DataTable extends AbstractAssetDataTable
{
    const IGNORED_FLAGS = [
        // generic fitting flags
        'Cargo',
        'DroneBay',
        'HiSlot0', 'HiSlot1', 'HiSlot2', 'HiSlot3', 'HiSlot4', 'HiSlot5', 'HiSlot6', 'HiSlot7',
        'MedSlot0', 'MedSlot1', 'MedSlot2', 'MedSlot3', 'MedSlot4', 'MedSlot5', 'MedSlot6', 'MedSlot7',
        'LoSlot0', 'LoSlot1', 'LoSlot2', 'LoSlot3', 'LoSlot4', 'LoSlot5', 'LoSlot6', 'LoSlot7',
        'RigSlot0', 'RigSlot1', 'RigSlot2', 'RigSlot3', 'RigSlot4', 'RigSlot5', 'RigSlot6', 'RigSlot7',
        // industrial fitting flags
        'SpecializedAmmoHold', 'SpecializedMineralHold', 'SpecializedOreHold', 'SpecializedPlanetaryCommoditiesHold',
        // battleship fitting flags
        'FrigateEscapeBay',
        // tech 3 fitting flags
        'SubSystemSlot0', 'SubSystemSlot1', 'SubSystemSlot2', 'SubSystemSlot3', 'SubSystemSlot4', 'SubSystemSlot5',
        'SubSystemSlot6', 'SubSystemSlot7', 'SubSystemBay', 'HiddenModifiers',
        // capitals fitting flags
        'FighterBay', 'FleetHangar', 'SpecializedFuelBay', 'ShipHangar',
        // Loaded fighters fitting flags
        'FighterTube0', 'FighterTube1', 'FighterTube2', 'FighterTube3', 'FighterTube4', 'FighterTube5', 'FighterTube6', 'FighterTube7',
        // Stuff inside a container
        'AutoFit',
    ];

    /**
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return parent::html()
            ->removeColumn('location_flag')
            ->addColumnBefore([
                'data' => 'character.name',
                'title' => trans_choice('web::seat.owner', 1),
                'orderable' => false,
                'searchable' => false,
            ])
            ->orders([1, 'asc'])
            ->postAjax([
                'data' => 'function(d) { d.characters = $("#dt-character-selector").val(); }',
            ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return CharacterAsset::with('type', 'type.group', 'station', 'container', 'container.station')
            ->whereDoesntHave('container', function ($container) {
                $container->whereIn('location_flag', ['AssetSafety', 'FleetHangar']); // exclude content from asset safety - we show their container
            })
            ->whereDoesntHave('container.container', function ($container) {
                $container->where('location_flag', 'AssetSafety');
            })
            ->whereNotIn('location_flag', self::IGNORED_FLAGS);
    }

    /**
     * @param  \Seat\Web\Http\DataTables\Common\Intel\AbstractAssetDataTable  $table
     * @return \Seat\Web\Http\DataTables\Common\IColumn
     */
    protected function getLocationFlagColumn($table): IColumn
    {
        return new LocationFlag($table);
    }

    /**
     * @param  \Seat\Web\Http\DataTables\Common\Intel\AbstractAssetDataTable  $table
     * @return \Seat\Web\Http\DataTables\Common\IColumn
     */
    protected function getStationColumn($table): IColumn
    {
        return new Station($table);
    }

    /**
     * @return \Seat\Web\Http\DataTables\Common\IColumn[]
     */
    protected function extraColumns(): array
    {
        return [
            'character.name' => new Owner($this),
        ];
    }
}
