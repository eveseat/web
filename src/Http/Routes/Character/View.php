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
    'uses' => 'CharacterController@getCharacters',
]);

Route::get('/data/', [
    'as'   => 'character.list.data',
    'uses' => 'CharacterController@getCharactersData',
]);

Route::get('/{character_id}/delete/', [
    'as'         => 'character.delete',
    'middleware' => 'bouncer:superuser',
    'uses'       => 'CharacterController@deleteCharacter',
])->where('character_id', '[0-9]+');

Route::get('/{character_id}/assets/', [
    'as'         => 'character.view.assets',
    'middleware' => 'characterbouncer:assets',
    'uses'       => 'AssetsController@getAssetsView',
])->where('character_id', '[0-9]+');

Route::get('/{character_id}/assets/details/', [
    'as'         => 'character.view.assets.details',
    'middleware' => 'characterbouncer:assets',
    'uses'       => 'AssetsController@getCharacterAssets',
])->where('character_id', '[0-9]+');

Route::get('/{character_id}/bookmarks/', [
    'as'         => 'character.view.bookmarks',
    'middleware' => 'characterbouncer:bookmarks',
    'uses'       => 'BookmarksController@getBookmarks',
])->where('character_id', '[0-9]+');

Route::get('/{character_id}/calendar/', [
    'as'         => 'character.view.calendar',
    'middleware' => 'characterbouncer:calendar',
    'uses'       => 'CalendarController@getCalendar',
])->where('character_id', '[0-9]+');

Route::get('/{character_id}/contacts/', [
    'as'         => 'character.view.contacts',
    'middleware' => 'characterbouncer:contacts',
    'uses'       => 'ContactsController@getContacts',
])->where('character_id', '[0-9]+');

Route::get('/{character_id}/contracts/', [
    'as'         => 'character.view.contracts',
    'middleware' => 'characterbouncer:contracts',
    'uses'       => 'ContractsController@getContracts',
])->where('character_id', '[0-9]+');

Route::get('/{character_id}/contracts/data/', [
    'as'         => 'character.view.contracts.data',
    'middleware' => 'characterbouncer:contracts',
    'uses'       => 'ContractsController@getContractsData',
])->where('character_id', '[0-9]+');

Route::get('{character_id}/contracts/{contract_id}/items/', [
    'as'         => 'character.view.contracts.items',
    'middleware' => 'characterbouncer:contracts',
    'uses'       => 'ContractsController@getContractsItemsData',
])->where('character_id', '[0-9]+')
  ->where('contract_id', '[0-9]+');

Route::get('/{character_id}/fittings/', [
    'as'         => 'character.view.fittings',
    'middleware' => 'characterbouncer:fittings',
    'uses'       => 'FittingController@getFittings',
])->where('character_id', '[0-9]+');

Route::get('/{character_id}/fittings/{fitting_id}/items/', [
    'as'         => 'character.view.fittings.items',
    'middleware' => 'characterbouncer:fittings',
    'uses'       => 'FittingController@getFittingItems',
])->where('character_id', '[0-9]+')
  ->where('fitting_id', '[0-9]+');

Route::get('/{character_id}/industry/', [
    'as'         => 'character.view.industry',
    'middleware' => 'characterbouncer:industry',
    'uses'       => 'IndustryController@getIndustry',
])->where('character_id', '[0-9]+');

Route::get('/{character_id}/industry/data/', [
    'as'         => 'character.view.industry.data',
    'middleware' => 'characterbouncer:industry',
    'uses'       => 'IndustryController@getIndustryData',
])->where('character_id', '[0-9]+');

Route::group(['prefix' => 'intel'], function () {

    Route::get('/{character_id}/summary/', [
        'as'         => 'character.view.intel.summary',
        'middleware' => 'characterbouncer:intel',
        'uses'       => 'IntelController@getIntelSummary',
    ])->where('character_id', '[0-9]+');

    // Ajax Call Journal
    Route::get('{character_id}/summary/journal/data/', [
        'as'         => 'character.view.intel.summary.journal.data',
        'middleware' => 'characterbouncer:intel',
        'uses'       => 'IntelController@getTopWalletJournalData',
    ])->where('character_id', '[0-9]+');

    Route::get('{first_party_id}/{second_party_id}/summary/journal/details/', [
        'as'         => 'character.view.intel.summary.journal.details',
        'middleware' => 'characterbouncer:intel',
        'uses'       => 'IntelController@getJournalContent',
    ])->where('first_party_id', '[0-9]+')
      ->where('second_party_id', '[0-9]+');

    // Transactions
    Route::get('{character_id}/summary/transactions/data/', [
        'as'         => 'character.view.intel.summary.transactions.data',
        'middleware' => 'characterbouncer:intel',
        'uses'       => 'IntelController@getTopTransactionsData',
    ])->where('character_id', '[0-9]+');

    Route::get('{character_id}/summary/transactions/{client_id}/details/', [
        'as'         => 'character.view.intel.summary.transactions.details',
        'middleware' => 'characterbouncer:intel',
        'uses'       => 'IntelController@getTransactionContent',
    ])->where('character_id', '[0-9]+')
      ->where('client_id', '[0-9]+');

    // Mail
    Route::get('{character_id}/summary/mail/data/', [
        'as'         => 'character.view.intel.summary.mail.data',
        'middleware' => 'characterbouncer:intel',
        'uses'       => 'IntelController@getTopMailFromData',
    ])->where('character_id', '[0-9]+');

    Route::get('{character_id}/summary/mail/{from}/details/', [
        'as'         => 'character.view.intel.summary.mail.details',
        'middleware' => 'characterbouncer:intel',
        'uses'       => 'IntelController@getTopMailContent',
    ])->where('character_id', '[0-9]+')
      ->where('from', '[0-9]+');

    // Standings Comparison
    Route::get('{character_id}/comparison/', [
        'as'         => 'character.view.intel.standingscomparison',
        'middleware' => 'characterbouncer:intel',
        'uses'       => 'IntelController@getStandingsComparison',
    ])->where('character_id', '[0-9]+');

    Route::get('{character_id}/comparison/{profile_id}/data/', [
        'as'         => 'character.view.intel.standingscomparison.data',
        'middleware' => 'characterbouncer:intel',
        'uses'       => 'IntelController@getCompareStandingsWithProfileData',
    ])->where('character_id', '[0-9]+')
      ->where('profile_id', '[0-9]+');

    // Notes
    Route::get('{character_id}/notes/', [
        'as'         => 'character.view.intel.notes',
        'middleware' => 'characterbouncer:intel',
        'uses'       => 'IntelController@getNotes',
    ])->where('character_id', '[0-9]+');

    Route::get('{character_id}/notes/data/', [
        'as'         => 'character.view.intel.notes.data',
        'middleware' => 'characterbouncer:intel',
        'uses'       => 'IntelController@getNotesData',
    ])->where('character_id', '[0-9]+');

    Route::get('{character_id}/notes/{note_id}/single/data/', [
        'as'         => 'character.view.intel.notes.single.data',
        'middleware' => 'characterbouncer:intel',
        'uses'       => 'IntelController@getSingleNotesData',
    ])->where('character_id', '[0-9]+')
      ->where('note_id', '[0-9]+');

    Route::post('{character_id}/notes/new/', [
        'as'         => 'character.view.intel.notes.new',
        'middleware' => 'characterbouncer:intel',
        'uses'       => 'IntelController@postAddNew',
    ])->where('character_id', '[0-9]+');

    Route::post('{character_id}/notes/update/', [
        'as'         => 'character.view.intel.notes.update',
        'middleware' => 'characterbouncer:intel',
        'uses'       => 'IntelController@postUpdateNote',
    ])->where('character_id', '[0-9]+');

    Route::get('{character_id}/notes/{note_id}/delete/', [
        'as'         => 'character.view.intel.notes.delete',
        'middleware' => 'characterbouncer:intel',
        'uses'       => 'IntelController@getDeleteNote',
    ])->where('character_id', '[0-9]+')
      ->where('note_id', '[0-9]+');

});

Route::get('/{character_id}/journal/', [
    'as'         => 'character.view.journal',
    'middleware' => 'characterbouncer:journal',
    'uses'       => 'WalletController@getJournal',
])->where('character_id', '[0-9]+');

Route::get('/{character_id}/journal/data/', [
    'as'         => 'character.view.journal.data',
    'middleware' => 'characterbouncer:journal',
    'uses'       => 'WalletController@getJournalData',
])->where('character_id', '[0-9]+');

Route::get('/{character_id}/journal/graph/balance/', [
    'as'         => 'character.view.journal.graph.balance',
    'middleware' => 'characterbouncer:journal',
    'uses'       => 'WalletController@getJournalGraphBalance',
])->where('character_id', '[0-9]+');

Route::get('/{character_id}/killmails/', [
    'as'         => 'character.view.killmails',
    'middleware' => 'characterbouncer:killmails',
    'uses'       => 'KillmailController@getKillmails',
])->where('character_id', '[0-9]+');

Route::get('/{character_id}/killmails/data/', [
    'as'         => 'character.view.killmails.data',
    'middleware' => 'characterbouncer:killmails',
    'uses'       => 'KillmailController@getKillmailsData',
])->where('character_id', '[0-9]+');

Route::get('/mail/timeline/', [
    'as'   => 'character.view.mail.timeline',
    'uses' => 'MailController@getMailTimeline',
]);

Route::get('/mail/timeline/{message_id}/read/', [
    'as'   => 'character.view.mail.timeline.read',
    'uses' => 'MailController@getMailTimelineRead',
])->where('message_id', '[0-9]+');

Route::get('/{character_id}/mail/', [
    'as'         => 'character.view.mail',
    'middleware' => 'characterbouncer:mail',
    'uses'       => 'MailController@getMail',
])->where('character_id', '[0-9]+');

Route::get('/{character_id}/mail/data/', [
    'as'         => 'character.view.mail.data',
    'middleware' => 'characterbouncer:mail',
    'uses'       => 'MailController@getMailData',
])->where('character_id', '[0-9]+');

Route::get('/{character_id}/mail/{message_id}/read/', [
    'as'         => 'character.view.mail.read',
    'middleware' => 'characterbouncer:mail',
    'uses'       => 'MailController@getMailRead',
])->where('character_id', '[0-9]+')
  ->where('message_id', '[0-9]+');

Route::get('/{character_id}/market/', [
    'as'         => 'character.view.market',
    'middleware' => 'characterbouncer:market',
    'uses'       => 'MarketController@getMarket',
])->where('character_id', '[0-9]+');

Route::get('/{character_id}/market/data/', [
    'as'         => 'character.view.market.data',
    'middleware' => 'characterbouncer:market',
    'uses'       => 'MarketController@getMarketData',
])->where('character_id', '[0-9]+');

Route::get('/{character_id}/mining-ledger/', [
    'as'         => 'character.view.mining_ledger',
    'middleware' => 'characterbouncer:mining',
    'uses'       => 'MiningLedgerController@getLedger',
])->where('character_id', '[0-9]+');

Route::get('/{character_id}/mining-ledger/data/', [
    'as'         => 'character.view.mining_ledger.data',
    'middleware' => 'characterbouncer:mining',
    'uses'       => 'MiningLedgerController@getMiningLedger',
]);

Route::get('/{character_id}/mining-ledger/{date}/{system_id}/{type_id}/', [
    'as'         => 'character.view.detailed_mining_ledger',
    'middleware' => 'characterbouncer:mining',
    'uses'       => 'MiningLedgerController@getDetailedLedger',
])->where('character_id', '[0-9]+')
  ->where('system_id', '[0-9]+')
  ->where('type_id', '[0-9]+');

Route::get('/{character_id}/notifications/', [
    'as'         => 'character.view.notifications',
    'middleware' => 'characterbouncer:notifications',
    'uses'       => 'NotificationsController@getNotifications',
])->where('character_id', '[0-9]+');

Route::get('/{character_id}/pi/', [
    'as'         => 'character.view.pi',
    'middleware' => 'characterbouncer:pi',
    'uses'       => 'PiController@getPi',
])->where('character_id', '[0-9]+');

Route::get('/{character_id}/research/', [
    'as'         => 'character.view.research',
    'middleware' => 'characterbouncer:research',
    'uses'       => 'ResearchController@getResearch',
])->where('character_id', '[0-9]+');

Route::get('/{character_id}/sheet/', [
    'as'         => 'character.view.sheet',
    'middleware' => 'characterbouncer:sheet',
    'uses'       => 'SheetController@getSheet',
])->where('character_id', '[0-9]+');

Route::get('/{character_id}/skills/', [
    'as'         => 'character.view.skills',
    'middleware' => 'characterbouncer:skills',
    'uses'       => 'SkillsController@getSkills',
])->where('character_id', '[0-9]+');

Route::get('/{character_id}/skills/graph/level/', [
    'as'         => 'character.view.skills.graph.level',
    'middleware' => 'characterbouncer:sheet',
    'uses'       => 'SkillsController@getCharacterSkillsLevelChartData',
])->where('character_id', '[0-9]+');

Route::get('/{character_id}/skills/graph/coverage/', [
    'as'         => 'character.view.skills.graph.coverage',
    'middleware' => 'characterbouncer:sheet',
    'uses'       => 'SkillsController@getCharacterSkillsCoverageChartData',
])->where('character_id', '[0-9]+');

Route::get('/{character_id}/standings/', [
    'as'         => 'character.view.standings',
    'middleware' => 'characterbouncer:standings',
    'uses'       => 'StandingsController@getStandings',
])->where('character_id', '[0-9]+');

Route::get('/{character_id}/transactions/', [
    'as'         => 'character.view.transactions',
    'middleware' => 'characterbouncer:transactions',
    'uses'       => 'WalletController@getTransactions',
])->where('character_id', '[0-9]+');

Route::get('/{character_id}/transactions/data/', [
    'as'         => 'character.view.transactions.data',
    'middleware' => 'characterbouncer:transactions',
    'uses'       => 'WalletController@getTransactionsData',
])->where('character_id', '[0-9]+');
