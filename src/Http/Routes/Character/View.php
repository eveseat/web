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
    'as'   => 'character.list',
    'uses' => 'CharacterController@getCharacters',
]);

Route::get('/list/data', [
    'as'   => 'character.list.data',
    'uses' => 'CharacterController@getCharactersData',
]);

Route::get('/view/assets/{character_id}', [
    'as'         => 'character.view.assets',
    'middleware' => 'characterbouncer:assets',
    'uses'       => 'AssetsController@getAssets',
]);

Route::get('/view/assets/contents/{character_id}/{item_id}', [
    'as'         => 'character.view.assets.contents',
    'middleware' => 'characterbouncer:assets',
    'uses'       => 'AssetsController@getAssetsContents',
]);

Route::get('/view/bookmarks/{character_id}', [
    'as'         => 'character.view.bookmarks',
    'middleware' => 'characterbouncer:bookmarks',
    'uses'       => 'BookmarksController@getBookmarks',
]);

Route::get('/view/calendar/{character_id}', [
    'as'         => 'character.view.calendar',
    'middleware' => 'characterbouncer:calendar',
    'uses'       => 'CalendarController@getCalendar',
]);

Route::get('/view/channels/{character_id}', [
    'as'         => 'character.view.channels',
    'middleware' => 'characterbouncer:channels',
    'uses'       => 'ChannelsController@getChannels',
]);

Route::get('/view/contacts/{character_id}', [
    'as'         => 'character.view.contacts',
    'middleware' => 'characterbouncer:contacts',
    'uses'       => 'ContactsController@getContacts',
]);

Route::get('/view/contracts/{character_id}', [
    'as'         => 'character.view.contracts',
    'middleware' => 'characterbouncer:contracts',
    'uses'       => 'ContractsController@getContracts',
]);

Route::get('/view/contracts/data/{character_id}', [
    'as'         => 'character.view.contracts.data',
    'middleware' => 'characterbouncer:contracts',
    'uses'       => 'ContractsController@getContractsData',
]);

Route::get('/view/contracts/items/{character_id}/{contract_id}', [
    'as'         => 'character.view.contracts.items',
    'middleware' => 'characterbouncer:contracts',
    'uses'       => 'ContractsController@getContractsItemsData',
]);

Route::get('/view/industry/{character_id}', [
    'as'         => 'character.view.industry',
    'middleware' => 'characterbouncer:industry',
    'uses'       => 'IndustryController@getIndustry',
]);

Route::get('/view/industry/data/{character_id}', [
    'as'         => 'character.view.industry.data',
    'middleware' => 'characterbouncer:industry',
    'uses'       => 'IndustryController@getIndustryData',
]);

Route::group(['prefix' => 'view/intel'], function () {

    Route::get('summary/{character_id}', [
        'as'         => 'character.view.intel.summary',
        'middleware' => 'characterbouncer:intel',
        'uses'       => 'IntelController@getIntelSummary',
    ]);

    // Ajax Call Journal
    Route::get('summary/journal/data/{character_id}', [
        'as'         => 'character.view.intel.summary.journal.data',
        'middleware' => 'characterbouncer:intel',
        'uses'       => 'IntelController@getTopWalletJournalData',
    ]);

    // Transactions
    Route::get('summary/transactions/data/{character_id}', [
        'as'         => 'character.view.intel.summary.transactions.data',
        'middleware' => 'characterbouncer:intel',
        'uses'       => 'IntelController@getTopTransactionsData',
    ]);

    // Mail
    Route::get('summary/mail/data/{character_id}', [
        'as'         => 'character.view.intel.summary.mail.data',
        'middleware' => 'characterbouncer:intel',
        'uses'       => 'IntelController@getTopMailFromData',
    ]);

    // Standings Comparison
    Route::get('comparison/{character_id}', [
        'as'         => 'character.view.intel.standingscomparison',
        'middleware' => 'characterbouncer:intel',
        'uses'       => 'IntelController@getStandingsComparison',
    ]);

    Route::get('comparison/data/{character_id}/{profile_id}', [
        'as'         => 'character.view.intel.standingscomparison.data',
        'middleware' => 'characterbouncer:intel',
        'uses'       => 'IntelController@getCompareStandingsWithProfileData',
    ]);

    // Notes
    Route::get('notes/{character_id}', [
        'as'         => 'character.view.intel.notes',
        'middleware' => 'characterbouncer:intel',
        'uses'       => 'IntelController@getNotes',
    ]);

    Route::get('notes/data/{character_id}', [
        'as'         => 'character.view.intel.notes.data',
        'middleware' => 'characterbouncer:intel',
        'uses'       => 'IntelController@getNotesData',
    ]);

    Route::get('notes/single/data/{character_id}/{note_id}', [
        'as'         => 'character.view.intel.notes.single.data',
        'middleware' => 'characterbouncer:intel',
        'uses'       => 'IntelController@getSingleNotesData',
    ]);

    Route::post('notes/new/{character_id}', [
        'as'         => 'character.view.intel.notes.new',
        'middleware' => 'characterbouncer:intel',
        'uses'       => 'IntelController@postAddNew',
    ]);

    Route::post('notes/update/{character_id}', [
        'as'         => 'character.view.intel.notes.update',
        'middleware' => 'characterbouncer:intel',
        'uses'       => 'IntelController@postUpdateNote',
    ]);

    Route::get('notes/delete/{character_id}/{note_id}', [
        'as'         => 'character.view.intel.notes.delete',
        'middleware' => 'characterbouncer:intel',
        'uses'       => 'IntelController@getDeleteNote',
    ]);

});

Route::get('/view/journal/{character_id}', [
    'as'         => 'character.view.journal',
    'middleware' => 'characterbouncer:journal',
    'uses'       => 'WalletController@getJournal',
]);

Route::get('/view/journal/data/{character_id}', [
    'as'         => 'character.view.journal.data',
    'middleware' => 'characterbouncer:journal',
    'uses'       => 'WalletController@getJournalData',
]);

Route::get('/view/journal/graph/balance/{character_id}', [
    'as'         => 'character.view.journal.graph.balance',
    'middleware' => 'characterbouncer:journal',
    'uses'       => 'WalletController@getJournalGraphBalance',
]);

Route::get('/view/killmails/{character_id}', [
    'as'         => 'character.view.killmails',
    'middleware' => 'characterbouncer:killmails',
    'uses'       => 'KillmailController@getKillmails',
]);

Route::get('/view/killmails/data/{character_id}', [
    'as'         => 'character.view.killmails.data',
    'middleware' => 'characterbouncer:killmails',
    'uses'       => 'KillmailController@getKillmailsData',
]);

Route::get('/view/mail/timeline', [
    'as'   => 'character.view.mail.timeline',
    'uses' => 'MailController@getMailTimeline',
]);

Route::get('/view/mail/timeline/read/{message_id}', [
    'as'   => 'character.view.mail.timeline.read',
    'uses' => 'MailController@getMailTimelineRead',
]);

Route::get('/view/mail/{character_id}', [
    'as'         => 'character.view.mail',
    'middleware' => 'characterbouncer:mail',
    'uses'       => 'MailController@getMail',
]);

Route::get('/view/mail/data/{character_id}', [
    'as'         => 'character.view.mail.data',
    'middleware' => 'characterbouncer:mail',
    'uses'       => 'MailController@getMailData',
]);

Route::get('/view/mail/{character_id}/read/{message_id}', [
    'as'         => 'character.view.mail.read',
    'middleware' => 'characterbouncer:mail',
    'uses'       => 'MailController@getMailRead',
]);

Route::get('/view/market/{character_id}', [
    'as'         => 'character.view.market',
    'middleware' => 'characterbouncer:market',
    'uses'       => 'MarketController@getMarket',
]);

Route::get('/view/market/data/{character_id}', [
    'as'         => 'character.view.market.data',
    'middleware' => 'characterbouncer:market',
    'uses'       => 'MarketController@getMarketData',
]);

Route::get('/view/notifications/{character_id}', [
    'as'         => 'character.view.notifications',
    'middleware' => 'characterbouncer:notifications',
    'uses'       => 'NotificationsController@getNotifications',
]);

Route::get('/view/pi/{character_id}', [
    'as'         => 'character.view.pi',
    'middleware' => 'characterbouncer:pi',
    'uses'       => 'PiController@getPi',
]);

Route::get('/view/research/{character_id}', [
    'as'         => 'character.view.research',
    'middleware' => 'characterbouncer:research',
    'uses'       => 'ResearchController@getResearch',
]);

Route::get('/view/sheet/{character_id}', [
    'as'         => 'character.view.sheet',
    'middleware' => 'characterbouncer:sheet',
    'uses'       => 'SheetController@getSheet',
]);

Route::get('/view/skills/{character_id}', [
    'as'         => 'character.view.skills',
    'middleware' => 'characterbouncer:skills',
    'uses'       => 'SkillsController@getSkills',
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

Route::get('/view/standings/{character_id}', [
    'as'         => 'character.view.standings',
    'middleware' => 'characterbouncer:standings',
    'uses'       => 'StandingsController@getStandings',
]);

Route::get('/view/transactions/{character_id}', [
    'as'         => 'character.view.transactions',
    'middleware' => 'characterbouncer:transactions',
    'uses'       => 'WalletController@getTransactions',
]);

Route::get('/view/transactions/data/{character_id}', [
    'as'         => 'character.view.transactions.data',
    'middleware' => 'characterbouncer:transactions',
    'uses'       => 'WalletController@getTransactionsData',
]);
