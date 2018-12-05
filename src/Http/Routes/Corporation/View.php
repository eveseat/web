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

Route::get('/list', [
    'as'   => 'corporation.list',
    'uses' => 'CorporationsController@getCorporations',
]);

Route::get('/list/data', [
    'as'   => 'corporation.list.data',
    'uses' => 'CorporationsController@getCorporationsData',
]);

Route::get('/delete/{corporation_id}', [
    'as'         => 'corporation.delete',
    'middleware' => 'bouncer:superuser',
    'uses'       => 'CorporationsController@deleteCorporation',
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

Route::get('/view/bookmarks/{corporation_id}', [
    'as'         => 'corporation.view.bookmarks',
    'middleware' => 'corporationbouncer:bookmarks',
    'uses'       => 'BookmarksController@getBookmarks',
]);

Route::get('/view/contacts/{corporation_id}', [
    'as'         => 'corporation.view.contacts',
    'middleware' => 'corporationbouncer:contacts',
    'uses'       => 'ContactsController@getContacts',
]);

Route::get('/view/contracts/{corporation_id}', [
    'as'         => 'corporation.view.contracts',
    'middleware' => 'corporationbouncer:contracts',
    'uses'       => 'ContractsController@getContracts',
]);

Route::get('/view/contracts/data/{corporation_id}', [
    'as'         => 'corporation.view.contracts.data',
    'middleware' => 'corporationbouncer:contracts',
    'uses'       => 'ContractsController@getContractsData',
]);

Route::get('/view/contracts/items/{corporation_id}/{contract_id}', [
    'as'         => 'corporation.view.contracts.items',
    'middleware' => 'corporationbouncer:contracts',
    'uses'       => 'ContractsController@getContractsItemsData',
]);

Route::get('/view/industry/{corporation_id}', [
    'as'         => 'corporation.view.industry',
    'middleware' => 'corporationbouncer:industry',
    'uses'       => 'IndustryController@getIndustry',
]);

Route::get('/view/industry/data/{corporation_id}', [
    'as'         => 'corporation.view.industry.data',
    'middleware' => 'corporationbouncer:industry',
    'uses'       => 'IndustryController@getIndustryData',
]);

Route::get('/view/killmails/{corporation_id}', [
    'as'         => 'corporation.view.killmails',
    'middleware' => 'corporationbouncer:killmails',
    'uses'       => 'KillmailsController@getKillmails',
]);

Route::get('/view/killmails/data/{corporation_id}', [
    'as'         => 'corporation.view.killmails.data',
    'middleware' => 'corporationbouncer:killmails',
    'uses'       => 'KillmailsController@getKillmailsData',
]);

Route::get('/view/market/{corporation_id}', [
    'as'         => 'corporation.view.market',
    'middleware' => 'corporationbouncer:market',
    'uses'       => 'MarketController@getMarket',
]);

Route::get('/view/market/data/{corporation_id}', [
    'as'         => 'corporation.view.market.data',
    'middleware' => 'corporationbouncer:market',
    'uses'       => 'MarketController@getMarketData',
]);

Route::get('/view/mining-ledger/{corporation_id}/{year?}/{month?}', [
    'as'         => 'corporation.view.mining_ledger',
    'middleware' => 'corporationbouncer:mining',
    'uses'       => 'MiningLedgerController@getLedger',
]);

Route::get('/view/pocos/{corporation_id}', [
    'as'         => 'corporation.view.pocos',
    'middleware' => 'corporationbouncer:pocos',
    'uses'       => 'IndustryController@getPoco',
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

    Route::get('log/{corporation_id}', [
        'as'         => 'corporation.view.security.log',
        'middleware' => 'corporationbouncer:security',
        'uses'       => 'SecurityController@getLog',
    ]);

});

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

Route::get('/view/summary/{corporation_id}', [
    'as'         => 'corporation.view.summary',
    'middleware' => 'corporationbouncer:summary',
    'uses'       => 'SummaryController@getSummary',
]);

Route::get('/view/standings/{corporation_id}', [
    'as'         => 'corporation.view.standings',
    'middleware' => 'corporationbouncer:standings',
    'uses'       => 'StandingsController@getStandings',
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

Route::get('/view/journal/{corporation_id}', [
    'as'         => 'corporation.view.journal',
    'middleware' => 'corporationbouncer:journal',
    'uses'       => 'WalletController@getJournal',
]);

Route::get('/view/journal/data/{corporation_id}', [
    'as'         => 'corporation.view.journal.data',
    'middleware' => 'corporationbouncer:journal',
    'uses'       => 'WalletController@getJournalData',
]);

Route::get('/view/transactions/{corporation_id}', [
    'as'         => 'corporation.view.transactions',
    'middleware' => 'corporationbouncer:transactions',
    'uses'       => 'WalletController@getTransactions',
]);

Route::get('/view/transactions/data/{corporation_id}', [
    'as'         => 'corporation.view.transactions.data',
    'middleware' => 'corporationbouncer:transactions',
    'uses'       => 'WalletController@getTransactionsData',
]);
