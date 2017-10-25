<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015, 2016, 2017  Leon Jacobs
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
    'as'         => 'people.list',
    'middleware' => 'bouncer:people.view',
    'uses'       => 'PeopleController@getAll',
]);

Route::get('/search', [
    'as'         => 'people.search',
    'middleware' => 'bouncer:people.view',
    'uses'       => 'PeopleController@getSearchPeopleGroups',
]);

Route::get('/new/group/{character_id}', [
    'as'         => 'people.new.group',
    'middleware' => 'bouncer:people.create',
    'uses'       => 'PeopleController@getNewGroup',
]);

Route::post('/add/existing', [
    'as'         => 'people.add.group.existing',
    'middleware' => 'bouncer:people.edit',
    'uses'       => 'PeopleController@getAddToExisting',
]);

Route::get('/set/main/key/{group_id}/{character_id}', [
    'as'         => 'people.set.main',
    'middleware' => 'bouncer:people.edit',
    'uses'       => 'PeopleController@setGroupMain',
]);

Route::get('/remove/group/{group_id}', [
    'as'         => 'people.remove.group',
    'middleware' => 'bouncer:people.edit',
    'uses'       => 'PeopleController@removeGroup',
]);

Route::get('/remove/group/key/{group_id}/{key_id}', [
    'as'         => 'people.remove.group.key',
    'middleware' => 'bouncer:people.edit',
    'uses'       => 'PeopleController@removeKeyFromGroup',
]);
