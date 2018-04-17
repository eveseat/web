<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015, 2016, 2017, 2018  Leon Jacobs
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

Route::get('/add', [
    'as'   => 'api.key',
    'uses' => 'KeyController@getAdd',
]);

Route::post('/add', [
    'as'   => 'api.key.add',
    'uses' => 'KeyController@addKey',
]);

Route::post('/check', [
    'as'   => 'api.key.check',
    'uses' => 'KeyController@checkKey',
]);

Route::get('/list', [
    'as'   => 'api.key.list',
    'uses' => 'KeyController@listAll',
]);

Route::get('/list/data', [
    'as'   => 'api.key.list.data',
    'uses' => 'KeyController@listAllData',
]);

Route::get('/delete/{key_id}', [
    'as'         => 'api.key.delete',
    'uses'       => 'KeyController@getDelete',
    'middleware' => 'keybouncer:delete',
]);

Route::get('/detail/{key_id}', [
    'as'         => 'api.key.detail',
    'uses'       => 'KeyController@getDetail',
    'middleware' => 'keybouncer:detail',
]);

Route::get('/enable/all', [
    'as'   => 'api.key.enable.all',
    'uses' => 'KeyController@getEnableAll',
]);

Route::get('/enable/{key_id}', [
    'as'         => 'api.key.enable',
    'uses'       => 'KeyController@getEnable',
    'middleware' => 'keybouncer:toggle_status',
]);

Route::get('/disable/all', [
    'as'         => 'api.key.disable.all',
    'uses'       => 'KeyController@getDisableAll',
    'middleware' => 'keybouncer:toggle_status',
]);

Route::get('/disable/{key_id}', [
    'as'         => 'api.key.disable',
    'uses'       => 'KeyController@getDisable',
    'middleware' => 'keybouncer:toggle_status',
]);

Route::get('/update/{key_id}', [
    'as'         => 'api.key.queue',
    'uses'       => 'KeyController@queueUpdateJob',
    'middleware' => 'keybouncer:update',
]);

Route::post('/transfer/{key_id}', [
    'as'         => 'api.key.transfer',
    'uses'       => 'KeyController@transfer',
    'middleware' => 'keybouncer:update',
]);

Route::post('/worker/constraints', [
    'as'         => 'api.key.worker.constraints',
    'uses'       => 'KeyController@postUpdateWorkerConstraint',
    'middleware' => 'bouncer:superuser',
]);

Route::get('/joblog/{key_id}', [
    'as'         => 'api.key.joblog',
    'uses'       => 'KeyController@getJobLog',
    'middleware' => 'keybouncer:detail',
]);

Route::get('/joblog/data/{key_id}', [
    'as'         => 'api.key.joblog.data',
    'uses'       => 'KeyController@getJobLogData',
    'middleware' => 'keybouncer:detail',
]);
