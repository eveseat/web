<?php
/*
This file is part of SeAT

Copyright (C) 2015, 2016  Leon Jacobs

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

Route::get('/view/bookmarks/{corporation_id}', [
    'as'         => 'corporation.view.bookmarks',
    'middleware' => 'corporationbouncer:bookmarks',
    'uses'       => 'ViewController@getBookmarks'
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

Route::get('/view/pocos/{corporation_id}', [
    'as'         => 'corporation.view.pocos',
    'middleware' => 'corporationbouncer:pocos',
    'uses'       => 'ViewController@getPoco'
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

Route::group(['prefix' => 'view/ledger'], function () {

    Route::get('summary/{corporation_id}', [
        'as'         => 'corporation.view.ledger.summary',
        'middleware' => 'corporationbouncer:journal',
        'uses'       => 'LedgerController@getWalletSummary'
    ]);

    Route::get('bountyprizes/{corporation_id}/{year?}/{month?}', [
        'as'         => 'corporation.view.ledger.bountyprizesbymonth',
        'middleware' => 'corporationbouncer:journal',
        'uses'       => 'LedgerController@getBountyPrizesByMonth'
    ]);

    Route::get('planetaryinteraction/{corporation_id}/{year?}/{month?}', [
        'as'         => 'corporation.view.ledger.planetaryinteraction',
        'middleware' => 'corporationbouncer:journal',
        'uses'       => 'LedgerController@getPlanetaryInteractionByMonth'
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

Route::get('/view/starbases/{corporation_id}', [
    'as'         => 'corporation.view.starbases',
    'middleware' => 'corporationbouncer:starbases',
    'uses'       => 'ViewController@getStarbases'
]);

Route::get('/view/starbases/silos/{corporation_id}', [
    'as'         => 'corporation.view.starbases.silos',
    'middleware' => 'corporationbouncer:starbases',
    'uses'       => 'ViewController@getStarbaseSilos'
]);

Route::post('/view/starbase/modules/{corporation_id}', [
    'as'         => 'corporation.view.starbase.modules',
    'middleware' => 'corporationbouncer:starbases',
    'uses'       => 'ViewController@postStarbaseModules'
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
