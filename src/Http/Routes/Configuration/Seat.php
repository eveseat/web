<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to present Leon Jacobs
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

Route::get('/view')
    ->name('seatcore::seat.settings.view')
    ->uses('SeatController@getView');

Route::get('/about')
    ->name('seatcore::seat.settings.about')
    ->uses('SeatController@getAbout');

Route::post('/update/customlink')
    ->name('seatcore::seat.update.customlink')
    ->uses('SeatController@postUpdateCustomLinks');

Route::post('/update/settings')
    ->name('seatcore::seat.update.settings')
    ->uses('SeatController@postUpdateSettings');

Route::get('/check/sde')
    ->name('seatcore::check.sde')
    ->uses('SeatController@getApprovedSDE');

Route::post('/packages/check')
    ->name('seatcore::packages.check')
    ->uses('SeatController@postPackagesCheck');

Route::post('/packages/changelog')
    ->name('seatcore::packages.changelog')
    ->uses('SeatController@postPackagesChangelog');
