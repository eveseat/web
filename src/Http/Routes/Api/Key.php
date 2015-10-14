<?php
/*
This file is part of SeAT

Copyright (C) 2015  Leon Jacobs

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/

Route::get('/add', [
    'as'   => 'api.key',
    'uses' => 'KeyController@getAdd'
]);

Route::post('/add', [
    'as'   => 'api.key.add',
    'uses' => 'KeyController@addKey'
]);

Route::post('/check', [
    'as'   => 'api.key.check',
    'uses' => 'KeyController@checkKey'
]);

Route::get('/list', [
    'as'   => 'api.key.list',
    'uses' => 'KeyController@listAll',
]);

Route::get('/delete/{key_id}', [
    'as'         => 'api.key.delete',
    'uses'       => 'KeyController@getDelete',
    'middleware' => 'keybouncer:delete'
]);

Route::get('/detail/{key_id}', [
    'as'         => 'api.key.detail',
    'uses'       => 'KeyController@getDetail',
    'middleware' => 'keybouncer:detail'
]);

Route::get('/update/{key_id}', [
    'as'         => 'api.key.queue',
    'uses'       => 'KeyController@queueUpdateJob',
    'middleware' => 'keybouncer:update'
]);
