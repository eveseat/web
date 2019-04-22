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
    'as'   => 'corporation.list',
    'uses' => 'CorporationsController@getCorporations',
]);

Route::get('/data/', [
    'as'   => 'corporation.list.data',
    'uses' => 'CorporationsController@getCorporationsData',
]);

Route::get('/{corporation_id}/delete/', [
    'as'         => 'corporation.delete',
    'middleware' => 'bouncer:superuser',
    'uses'       => 'CorporationsController@deleteCorporation',
]);

Route::get('/{corporation_id}/assets/', [
    'as'         => 'corporation.view.assets',
    'middleware' => 'corporationbouncer:assets',
    'uses'       => 'AssetsController@getAssets',
])->where('corporation_id', '[0-9]+');

Route::get('/{corporation_id}/assets/{item_id}/contents/', [
    'as'         => 'corporation.view.assets.contents',
    'middleware' => 'corporationbouncer:assets',
    'uses'       => 'AssetsController@getAssetsContents',
])->where('corporation_id', '[0-9]+')
  ->where('item_id', '[0-9]+');

Route::get('/{corporation_id}/bookmarks/', [
    'as'         => 'corporation.view.bookmarks',
    'middleware' => 'corporationbouncer:bookmarks',
    'uses'       => 'BookmarksController@getBookmarks',
])->where('corporation_id', '[0-9]+');

Route::get('/{corporation_id}/contacts/', [
    'as'         => 'corporation.view.contacts',
    'middleware' => 'corporationbouncer:contacts',
    'uses'       => 'ContactsController@getContacts',
])->where('corporation_id', '[0-9]+');

Route::get('/{corporation_id}/contracts/', [
    'as'         => 'corporation.view.contracts',
    'middleware' => 'corporationbouncer:contracts',
    'uses'       => 'ContractsController@getContracts',
])->where('corporation_id', '[0-9]+');

Route::get('/{corporation_id}/contracts/data/', [
    'as'         => 'corporation.view.contracts.data',
    'middleware' => 'corporationbouncer:contracts',
    'uses'       => 'ContractsController@getContractsData',
])->where('corporation_id', '[0-9]+');

Route::get('/{corporation_id}/contracts/{contract_id}/items/', [
    'as'         => 'corporation.view.contracts.items',
    'middleware' => 'corporationbouncer:contracts',
    'uses'       => 'ContractsController@getContractsItemsData',
])->where('corporation_id', '[0-9]+')
  ->where('contract_id', '[0-9]+');

Route::get('/{corporation_id}/industry/', [
    'as'         => 'corporation.view.industry',
    'middleware' => 'corporationbouncer:industry',
    'uses'       => 'IndustryController@getIndustry',
])->where('corporation_id', '[0-9]+');

Route::get('/{corporation_id}/industry/data/', [
    'as'         => 'corporation.view.industry.data',
    'middleware' => 'corporationbouncer:industry',
    'uses'       => 'IndustryController@getIndustryData',
])->where('corporation_id', '[0-9]+');

Route::get('/{corporation_id}/killmails/', [
    'as'         => 'corporation.view.killmails',
    'middleware' => 'corporationbouncer:killmails',
    'uses'       => 'KillmailsController@getKillmails',
])->where('corporation_id', '[0-9]+');

Route::get('/{corporation_id}/killmails/data/', [
    'as'         => 'corporation.view.killmails.data',
    'middleware' => 'corporationbouncer:killmails',
    'uses'       => 'KillmailsController@getKillmailsData',
])->where('corporation_id', '[0-9]+');

Route::get('/{corporation_id}/market/', [
    'as'         => 'corporation.view.market',
    'middleware' => 'corporationbouncer:market',
    'uses'       => 'MarketController@getMarket',
])->where('corporation_id', '[0-9]+');

Route::get('/{corporation_id}/market/data/', [
    'as'         => 'corporation.view.market.data',
    'middleware' => 'corporationbouncer:market',
    'uses'       => 'MarketController@getMarketData',
])->where('corporation_id', '[0-9]+');

Route::get('/{corporation_id}/mining-ledger/{year?}/{month?}/', [
    'as'         => 'corporation.view.mining_ledger',
    'middleware' => 'corporationbouncer:mining',
    'uses'       => 'MiningLedgerController@getLedger',
])->where('corporation_id', '[0-9]+')
  ->where('year', '[0-9]{4}')
  ->where('month', '[0-9]{2}');

Route::get('/{corporation_id}/pocos/', [
    'as'         => 'corporation.view.pocos',
    'middleware' => 'corporationbouncer:pocos',
    'uses'       => 'IndustryController@getPoco',
])->where('corporation_id', '[0-9]+');

Route::get('/{corporation_id}/extractions/', [
    'as'         => 'corporation.view.extractions',
    'middleware' => 'corporationbouncer:extractions',
    'uses'       => 'ExtractionController@getExtractions',
])->where('corporation_id', '[0-9]+');

Route::post('/extractions/report/', [
    'as'         => 'corporation.view.extractions.probe-report',
    'middleware' => 'corporationbouncer:extractions',
    'uses'       => 'ExtractionController@postProbeReport',
]);

Route::group(['prefix' => '{corporation_id}/security'], function () {

    Route::get('roles/', [
        'as'         => 'corporation.view.security.roles',
        'middleware' => 'corporationbouncer:security',
        'uses'       => 'SecurityController@getRoles',
    ])->where('corporation_id', '[0-9]+');

    Route::get('titles/', [
        'as'         => 'corporation.view.security.titles',
        'middleware' => 'corporationbouncer:security',
        'uses'       => 'SecurityController@getTitles',
    ])->where('corporation_id', '[0-9]+');

    Route::get('log/', [
        'as'         => 'corporation.view.security.log',
        'middleware' => 'corporationbouncer:security',
        'uses'       => 'SecurityController@getLog',
    ])->where('corporation_id', '[0-9]+');

});

Route::group(['prefix' => '{corporation_id}/ledger'], function () {

    Route::get('summary/', [
        'as'         => 'corporation.view.ledger.summary',
        'middleware' => 'corporationbouncer:ledger',
        'uses'       => 'LedgerController@getWalletSummary',
    ])->where('corporation_id', '[0-9]+');

    Route::get('bountyprizes/{year?}/{month?}/', [
        'as'         => 'corporation.view.ledger.bountyprizesbymonth',
        'middleware' => 'corporationbouncer:ledger',
        'uses'       => 'LedgerController@getBountyPrizesByMonth',
    ])->where('corporation_id', '[0-9]+')
      ->where('year', '[0-9]{4}')
      ->where('month', '[0-9]{1,2}');

    Route::get('planetaryinteraction/{year?}/{month?}/', [
        'as'         => 'corporation.view.ledger.planetaryinteraction',
        'middleware' => 'corporationbouncer:ledger',
        'uses'       => 'LedgerController@getPlanetaryInteractionByMonth',
    ])->where('corporation_id', '[0-9]+')
      ->where('year', '[0-9]{4}')
      ->where('month', '[0-9]{1,2}');

});

Route::get('/{corporation_id}/summary/', [
    'as'         => 'corporation.view.summary',
    'middleware' => 'corporationbouncer:summary',
    'uses'       => 'SummaryController@getSummary',
])->where('corporation_id', '[0-9]+');

Route::get('/{corporation_id}/standings/', [
    'as'         => 'corporation.view.standings',
    'middleware' => 'corporationbouncer:standings',
    'uses'       => 'StandingsController@getStandings',
])->where('corporation_id', '[0-9]+');

Route::get('/{corporation_id}/starbases/', [
    'as'         => 'corporation.view.starbases',
    'middleware' => 'corporationbouncer:starbases',
    'uses'       => 'StarbaseController@getStarbases',
])->where('corporation_id', '[0-9]+');

Route::post('/{corporation_id}/starbase/modules/', [
    'as'         => 'corporation.view.starbase.modules',
    'middleware' => 'corporationbouncer:starbases',
    'uses'       => 'StarbaseController@postStarbaseModules',
])->where('corporation_id', '[0-9]+');

Route::get('/{corporation_id}/structures/', [
    'as'         => 'corporation.view.structures',
    'middleware' => 'corporationbouncer:structures',
    'uses'       => 'StructureController@getStructures',
])->where('corporation_id', '[0-9]+');

Route::get('/{corporation_id}/tracking/', [
    'as'         => 'corporation.view.tracking',
    'middleware' => 'corporationbouncer:tracking',
    'uses'       => 'TrackingController@getTracking',
])->where('corporation_id', '[0-9]+');

Route::get('/{corporation_id}/tracking/membertracking/', [
    'as'         => 'corporation.view.tracking.data',
    'middleware' => 'corporationbouncer:tracking',
    'uses'       => 'TrackingController@getMemberTracking',
])->where('corporation_id', '[0-9]+');

Route::get('/{corporation_id}/journal/', [
    'as'         => 'corporation.view.journal',
    'middleware' => 'corporationbouncer:journal',
    'uses'       => 'WalletController@getJournal',
])->where('corporation_id', '[0-9]+');

Route::get('/{corporation_id}/journal/data/', [
    'as'         => 'corporation.view.journal.data',
    'middleware' => 'corporationbouncer:journal',
    'uses'       => 'WalletController@getJournalData',
])->where('corporation_id', '[0-9]+');

Route::get('/{corporation_id}/transactions/', [
    'as'         => 'corporation.view.transactions',
    'middleware' => 'corporationbouncer:transactions',
    'uses'       => 'WalletController@getTransactions',
])->where('corporation_id', '[0-9]+');

Route::get('/{corporation_id}/transactions/data/', [
    'as'         => 'corporation.view.transactions.data',
    'middleware' => 'corporationbouncer:transactions',
    'uses'       => 'WalletController@getTransactionsData',
])->where('corporation_id', '[0-9]+');
