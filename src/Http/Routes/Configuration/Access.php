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

Route::resource('roles', 'AccessController', [
    'names' => [
        'index' => 'configuration.access.roles',
        'create' => 'configuration.access.roles.add',
        'store' => 'configuration.access.roles.store',
        'edit' => 'configuration.access.roles.edit',
        'update' => 'configuration.access.roles.update',
        'destroy' => 'configuration.access.roles.delete',
    ],
    'except' => [
        'show',
    ],
]);

Route::delete('/roles/{role_id}/members/{user_id}', [
    'as'   => 'configuration.access.roles.edit.remove.user',
    'uses' => 'AccessController@removeUser',
]);
