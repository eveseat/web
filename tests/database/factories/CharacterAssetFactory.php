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

use Faker\Generator;
use Seat\Eveapi\Models\Assets\CharacterAsset;

$factory->define(CharacterAsset::class, function (Generator $faker) {
    static $id = 1;

    return [
        'item_id' => $id++,
        'type_id' => $faker->numberBetween(2129, 2159),
        'quantity' => $faker->numberBetween(1, 99999),
        'location_id' => $faker->numberBetween(30000001, 33000050),
        'location_type' => 'solar_system',
        'location_flag' => $faker->randomElement([
            'AssetSafety',
            'AutoFit',
            'BoosterBay',
            'Cargo',
            'CorpseBay',
            'Deliveries',
            'DroneBay',
            'FighterBay', 'FighterTube0', 'FighterTube1', 'FighterTube2', 'FighterTube3', 'FighterTube4',
            'FleetHangar',
            'FrigateEscapeBay',
            'Hangar',
            'HangarAll',
            'HiSlot0', 'HiSlot1', 'HiSlot2', 'HiSlot3', 'HiSlot4', 'HiSlot5', 'HiSlot6', 'HiSlot7',
            'HiddenModifiers',
            'Implant',
            'LoSlot0', 'LoSlot1', 'LoSlot2', 'LoSlot3', 'LoSlot4', 'LoSlot5', 'LoSlot6', 'LoSlot7',
            'Locked',
            'MedSlot0', 'MedSlot1', 'MedSlot2', 'MedSlot3', 'MedSlot4', 'MedSlot5', 'MedSlot6', 'MedSlot7',
            'QuafeBay',
            'RigSlot0', 'RigSlot1', 'RigSlot2', 'RigSlot3', 'RigSlot4', 'RigSlot5', 'RigSlot6', 'RigSlot7',
            'ShipHangar', 'Skill', 'SpecializedAmmoHold', 'SpecializedCommandCenterHold', 'SpecializedFuelBay',
            'SpecializedGasHold', 'SpecializedIndustrialShipHold', 'SpecializedLargeShipHold', 'SpecializedMaterialBay',
            'SpecializedMediumShipHold', 'SpecializedMineralHold', 'SpecializedOreHold',
            'SpecializedPlanetaryCommoditiesHold', 'SpecializedSalvageHold', 'SpecializedShipHold',
            'SpecializedSmallShipHold', 'SubSystemBay',
            'SubSystemSlot0', 'SubSystemSlot1', 'SubSystemSlot2', 'SubSystemSlot3', 'SubSystemSlot4', 'SubSystemSlot5',
            'SubSystemSlot6', 'SubSystemSlot7', 'Unlocked', 'Wardrobe']),
        'is_singleton' => $faker->boolean(),
    ];
});
