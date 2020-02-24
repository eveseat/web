<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015, 2016, 2017, 2018, 2019  Leon Jacobs
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

Route::get('/', [
    'as'   => 'squads.index',
    'uses' => 'SquadsController@index',
]);

Route::get('/create', [
    'as'   => 'squads.create',
    'uses' => 'SquadsController@create',
]);

Route::post('/', [
    'as'   => 'squads.store',
    'uses' => 'SquadsController@store',
]);

Route::get('/{id}', [
    'as'   => 'squads.show',
    'uses' => 'SquadsController@show',
]);

Route::get('/{id}/edit', [
    'as'   => 'squads.edit',
    'uses' => 'SquadsController@edit',
]);

Route::put('/{id}', [
    'as'   => 'squads.update',
    'uses' => 'SquadsController@update',
]);

Route::delete('/{id}', [
    'as' => 'squads.destroy',
    'uses' => 'SquadsController@destroy',
]);

Route::prefix('/{id}/candidates')->group(function () {
    Route::get('/', [
        'as'   => 'squads.candidates.index',
        'uses' => 'ApplicationsController@index',
    ]);

    Route::post('/', [
        'as'   => 'squads.candidates.store',
        'uses' => 'ApplicationsController@store',
    ]);
});

Route::prefix('/{id}/members')->group(function () {
    Route::get('/', [
        'as'   => 'squads.members.show',
        'uses' => 'MembersController@show',
    ]);

    Route::get('/lookup', [
        'as'   => 'squads.members.lookup',
        'uses' => 'MembersController@lookup',
    ]);

    Route::post('/', [
        'as'   => 'squads.members.store',
        'uses' => 'MembersController@store',
    ]);

    Route::delete('/', [
        'as'   => 'squads.members.kick',
        'uses' => 'MembersController@destroy',
    ]);

    Route::delete('/leave', [
        'as'   => 'squads.members.leave',
        'uses' => 'MembersController@leave',
    ]);
});

Route::prefix('/{id}/moderators')->group(function () {
    Route::get('/lookup', [
        'as'   => 'squads.moderators.lookup',
        'uses' => 'ModeratorsController@lookup',
    ]);

    Route::post('/', [
        'as'   => 'squads.moderators.store',
        'uses' => 'ModeratorsController@store',
    ]);

    Route::delete('/', [
        'as'   => 'squads.moderators.destroy',
        'uses' => 'ModeratorsController@destroy',
    ]);
});

Route::prefix('/{id}/roles')->group(function () {
    Route::get('/', [
        'as'   => 'squads.roles.show',
        'uses' => 'RolesController@show',
    ]);

    Route::get('/lookup', [
        'as'   => 'squads.roles.lookup',
        'uses' => 'RolesController@lookup',
    ]);

    Route::post('/', [
        'as'   => 'squads.roles.store',
        'uses' => 'RolesController@store',
    ]);

    Route::delete('/', [
        'as'   => 'squads.roles.destroy',
        'uses' => 'RolesController@destroy',
    ]);
});

Route::prefix('/applications/{id}')->group(function () {
    Route::get('/', [
        'as'   => 'squads.applications.show',
        'uses' => 'ApplicationsController@show',
    ]);

    Route::post('/', [
        'as'   => 'squads.applications.approve',
        'uses' => 'ApplicationsController@approve',
    ]);

    Route::delete('/', [
        'as'   => 'squads.applications.reject',
        'uses' => 'ApplicationsController@reject',
    ]);
});
