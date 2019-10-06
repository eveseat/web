<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2020 Leon Jacobs
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
    'as'   => 'corporation.list',
    'uses' => 'CorporationsController@index',
]);

Route::get('/view/assets/{corporation_id}', [
    'as'         => 'corporation.view.assets',
    'middleware' => 'corporationbouncer:assets',
    'uses'       => 'AssetsController@getAssets',
]);

Route::get('/view/assets/contents/{corporation_id}/{item_id}', [
    'as'         => 'corporation.view.assets.contents',
    'middleware' => 'corporationbouncer:assets',
    'uses'       => 'AssetsController@getAssetsContents',
]);

Route::get('/{corporation_id}/bookmarks', [
    'as'         => 'corporation.view.bookmarks',
    'middleware' => 'corporationbouncer:bookmarks',
    'uses'       => 'BookmarksController@index',
]);

Route::get('/{corporation_id}/contacts', [
    'as'         => 'corporation.view.contacts',
    'middleware' => 'corporationbouncer:contacts',
    'uses'       => 'ContactsController@index',
]);

Route::get('/{corporation_id}/contracts', [
    'as'         => 'corporation.view.contracts',
    'middleware' => 'corporationbouncer:contracts',
    'uses'       => 'ContractsController@index',
]);

Route::get('/{corporation_id}/contracts/{contract_id}', [
    'as'         => 'corporation.view.contracts.items',
    'middleware' => 'corporationbouncer:contracts',
    'uses'       => 'ContractsController@show',
]);

Route::get('/{corporation_id}/industry', [
    'as'         => 'corporation.view.industry',
    'middleware' => 'corporationbouncer:industry',
    'uses'       => 'IndustryController@index',
]);

Route::get('/view/industry/data/{corporation_id}', [
    'as'         => 'corporation.view.industry.data',
    'middleware' => 'corporationbouncer:industry',
    'uses'       => 'IndustryController@getIndustryData',
]);

Route::get('/{corporation_id}/killmails', [
    'as'         => 'corporation.view.killmails',
    'middleware' => 'corporationbouncer:killmails',
    'uses'       => 'KillmailsController@index',
]);

Route::get('/view/killmails/data/{corporation_id}', [
    'as'         => 'corporation.view.killmails.data',
    'middleware' => 'corporationbouncer:killmails',
    'uses'       => 'KillmailsController@getKillmailsData',
]);

Route::get('/{corporation_id}/markets', [
    'as'         => 'corporation.view.market',
    'middleware' => 'corporationbouncer:market',
    'uses'       => 'MarketController@index',
]);

Route::get('/{corporation_id}/mining-ledger/{year?}/{month?}', [
    'as'         => 'corporation.view.mining_ledger',
    'middleware' => 'corporationbouncer:mining',
    'uses'       => 'MiningLedgerController@show',
]);

Route::get('/view/pocos/{corporation_id}', [
    'as'         => 'corporation.view.pocos',
    'middleware' => 'corporationbouncer:pocos',
    'uses'       => 'IndustryController@getPoco',
]);

Route::get('/view/extractions/{corporation_id}', [
    'as'         => 'corporation.view.extractions',
    'middleware' => 'corporationbouncer:extractions',
    'uses'       => 'ExtractionController@getExtractions',
]);

Route::post('/view/extractions/report', [
    'as'         => 'corporation.view.extractions.probe-report',
    'middleware' => 'corporationbouncer:extractions',
    'uses'       => 'ExtractionController@postProbeReport',
]);

Route::group(['prefix' => 'view/security'], function () {

    Route::get('roles/{corporation_id}', [
        'as'         => 'corporation.view.security.roles',
        'middleware' => 'corporationbouncer:security',
        'uses'       => 'SecurityController@getRoles',
    ]);

    Route::get('titles/{corporation_id}', [
        'as'         => 'corporation.view.security.titles',
        'middleware' => 'corporationbouncer:security',
        'uses'       => 'SecurityController@getTitles',
    ]);

});

Route::get('/{corporation_id}/security/logs', [
    'as' => 'corporation.view.security.log',
    'middleware' => 'corporationbouncer:security',
    'uses'       => 'SecurityController@getLogs',
]);

Route::group(['prefix' => 'view/ledger'], function () {

    Route::get('summary/{corporation_id}', [
        'as'         => 'corporation.view.ledger.summary',
        'middleware' => 'corporationbouncer:ledger',
        'uses'       => 'LedgerController@getWalletSummary',
    ]);

    Route::get('bountyprizes/{corporation_id}/{year?}/{month?}', [
        'as'         => 'corporation.view.ledger.bountyprizesbymonth',
        'middleware' => 'corporationbouncer:ledger',
        'uses'       => 'LedgerController@getBountyPrizesByMonth',
    ]);

    Route::get('planetaryinteraction/{corporation_id}/{year?}/{month?}', [
        'as'         => 'corporation.view.ledger.planetaryinteraction',
        'middleware' => 'corporationbouncer:ledger',
        'uses'       => 'LedgerController@getPlanetaryInteractionByMonth',
    ]);

});

Route::get('/{corporation_id}/summary', [
    'as'         => 'corporation.view.summary',
    'middleware' => 'corporationbouncer:summary',
    'uses'       => 'SummaryController@show',
]);

Route::get('/{corporation_id}/standings', [
    'as'         => 'corporation.view.standings',
    'middleware' => 'corporationbouncer:standings',
    'uses'       => 'StandingsController@index',
]);

Route::get('/view/starbases/{corporation_id}', [
    'as'         => 'corporation.view.starbases',
    'middleware' => 'corporationbouncer:starbases',
    'uses'       => 'StarbaseController@getStarbases',
]);

Route::post('/view/starbase/modules/{corporation_id}', [
    'as'         => 'corporation.view.starbase.modules',
    'middleware' => 'corporationbouncer:starbases',
    'uses'       => 'StarbaseController@postStarbaseModules',
]);

Route::get('/view/structures/{corporation_id}', [
    'as'         => 'corporation.view.structures',
    'middleware' => 'corporationbouncer:structures',
    'uses'       => 'StructureController@getStructures',
]);

Route::get('/view/tracking/{corporation_id}', [
    'as'         => 'corporation.view.tracking',
    'middleware' => 'corporationbouncer:tracking',
    'uses'       => 'TrackingController@getTracking',
]);

Route::get('/view/tracking/{corporation_id}/membertracking', [
    'as'         => 'corporation.view.tracking.data',
    'middleware' => 'corporationbouncer:tracking',
    'uses'       => 'TrackingController@getMemberTracking',
]);

Route::get('/{corporation_id}/journal', [
    'as'         => 'corporation.view.journal',
    'middleware' => 'corporationbouncer:journal',
    'uses'       => 'WalletController@journal',
]);

Route::get('/view/journal/data/{corporation_id}', [
    'as'         => 'corporation.view.journal.data',
    'middleware' => 'corporationbouncer:journal',
    'uses'       => 'WalletController@getJournalData',
]);

Route::get('/{corporation_id}/transactions', [
    'as'         => 'corporation.view.transactions',
    'middleware' => 'corporationbouncer:transactions',
    'uses'       => 'WalletController@transactions',
]);

Route::get('/view/transactions/data/{corporation_id}', [
    'as'         => 'corporation.view.transactions.data',
    'middleware' => 'corporationbouncer:transactions',
    'uses'       => 'WalletController@getTransactionsData',
]);
