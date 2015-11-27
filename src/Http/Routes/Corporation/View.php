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

Route::any('/list', [
    'as'   => 'corporation.list',
    'uses' => 'ViewController@getCorporations'
]);

Route::get('/view/assets/{corporation_id}', [
    'as'         => 'corporation.view.assets',
    'middleware' => 'corporationbouncer:assets',
    'uses'       => 'ViewController@getAssets'
]);

Route::get('/view/contacts/{corporation_id}', [
    'as'         => 'corporation.view.contacts',
    'middleware' => 'corporationbouncer:contacts',
    'uses'       => 'ViewController@getContacts'
]);

Route::get('/view/contracts/{corporation_id}', [
    'as'         => 'corporation.view.contracts',
    'middleware' => 'corporationbouncer:contracts',
    'uses'       => 'ViewController@getContracts'
]);

Route::get('/view/industry/{corporation_id}', [
    'as'         => 'corporation.view.industry',
    'middleware' => 'corporationbouncer:industry',
    'uses'       => 'ViewController@getIndustry'
]);

Route::get('/view/killmails/{corporation_id}', [
    'as'         => 'corporation.view.killmails',
    'middleware' => 'corporationbouncer:killmails',
    'uses'       => 'ViewController@getKillmails'
]);

Route::get('/view/market/{corporation_id}', [
    'as'         => 'corporation.view.market',
    'middleware' => 'corporationbouncer:market',
    'uses'       => 'ViewController@getMarket'
]);

Route::group(['prefix' => 'view/security'], function () {

    Route::get('roles/{corporation_id}', [
        'as'         => 'corporation.view.security.roles',
        'middleware' => 'corporationbouncer:security',
        'uses'       => 'SecurityController@getRoles'
    ]);

    Route::get('titles/{corporation_id}', [
        'as'         => 'corporation.view.security.titles',
        'middleware' => 'corporationbouncer:security',
        'uses'       => 'SecurityController@getTitles'
    ]);

    Route::get('log/{corporation_id}', [
        'as'         => 'corporation.view.security.log',
        'middleware' => 'corporationbouncer:security',
        'uses'       => 'SecurityController@getLog'
    ]);

});

Route::get('/view/summary/{corporation_id}', [
    'as'         => 'corporation.view.summary',
    'middleware' => 'corporationbouncer:summary',
    'uses'       => 'ViewController@getSummary'
]);

Route::get('/view/standings/{corporation_id}', [
    'as'         => 'corporation.view.standings',
    'middleware' => 'corporationbouncer:standings',
    'uses'       => 'ViewController@getStandings'
]);

Route::get('/view/tracking/{corporation_id}', [
    'as'         => 'corporation.view.tracking',
    'middleware' => 'corporationbouncer:tracking',
    'uses'       => 'ViewController@getTracking'
]);

Route::get('/view/journal/{corporation_id}', [
    'as'         => 'corporation.view.journal',
    'middleware' => 'corporationbouncer:journal',
    'uses'       => 'ViewController@getJournal'
]);

Route::get('/view/transactions/{corporation_id}', [
    'as'         => 'corporation.view.transactions',
    'middleware' => 'corporationbouncer:transactions',
    'uses'       => 'ViewController@getTransactions'
]);
