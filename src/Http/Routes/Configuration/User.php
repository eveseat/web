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

Route::get('/')
    ->name('seatcore::configuration.users')
    ->uses('UserController@index');

Route::get('/{user_id}')
    ->name('seatcore::configuration.users.edit')
    ->uses('UserController@edit');

Route::put('/{user_id}')
    ->name('seatcore::configuration.users.update')
    ->uses('UserController@update');

Route::put('/{user_id}/reassign')
    ->middleware('can:global.superuser')
    ->name('seatcore::configuration.users.reassign')
    ->uses('UserController@reassign');

Route::delete('/{user_id}')
    ->name('seatcore::configuration.users.delete')
    ->uses('UserController@delete');

Route::get('/edit/{user_id}/account_status')
    ->name('seatcore::configuration.users.edit.account_status')
    ->uses('UserController@editUserAccountStatus');

Route::post('/{user_id}/impersonate')
    ->name('seatcore::configuration.users.impersonate')
    ->uses('UserController@impersonate');
