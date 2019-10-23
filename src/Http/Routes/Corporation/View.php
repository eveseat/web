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
    'uses' => 'CorporationsController@index',
]);

Route::get('/{corporation_id}', [
    'as'         => 'corporation.view.default',
    'uses'       => 'CorporationsController@show',
])->where('corporation_id', '[0-9]+');

Route::get('/{corporation_id}/assets', [
    'as'         => 'corporation.view.assets',
    'middleware' => 'corporationbouncer:asset',
    'uses'       => 'AssetsController@getAssets',
]);

Route::get('/{corporation_id}/assets/contents/{item_id}', [
    'as'         => 'corporation.view.assets.contents',
    'middleware' => 'corporationbouncer:asset',
    'uses'       => 'AssetsController@getAssetsContents',
]);

Route::get('/{corporation_id}/bookmarks', [
    'as'         => 'corporation.view.bookmarks',
    'middleware' => 'corporationbouncer:bookmark',
    'uses'       => 'BookmarksController@index',
]);

Route::get('/{corporation_id}/contacts', [
    'as'         => 'corporation.view.contacts',
    'middleware' => 'corporationbouncer:contact',
    'uses'       => 'ContactsController@index',
]);

Route::get('/{corporation_id}/contracts', [
    'as'         => 'corporation.view.contracts',
    'middleware' => 'corporationbouncer:contract',
    'uses'       => 'ContractsController@index',
]);

Route::get('/{corporation_id}/contracts/{contract_id}', [
    'as'         => 'corporation.view.contracts.items',
    'middleware' => 'corporationbouncer:contract',
    'uses'       => 'ContractsController@show',
]);

Route::get('/{corporation_id}/industry', [
    'as'         => 'corporation.view.industry',
    'middleware' => 'corporationbouncer:industry',
    'uses'       => 'IndustryController@index',
]);

Route::get('/{corporation_id}/blueprint', [
    'as'         => 'corporation.view.blueprint',
    'middleware' => 'corporationbouncer:blueprint',
    'uses'       => 'BlueprintController@index',
]);

Route::get('/{corporation_id}/killmails', [
    'as'         => 'corporation.view.killmails',
    'middleware' => 'corporationbouncer:killmail',
    'uses'       => 'KillmailsController@index',
]);

Route::get('/{corporation_id}/markets', [
    'as'         => 'corporation.view.market',
    'middleware' => 'corporationbouncer:market',
    'uses'       => 'MarketController@index',
]);

Route::get('/{corporation_id}/mining-ledger/{year?}/{month?}', [
    'as'         => 'corporation.view.mining_ledger',
    'middleware' => 'corporationbouncer:mining',
    'uses'       => 'MiningLedgerController@index',
]);

Route::get('/{corporation_id}/customs-offices', [
    'as'         => 'corporation.view.customs-offices',
    'middleware' => 'corporationbouncer:customs-office',
    'uses'       => 'CustomOfficeController@index',
]);

Route::get('/{corporation_id}/extractions', [
    'as'         => 'corporation.view.extractions',
    'middleware' => 'corporationbouncer:extraction',
    'uses'       => 'ExtractionController@getExtractions',
]);

Route::post('/extractions/report', [
    'as'         => 'corporation.view.extractions.probe-report',
    'middleware' => 'corporationbouncer:extraction',
    'uses'       => 'ExtractionController@postProbeReport',
]);

Route::group(['prefix' => '{corporation_id}/security'], function () {

    Route::get('roles', [
        'as'         => 'corporation.view.security.roles',
        'middleware' => 'corporationbouncer:security',
        'uses'       => 'SecurityController@getRoles',
    ]);

    Route::get('titles', [
        'as'         => 'corporation.view.security.titles',
        'middleware' => 'corporationbouncer:security',
        'uses'       => 'SecurityController@getTitles',
    ]);

    Route::get('logs', [
        'as' => 'corporation.view.security.log',
        'middleware' => 'corporationbouncer:security',
        'uses'       => 'SecurityController@getLogs',
    ]);
});

Route::group(['prefix' => '{corporation_id}/ledger'], function () {

    Route::get('summary', [
        'as'         => 'corporation.view.ledger.summary',
        'middleware' => 'corporationbouncer:ledger',
        'uses'       => 'LedgerController@getWalletSummary',
    ]);

    Route::get('bounty-prizes/{year?}/{month?}', [
        'as'         => 'corporation.view.ledger.bounty_prizes',
        'middleware' => 'corporationbouncer:ledger',
        'uses'       => 'LedgerController@getBountyPrizesByMonth',
    ]);

    Route::get('planetaryinteraction/{year?}/{month?}', [
        'as'         => 'corporation.view.ledger.planetary_interaction',
        'middleware' => 'corporationbouncer:ledger',
        'uses'       => 'LedgerController@getPlanetaryInteractionByMonth',
    ]);

    Route::get('offices-rentals/{year?}/{month?}', [
        'as'         => 'corporation.view.ledger.offices_rentals',
        'middleware' => 'corporationbouncer:ledger',
        'uses'       => 'LedgerController@getOfficesRentalsByMonth',
    ]);

    Route::get('industry-facility/{year?}/{month?}', [
        'as'         => 'corporation.view.ledger.industry_facility',
        'middleware' => 'corporationbouncer:ledger',
        'uses'       => 'LedgerController@getIndustryFacilityByMonth',
    ]);

    Route::get('reprocessing/{year?}/{month?}', [
        'as'         => 'corporation.view.ledger.reprocessing',
        'middleware' => 'corporationbouncer:ledger',
        'uses'       => 'LedgerController@getReprocessingByMonth',
    ]);

    Route::get('jump-clones/{year?}/{month?}', [
        'as'         => 'corporation.view.ledger.jump_clones',
        'middleware' => 'corporationbouncer:ledger',
        'uses'       => 'LedgerController@getJumpClonesByMonth',
    ]);

    Route::get('jump-bridges/{year?}/{month?}', [
        'as'         => 'corporation.view.ledger.jump_bridges',
        'middleware' => 'corporationbouncer:ledger',
        'uses'       => 'LedgerController@getJumpBridgesByMonth',
    ]);
});

Route::get('/{corporation_id}/summary', [
    'as'         => 'corporation.view.summary',
    'middleware' => 'corporationbouncer:summary',
    'uses'       => 'SummaryController@show',
]);

Route::get('/{corporation_id}/standings', [
    'as'         => 'corporation.view.standings',
    'middleware' => 'corporationbouncer:standing',
    'uses'       => 'StandingsController@index',
]);

Route::get('/{corporation_id}/starbases', [
    'as'         => 'corporation.view.starbases',
    'middleware' => 'corporationbouncer:starbase',
    'uses'       => 'StarbaseController@getStarbases',
]);

Route::post('/{corporation_id}/starbase/modules', [
    'as'         => 'corporation.view.starbase.modules',
    'middleware' => 'corporationbouncer:starbase',
    'uses'       => 'StarbaseController@postStarbaseModules',
]);

Route::get('/{corporation_id}/structures', [
    'as'         => 'corporation.view.structures',
    'middleware' => 'corporationbouncer:structure',
    'uses'       => 'StructureController@getStructures',
]);

Route::get('/{corporation_id}/structures/{structure_id}', [
    'as'         => 'corporation.view.structures.show',
    'middleware' => 'corporationbouncer:structure',
    'uses'       => 'StructureController@show',
]);

Route::get('/{corporation_id}/tracking', [
    'as'         => 'corporation.view.tracking',
    'middleware' => 'corporationbouncer:tracking',
    'uses'       => 'TrackingController@getTracking',
]);

Route::get('/{corporation_id}/tracking/member-tracking', [
    'as'         => 'corporation.view.tracking.data',
    'middleware' => 'corporationbouncer:tracking',
    'uses'       => 'TrackingController@getMemberTracking',
]);

Route::get('/{corporation_id}/journal', [
    'as'         => 'corporation.view.journal',
    'middleware' => 'corporationbouncer:journal',
    'uses'       => 'WalletController@journal',
]);

Route::get('/{corporation_id}/transactions', [
    'as'         => 'corporation.view.transactions',
    'middleware' => 'corporationbouncer:transaction',
    'uses'       => 'WalletController@transactions',
]);
