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
    ->name('seatcore::character.list')
    ->uses('CharacterController@index');

Route::delete('/{character}')
    ->name('seatcore::character.destroy')
    ->uses('CharacterController@destroy')
    ->middleware('can:global.superuser');

Route::get('/{character}')
    ->name('seatcore::character.view.default')
    ->uses('CharacterController@show')
    ->where('character_id', '[0-9]+');

Route::get('/{character}/assets')
    ->name('seatcore::character.view.assets')
    ->uses('AssetsController@getAssetsView')
    ->middleware('can:character.asset,character');

Route::get('/{character}/assets/{item_id}/fitting')
    ->name('seatcore::character.view.assets.fitting')
    ->uses('AssetsController@getFitting')
    ->middleware('can:character.asset,character');

Route::get('/{character}/assets/{item_id}/container')
    ->name('seatcore::character.view.assets.container')
    ->uses('AssetsController@getContainer')
    ->middleware('can:character.asset,character');

Route::get('/{character}/calendar')
    ->name('seatcore::character.view.calendar')
    ->uses('CalendarController@index')
    ->middleware('can:character.calendar,character');

Route::get('/{character}/contacts')
    ->name('seatcore::character.view.contacts')
    ->uses('ContactsController@index')
    ->middleware('can:character.contact,character');

Route::get('/{character}/contracts')
    ->name('seatcore::character.view.contracts')
    ->uses('ContractsController@index')
    ->middleware('can:character.contract,character');

Route::post('/{character}/contracts/export')
    ->uses('ContractsController@index')
    ->middleware('can:character.contract,character');

Route::get('/{character}/contracts/{contract_id}')
    ->name('seatcore::character.view.contracts.items')
    ->uses('ContractsController@show')
    ->middleware('can:character.contract,character');

Route::get('/{character}/fittings')
    ->name('seatcore::character.view.fittings')
    ->uses('FittingController@index')
    ->middleware('can:character.fitting,character');

Route::get('/{character}/fittings/{fitting_id}')
    ->name('seatcore::character.view.fittings.items')
    ->uses('FittingController@show')
    ->middleware('can:character.fitting,character');

Route::get('/{character}/blueprint')
    ->name('seatcore::character.view.blueprint')
    ->uses('BlueprintController@index')
    ->middleware('can:character.blueprint,character');

Route::get('/{character}/industry')
    ->name('seatcore::character.view.industry')
    ->uses('IndustryController@index')
    ->middleware('can:character.industry,character');

Route::get('/{character}/loyalty-points')
    ->name('character.view.loyalty_points')
    ->uses('LoyaltyPointsController@index')
    ->middleware('can:character.loyalty_points,character');

Route::group(['prefix' => '{character}/intel'], function () {

    Route::get('summary')
        ->name('seatcore::character.view.intel.summary')
        ->uses('IntelController@getIntelSummary')
        ->middleware('can:character.intel,character');

    // Ajax Call Journal
    Route::get('summary/journal/data')
        ->name('seatcore::character.view.intel.summary.journal.data')
        ->uses('IntelController@getTopWalletJournalData')
        ->middleware('can:character.intel,character');

    Route::get('summary/journal/details/{first_party_id}/{second_party_id}/{ref_type}')
        ->name('seatcore::character.view.intel.summary.journal.details')
        ->uses('IntelController@getJournalContent')
        ->middleware('can:character.intel,character');

    // Transactions
    Route::get('summary/transactions/data')
        ->name('seatcore::character.view.intel.summary.transactions.data')
        ->uses('IntelController@getTopTransactionsData')
        ->middleware('can:character.intel,character');

    Route::get('summary/transactions/details/{client_id}')
        ->name('seatcore::character.view.intel.summary.transactions.details')
        ->uses('IntelController@getTransactionContent')
        ->middleware('can:character.intel,character');

    // Mail
    Route::get('summary/mail/data')
        ->name('seatcore::character.view.intel.summary.mail.data')
        ->uses('IntelController@getTopMailFromData')
        ->middleware('can:character.intel,character');

    Route::get('summary/mail/details/{from}')
        ->name('seatcore::character.view.intel.summary.mail.details')
        ->uses('IntelController@getTopMailContent')
        ->middleware('can:character.intel,character');

    // Standings Comparison
    Route::get('comparison')
        ->name('seatcore::character.view.intel.standingscomparison')
        ->uses('IntelController@getStandingsComparison')
        ->middleware('can:character.intel,character');

    Route::get('comparison/data/{profile_id}')
        ->name('seatcore::character.view.intel.standingscomparison.data')
        ->uses('IntelController@getCompareStandingsWithProfileData')
        ->middleware('can:character.intel,character');

    // Notes
    Route::get('notes')
        ->name('seatcore::character.view.intel.notes')
        ->uses('IntelController@notes')
        ->middleware('can:character.intel,character');
});

Route::get('/{character}/journal')
    ->name('seatcore::character.view.journal')
    ->uses('WalletController@journal')
    ->middleware('can:character.journal,character');

Route::post('/{character}/journal/export')
    ->uses('WalletController@journal')
    ->middleware('can:character.journal,character');

Route::get('/view/journal/graph/balance/{character}')
    ->name('seatcore::character.view.journal.graph.balance')
    ->uses('WalletController@getJournalGraphBalance')
    ->middleware('can:character.journal,character');

Route::get('/{character}/killmails')
    ->name('seatcore::character.view.killmails')
    ->uses('KillmailController@index')
    ->middleware('can:character.killmail,character');

Route::get('/{character}/killmails/{killmail}')
    ->name('seatcore::character.view.killmail')
    ->uses('KillmailController@show')
    ->middleware('can:character.killmail,character');

Route::get('/view/mail/timeline')
    ->name('seatcore::character.view.mail.timeline')
    ->uses('MailController@getMailTimeline');

Route::get('/view/mail/timeline/read/{message_id}')
    ->name('seatcore::character.view.mail.timeline.read')
    ->uses('MailController@getMailTimelineRead');

Route::get('/{character}/mail')
    ->name('seatcore::character.view.mail')
    ->uses('MailController@index')
    ->middleware('can:character.mail,character');

Route::get('/{character}/mail/{message_id}')
    ->name('seatcore::character.view.mail.read')
    ->uses('MailController@show')
    ->middleware('can:character.mail,character');

Route::get('/{character}/markets')
    ->name('seatcore::character.view.market')
    ->uses('MarketController@index')
    ->middleware('can:character.market,character');

Route::post('/{character}/markets/export')
    ->uses('MarketController@index')
    ->middleware('can:character.market,character');

Route::get('/{character}/mining-ledger')
    ->name('seatcore::character.view.mining_ledger')
    ->uses('MiningLedgerController@index')
    ->middleware('can:character.mining,character');

Route::get('/{character}/mining-ledger/details')
    ->name('seatcore::character.view.mining_ledger.details')
    ->uses('MiningLedgerController@show')
    ->middleware('can:character.mining,character');

Route::get('/{character}/notifications')
    ->name('seatcore::character.view.notifications')
    ->uses('NotificationsController@index')
    ->middleware('can:character.notification,character');

Route::get('/{character}/pi')
    ->name('seatcore::character.view.pi')
    ->uses('PiController@getPi')
    ->middleware('can:character.planetary,character');

Route::get('/{character}/research')
    ->name('seatcore::character.view.research')
    ->uses('ResearchController@index')
    ->middleware('can:character.research,character');

Route::get('/{character}/sheet')
    ->name('seatcore::character.view.sheet')
    ->uses('SheetController@show')
    ->middleware('can:character.sheet,character');

Route::get('/{character}/ship')
    ->name('seatcore::character.view.ship')
    ->uses('CharacterController@getShip')
    ->middleware('can:character.asset,character');

Route::get('/{character}/skills')
    ->name('seatcore::character.view.skills')
    ->uses('SkillsController@getSkills')
    ->middleware('can:character.skill,character');

Route::get('/{character}/skills/export')
    ->name('seatcore::character.export.skills')
    ->uses('SkillsController@export')
    ->middleware('can:character.skill,character');

Route::get('/view/skills/graph/level/{character}')
    ->name('seatcore::character.view.skills.graph.level')
    ->uses('SkillsController@getCharacterSkillsLevelChartData')
    ->middleware('can:character.sheet,character');

Route::get('/view/skills/graph/coverage/{character}')
    ->name('seatcore::character.view.skills.graph.coverage')
    ->uses('SkillsController@getCharacterSkillsCoverageChartData')
    ->middleware('can:character.sheet,character');

Route::get('/{character}/standings')
    ->name('seatcore::character.view.standings')
    ->uses('StandingsController@index')
    ->middleware('can:character.standing,character');

Route::get('/{character}/transactions')
    ->name('seatcore::character.view.transactions')
    ->uses('WalletController@transactions')
    ->middleware('can:character.transaction,character');

Route::post('/{character}/transactions/export')
    ->uses('WalletController@transactions')
    ->middleware('can:character.transaction,character');
