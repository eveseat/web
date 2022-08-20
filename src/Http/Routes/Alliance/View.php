<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2022 Leon Jacobs
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

Route::get('/')
    ->name('alliance.list')
    ->uses('AlliancesController@index');

Route::get('/{alliance}')
    ->name('alliance.view.default')
    ->uses('AlliancesController@show');

Route::delete('/{alliance}')
    ->name('alliance.destroy')
    ->uses('AlliancesController@destroy');

Route::get('/{alliance}/contacts')
    ->name('alliance.view.contacts')
    ->uses('AlliancesController@showContacts')
    ->middleware('can:alliance.contact,alliance');

Route::get('/{alliance}/summary')
    ->name('alliance.view.summary')
    ->uses('AlliancesController@showSummary')
    ->middleware('can:alliance.summary,alliance');

Route::get('/{alliance}/tracking')
    ->name('alliance.view.tracking')
    ->uses('AlliancesController@showTracking')
    ->middleware('can:alliance.tracking,alliance');
