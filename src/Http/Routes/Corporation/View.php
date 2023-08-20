<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to present Leon Jacobs
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

Route::get('/')
    ->name('seatcore::corporation.list')
    ->uses('CorporationsController@index');

Route::get('/{corporation}')
    ->name('seatcore::corporation.view.default')
    ->uses('CorporationsController@show');

Route::delete('/{corporation}')
    ->name('seatcore::corporation.destroy')
    ->uses('CorporationsController@destroy');

Route::get('/{corporation}/assets')
    ->name('seatcore::corporation.view.assets')
    ->uses('AssetsController@getAssets')
    ->middleware('can:corporation.asset,corporation');

Route::get('/{corporation}/assets/{item_id}/fitting')
    ->name('seatcore::corporation.view.assets.fitting')
    ->uses('AssetsController@getFitting')
    ->middleware('can:corporation.asset,corporation');

Route::get('/{corporation}/assets/{item_id}/container')
    ->name('seatcore::corporation.view.assets.container')
    ->uses('AssetsController@getContainer')
    ->middleware('can:corporation.asset,corporation');

Route::get('/{corporation}/contacts')
    ->name('seatcore::corporation.view.contacts')
    ->uses('ContactsController@index')
    ->middleware('can:corporation.contact,corporation');

Route::get('/{corporation}/contracts')
    ->name('seatcore::corporation.view.contracts')
    ->uses('ContractsController@index')
    ->middleware('can:corporation.contract,corporation');

Route::post('/{corporation}/contracts/export')
    ->uses('ContractsController@index')
    ->middleware('can:corporation.contract,corporation');

Route::get('/{corporation}/contracts/{contract_id}')
    ->name('seatcore::corporation.view.contracts.items')
    ->uses('ContractsController@show')
    ->middleware('can:corporation.contract,corporation');

Route::get('/{corporation}/industry')
    ->name('seatcore::corporation.view.industry')
    ->uses('IndustryController@index')
    ->middleware('can:corporation.industry,corporation');

Route::get('/{corporation}/blueprint')
    ->name('seatcore::corporation.view.blueprint')
    ->uses('BlueprintController@index')
    ->middleware('can:corporation.blueprint,corporation');

Route::get('/{corporation}/killmails')
    ->name('seatcore::corporation.view.killmails')
    ->uses('KillmailsController@index')
    ->middleware('can:corporation.killmail,corporation');

Route::get('/{corporation}/killmails/{killmail}')
    ->name('seatcore::corporation.view.killmail')
    ->uses('KillmailsController@show')
    ->middleware('can:corporation.killmail,corporation');

Route::get('/{corporation}/markets')
    ->name('seatcore::corporation.view.market')
    ->uses('MarketController@index')
    ->middleware('can:corporation.market,corporation');

Route::post('/{corporation}/markets/export')
    ->uses('MarketController@index')
    ->middleware('can:corporation.market,corporation');

Route::get('/{corporation}/mining-ledger/{year?}/{month?}')
    ->name('seatcore::corporation.view.mining_ledger')
    ->uses('MiningLedgerController@index')
    ->middleware('can:corporation.mining,corporation');

Route::get('/{corporation}/customs-offices')
    ->name('seatcore::corporation.view.customs-offices')
    ->uses('CustomOfficeController@index')
    ->middleware('can:corporation.customs_office,corporation');

Route::get('/{corporation}/extractions')
    ->name('seatcore::corporation.view.extractions')
    ->uses('ExtractionController@getExtractions')
    ->middleware('can:corporation.extraction,corporation');

Route::group(['prefix' => '{corporation}/security', 'middleware' => 'can:corporation.security,corporation'], function () {
    Route::get('roles')
        ->name('seatcore::corporation.view.security.roles')
        ->uses('SecurityController@getRoles');

    Route::get('titles')
        ->name('seatcore::corporation.view.security.titles')
        ->uses('SecurityController@getTitles');

    Route::get('logs')
        ->name('seatcore::corporation.view.security.log')
        ->uses('SecurityController@getLogs');
});

Route::group(['prefix' => '{corporation}/ledger', 'middleware' => 'can:corporation.ledger,corporation'], function () {
    Route::get('summary')
        ->name('seatcore::corporation.view.ledger.summary')
        ->uses('LedgerController@getWalletSummary');

    Route::get('bounty-prizes/{year?}/{month?}')
        ->name('seatcore::corporation.view.ledger.bounty_prizes')
        ->uses('LedgerController@getBountyPrizesByMonth');

    Route::get('planetary-interaction/{year?}/{month?}')
        ->name('seatcore::corporation.view.ledger.planetary_interaction')
        ->uses('LedgerController@getPlanetaryInteractionByMonth');

    Route::get('offices-rentals/{year?}/{month?}')
        ->name('seatcore::corporation.view.ledger.offices_rentals')
        ->uses('LedgerController@getOfficesRentalsByMonth');

    Route::get('industry-facility/{year?}/{month?}')
        ->name('seatcore::corporation.view.ledger.industry_facility')
        ->uses('LedgerController@getIndustryFacilityByMonth');

    Route::get('reprocessing/{year?}/{month?}')
        ->name('seatcore::corporation.view.ledger.reprocessing')
        ->uses('LedgerController@getReprocessingByMonth');

    Route::get('jump-clones/{year?}/{month?}')
        ->name('seatcore::corporation.view.ledger.jump_clones')
        ->uses('LedgerController@getJumpClonesByMonth');

    Route::get('jump-bridges/{year?}/{month?}')
        ->name('seatcore::corporation.view.ledger.jump_bridges')
        ->uses('LedgerController@getJumpBridgesByMonth');
});

Route::get('/{corporation}/summary')
    ->name('seatcore::corporation.view.summary')
    ->uses('SummaryController@show')
    ->middleware('can:corporation.summary,corporation');

Route::get('/{corporation}/standings')
    ->name('seatcore::corporation.view.standings')
    ->uses('StandingsController@index')
    ->middleware('can:corporation.standing,corporation');

Route::get('/{corporation}/starbases')
    ->name('seatcore::corporation.view.starbases')
    ->uses('StarbaseController@getStarbases')
    ->middleware('can:corporation.starbase,corporation');

Route::post('/{corporation}/starbase/modules')
    ->name('seatcore::corporation.view.starbase.modules')
    ->uses('StarbaseController@postStarbaseModules')
    ->middleware('can:corporation.starbase,corporation');

Route::get('/{corporation}/structures')
    ->name('seatcore::corporation.view.structures')
    ->uses('StructureController@getStructures')
    ->middleware('can:corporation.structure,corporation');

Route::get('/{corporation}/structures/{structure_id}')
    ->name('seatcore::corporation.view.structures.show')
    ->uses('StructureController@show')
    ->middleware('can:corporation.structure,corporation');

Route::get('/{corporation}/tracking')
    ->name('seatcore::corporation.view.tracking')
    ->uses('TrackingController@getTracking')
    ->middleware('can:corporation.tracking,corporation');

Route::post('/{corporation}/tracking/export')
    ->uses('TrackingController@getTracking')
    ->middleware('can:corporation.tracking,corporation');

Route::get('/{corporation}/journal')
    ->name('seatcore::corporation.view.journal')
    ->uses('WalletController@journal')
    ->middleware('can:corporation.journal,corporation');

Route::post('/{corporation}/journal/export')
    ->uses('WalletController@journal')
    ->middleware('can:corporation.journal,corporation');

Route::get('/{corporation}/transactions')
    ->name('seatcore::corporation.view.transactions')
    ->uses('WalletController@transactions')
    ->middleware('can:corporation.transaction,corporation');

Route::post('/{corporation}/transactions/export')
    ->uses('WalletController@transactions')
    ->middleware('can:corporation.transaction,corporation');
