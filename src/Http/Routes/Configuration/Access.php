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

Route::resource('roles', 'AccessController', [
    'names' => [
        'index' => 'seatcore::configuration.access.roles',
        'create' => 'seatcore::configuration.access.roles.add',
        'store' => 'seatcore::configuration.access.roles.store',
        'edit' => 'seatcore::configuration.access.roles.edit',
        'update' => 'seatcore::configuration.access.roles.update',
        'destroy' => 'seatcore::configuration.access.roles.delete',
    ],
    'except' => [
        'show',
    ],
]);

Route::delete('/roles/{role_id}/members/{user_id}')
    ->name('seatcore::configuration.access.roles.edit.remove.user')
    ->uses('AccessController@removeUser');
