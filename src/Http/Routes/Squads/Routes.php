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
    'as'   => 'squads.list',
    'uses' => 'SquadsController@index',
]);

Route::get('/{id}', [
    'as'   => 'squads.show',
    'uses' => 'SquadsController@show',
]);

Route::post('/{id}/members/join', [
    'as'   => 'squads.members.join',
    'uses' => 'SquadsController@join',
]);

Route::delete('/{id}/members/leave', [
    'as'   => 'squads.members.leave',
    'uses' => 'SquadsController@leave',
]);

Route::delete('/{id}/members/{user_id}', [
    'as'   => 'squads.members.kick',
    'uses' => 'SquadsController@kickMember',
]);

Route::group(['prefix' => 'applications'], function () {
    Route::get('/{id}', [
        'as'   => 'squads.application',
        'uses' => 'SquadsController@showApplication',
    ]);

    Route::post('/{id}', [
        'as'   => 'squads.application.approve',
        'uses' => 'SquadsController@approveApplication',
    ]);

    Route::delete('/{id}', [
        'as'   => 'squads.application.reject',
        'uses' => 'SquadsController@rejectApplication',
    ]);
});

Route::group(['prefix' => '/ajax'], function () {
    Route::get('/{id}/members', [
        'as'   => 'squads.members',
        'uses' => 'SquadsController@getSquadMembers',
    ]);

    Route::get('/{id}/candidates', [
        'as'   => 'squads.candidates',
        'uses' => 'SquadsController@getSquadCandidates',
    ]);
});
