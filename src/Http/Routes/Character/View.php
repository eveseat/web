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
    'as'   => 'character.list',
    'uses' => 'ViewController@getCharacters'
]);

Route::get('/view/assets/{character_id}', [
    'as'         => 'character.view.assets',
    'middleware' => 'characterbouncer:assets',
    'uses'       => 'ViewController@getAssets'
]);

Route::get('/view/bookmarks/{character_id}', [
    'as'         => 'character.view.bookmarks',
    'middleware' => 'characterbouncer:bookmarks',
    'uses'       => 'ViewController@getBookmarks'
]);

Route::get('/view/calendar/{character_id}', [
    'as'         => 'character.view.calendar',
    'middleware' => 'characterbouncer:calendar',
    'uses'       => 'ViewController@getCalendar'
]);

Route::get('/view/channels/{character_id}', [
    'as'         => 'character.view.channels',
    'middleware' => 'characterbouncer:channels',
    'uses'       => 'ViewController@getChannels'
]);

Route::get('/view/contacts/{character_id}', [
    'as'         => 'character.view.contacts',
    'middleware' => 'characterbouncer:contacts',
    'uses'       => 'ViewController@getContacts'
]);

Route::get('/view/contracts/{character_id}', [
    'as'         => 'character.view.contracts',
    'middleware' => 'characterbouncer:contracts',
    'uses'       => 'ViewController@getContracts'
]);

Route::get('/view/industry/{character_id}', [
    'as'         => 'character.view.industry',
    'middleware' => 'characterbouncer:industry',
    'uses'       => 'ViewController@getIndustry'
]);

Route::get('/view/journal/{character_id}', [
    'as'         => 'character.view.journal',
    'middleware' => 'characterbouncer:journal',
    'uses'       => 'ViewController@getJournal'
]);

Route::get('/view/killmails/{character_id}', [
    'as'         => 'character.view.killmails',
    'middleware' => 'characterbouncer:killmails',
    'uses'       => 'ViewController@getKillmails'
]);

Route::get('/view/mail/timeline', [
    'as'   => 'character.view.mail.timeline',
    'uses' => 'ViewController@getMailTimeline'
]);

Route::get('/view/mail/timeline/read/{message_id}', [
    'as'   => 'character.view.mail.timeline.read',
    'uses' => 'ViewController@getMailTimelineRead'
]);

Route::get('/view/mail/{character_id}', [
    'as'         => 'character.view.mail',
    'middleware' => 'characterbouncer:mail',
    'uses'       => 'ViewController@getMail'
]);

Route::get('/view/mail/{character_id}/read/{message_id}', [
    'as'         => 'character.view.mail.read',
    'middleware' => 'characterbouncer:mail',
    'uses'       => 'ViewController@getMailRead'
]);

Route::get('/view/market/{character_id}', [
    'as'         => 'character.view.market',
    'middleware' => 'characterbouncer:market',
    'uses'       => 'ViewController@getMarket'
]);

Route::get('/view/notifications/{character_id}', [
    'as'         => 'character.view.notifications',
    'middleware' => 'characterbouncer:notifications',
    'uses'       => 'ViewController@getNotifications'
]);

Route::get('/view/pi/{character_id}', [
    'as'         => 'character.view.pi',
    'middleware' => 'characterbouncer:pi',
    'uses'       => 'ViewController@getPi'
]);

Route::get('/view/research/{character_id}', [
    'as'         => 'character.view.research',
    'middleware' => 'characterbouncer:research',
    'uses'       => 'ViewController@getResearch'
]);

Route::get('/view/sheet/{character_id}', [
    'as'         => 'character.view.sheet',
    'middleware' => 'characterbouncer:sheet',
    'uses'       => 'ViewController@getSheet'
]);

Route::get('/view/skills/{character_id}', [
    'as'         => 'character.view.skills',
    'middleware' => 'characterbouncer:skills',
    'uses'       => 'ViewController@getSkills'
]);

Route::get('/view/standings/{character_id}', [
    'as'         => 'character.view.standings',
    'middleware' => 'characterbouncer:standings',
    'uses'       => 'ViewController@getStandings'
]);

Route::get('/view/transactions/{character_id}', [
    'as'         => 'character.view.transactions',
    'middleware' => 'characterbouncer:transactions',
    'uses'       => 'ViewController@getTransactions'
]);
