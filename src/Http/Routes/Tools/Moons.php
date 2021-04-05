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

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => '/moons/',
    'middleware' => 'can:moon.view_moon_reports',
], function () {
    Route::get('/')
        ->name('tools.moons.index')
        ->uses('MoonsController@index');

    Route::get('/{id}')
        ->name('tools.moons.show')
        ->uses('MoonsController@show');

    Route::post('/')
        ->name('tools.moons.store')
        ->uses('MoonsController@store')
        ->middleware('can:moon.create_moon_reports');

    Route::delete('/{report}')
        ->name('tools.moons.destroy')
        ->uses('MoonsController@destroy')
        ->middleware('can:moon.manage_moon_reports');
});
