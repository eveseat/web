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
    'as'   => 'character.list',
    'uses' => 'CharacterController@index',
]);

Route::get('/delete/{character_id}', [
    'as'         => 'character.delete',
    'middleware' => 'bouncer:global.superuser',
    'uses'       => 'CharacterController@deleteCharacter',
]);

Route::get('/{character_id}', [
    'as'   => 'character.view.default',
    'uses' => 'CharacterController@show',
])->where('character_id', '[0-9]+');

Route::get('/{character_id}/assets', [
    'as'         => 'character.view.assets',
    'middleware' => 'characterbouncer:asset',
    'uses'       => 'AssetsController@getAssetsView',
]);

Route::get('/{character_id}/assets/details', [
    'as'         => 'character.view.assets.details',
    'middleware' => 'characterbouncer:asset',
    'uses'       => 'AssetsController@getCharacterAssets',
]);

Route::get('/{character_id}/bookmarks', [
    'as'         => 'character.view.bookmarks',
    'middleware' => 'characterbouncer:bookmark',
    'uses'       => 'BookmarksController@index',
]);

Route::get('/{character_id}/calendar', [
    'as'         => 'character.view.calendar',
    'middleware' => 'characterbouncer:calendar',
    'uses'       => 'CalendarController@index',
]);

Route::get('/{character_id}/contacts', [
    'as'         => 'character.view.contacts',
    'middleware' => 'characterbouncer:contact',
    'uses'       => 'ContactsController@index',
]);

Route::get('/{character_id}/contracts', [
    'as'         => 'character.view.contracts',
    'middleware' => 'characterbouncer:contract',
    'uses'       => 'ContractsController@index',
]);

Route::get('/{character_id}/contracts/{contract_id}', [
    'as'         => 'character.view.contracts.items',
    'middleware' => 'characterbouncer:contract',
    'uses'       => 'ContractsController@show',
]);

Route::get('/{character_id}/fittings', [
    'as'         => 'character.view.fittings',
    'middleware' => 'characterbouncer:fitting',
    'uses'       => 'FittingController@index',
]);

Route::get('/{character_id}/fittings/{fitting_id}', [
    'as'         => 'character.view.fittings.items',
    'middleware' => 'characterbouncer:fitting',
    'uses'       => 'FittingController@show',
]);

Route::get('/{character_id}/blueprint', [
    'as'         => 'character.view.blueprint',
    'middleware' => 'characterbouncer:blueprint',
    'uses'       => 'BlueprintController@index',
]);

Route::get('/{character_id}/industry', [
    'as'         => 'character.view.industry',
    'middleware' => 'characterbouncer:industry',
    'uses'       => 'IndustryController@index',
]);

Route::group(['prefix' => '{character_id}/intel'], function () {

    Route::get('summary', [
        'as'         => 'character.view.intel.summary',
        'middleware' => 'characterbouncer:intel',
        'uses'       => 'IntelController@getIntelSummary',
    ]);

    // Ajax Call Journal
    Route::get('summary/journal/data', [
        'as'         => 'character.view.intel.summary.journal.data',
        'middleware' => 'characterbouncer:intel',
        'uses'       => 'IntelController@getTopWalletJournalData',
    ]);

    Route::get('summary/journal/details/{first_party_id}/{second_party_id}/{ref_type}', [
        'as'         => 'character.view.intel.summary.journal.details',
        'middleware' => 'characterbouncer:intel',
        'uses'       => 'IntelController@getJournalContent',
    ]);

    // Transactions
    Route::get('summary/transactions/data', [
        'as'         => 'character.view.intel.summary.transactions.data',
        'middleware' => 'characterbouncer:intel',
        'uses'       => 'IntelController@getTopTransactionsData',
    ]);

    Route::get('summary/transactions/details/{client_id}', [
        'as'         => 'character.view.intel.summary.transactions.details',
        'middleware' => 'characterbouncer:intel',
        'uses'       => 'IntelController@getTransactionContent',
    ]);

    // Mail
    Route::get('summary/mail/data', [
        'as'         => 'character.view.intel.summary.mail.data',
        'middleware' => 'characterbouncer:intel',
        'uses'       => 'IntelController@getTopMailFromData',
    ]);

    Route::get('summary/mail/details/{from}', [
        'as'         => 'character.view.intel.summary.mail.details',
        'middleware' => 'characterbouncer:intel',
        'uses'       => 'IntelController@getTopMailContent',
    ]);

    // Standings Comparison
    Route::get('comparison', [
        'as'         => 'character.view.intel.standingscomparison',
        'middleware' => 'characterbouncer:intel',
        'uses'       => 'IntelController@getStandingsComparison',
    ]);

    Route::get('comparison/data/{profile_id}', [
        'as'         => 'character.view.intel.standingscomparison.data',
        'middleware' => 'characterbouncer:intel',
        'uses'       => 'IntelController@getCompareStandingsWithProfileData',
    ]);

    // Notes
    Route::get('notes', [
        'as'         => 'character.view.intel.notes',
        'middleware' => 'characterbouncer:intel',
        'uses'       => 'IntelController@notes',
    ]);
});

Route::get('/{character_id}/journal', [
    'as'         => 'character.view.journal',
    'middleware' => 'characterbouncer:journal',
    'uses'       => 'WalletController@journal',
]);

Route::get('/view/journal/graph/balance/{character_id}', [
    'as'         => 'character.view.journal.graph.balance',
    'middleware' => 'characterbouncer:journal',
    'uses'       => 'WalletController@getJournalGraphBalance',
]);

Route::get('/{character_id}/killmails', [
    'as'         => 'character.view.killmails',
    'middleware' => 'characterbouncer:killmail',
    'uses'       => 'KillmailController@index',
]);

Route::get('/view/mail/timeline', [
    'as'   => 'character.view.mail.timeline',
    'uses' => 'MailController@getMailTimeline',
]);

Route::get('/view/mail/timeline/read/{message_id}', [
    'as'   => 'character.view.mail.timeline.read',
    'uses' => 'MailController@getMailTimelineRead',
]);

Route::get('/{character_id}/mail', [
    'as'         => 'character.view.mail',
    'middleware' => 'characterbouncer:mail',
    'uses'       => 'MailController@index',
]);

Route::get('/{character_id}/mail/{message_id}', [
    'as'         => 'character.view.mail.read',
    'middleware' => 'characterbouncer:mail',
    'uses'       => 'MailController@show',
]);

Route::get('/{character_id}/markets', [
    'as'         => 'character.view.market',
    'middleware' => 'characterbouncer:market',
    'uses'       => 'MarketController@index',
]);

Route::get('/{character_id}/mining-ledger', [
    'as'         => 'character.view.mining_ledger',
    'middleware' => 'characterbouncer:mining',
    'uses'       => 'MiningLedgerController@index',
]);

Route::get('/{character_id}/mining-ledger/details', [
    'as'         => 'character.view.mining_ledger.details',
    'middleware' => 'characterbouncer:mining',
    'uses'       => 'MiningLedgerController@show',
]);

Route::get('/{character_id}/notifications', [
    'as'         => 'character.view.notifications',
    'middleware' => 'characterbouncer:notifications',
    'uses'       => 'NotificationsController@index',
]);

Route::get('/{character_id}/pi', [
    'as'         => 'character.view.pi',
    'middleware' => 'characterbouncer:pi',
    'uses'       => 'PiController@getPi',
]);

Route::get('/{character_id}/research', [
    'as'         => 'character.view.research',
    'middleware' => 'characterbouncer:research',
    'uses'       => 'ResearchController@index',
]);

Route::get('/{character_id}/sheet', [
    'as'         => 'character.view.sheet',
    'middleware' => 'characterbouncer:sheet',
    'uses'       => 'SheetController@show',
]);

Route::get('/{character_id}/skills', [
    'as'         => 'character.view.skills',
    'middleware' => 'characterbouncer:skill',
    'uses'       => 'SkillsController@getSkills',
]);

Route::get('/{character_id}/skills/export', [
    'as'         => 'character.export.skills',
    'middleware' => 'characterbouncer:skills',
    'uses'       => 'SkillsController@export',
]);

Route::get('/view/skills/graph/level/{character_id}', [
    'as'         => 'character.view.skills.graph.level',
    'middleware' => 'characterbouncer:sheet',
    'uses'       => 'SkillsController@getCharacterSkillsLevelChartData',
]);

Route::get('/view/skills/graph/coverage/{character_id}', [
    'as'         => 'character.view.skills.graph.coverage',
    'middleware' => 'characterbouncer:sheet',
    'uses'       => 'SkillsController@getCharacterSkillsCoverageChartData',
]);

Route::get('/{character_id}/standings', [
    'as'         => 'character.view.standings',
    'middleware' => 'characterbouncer:standing',
    'uses'       => 'StandingsController@index',
]);

Route::get('/{character_id}/transactions', [
    'as'         => 'character.view.transactions',
    'middleware' => 'characterbouncer:transaction',
    'uses'       => 'WalletController@transactions',
]);
