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

Route::get('/groups')
    ->name('seatcore::fastlookup.groups')
    ->uses('FastLookupController@getGroups');

Route::get('/titles')
    ->name('seatcore::fastlookup.titles')
    ->uses('FastLookupController@getTitles');

Route::get('/users')
    ->name('seatcore::fastlookup.users')
    ->uses('FastLookupController@getUsers');

Route::get('/characters')
    ->name('seatcore::fastlookup.characters')
    ->uses('FastLookupController@getCharacters');

Route::get('/corporations')
    ->name('seatcore::fastlookup.corporations')
    ->uses('FastLookupController@getCorporations');

Route::get('/factions')
    ->name('seatcore::fastlookup.factions')
    ->uses('FastLookupController@getFactions');

Route::get('/alliances')
    ->name('seatcore::fastlookup.alliances')
    ->uses('FastLookupController@getAlliances');

Route::get('/entities')
    ->name('seatcore::fastlookup.entities')
    ->uses('FastLookupController@getEntities')
    ->middleware('can:global.superuser');

Route::get('/items')
    ->name('seatcore::fastlookup.items')
    ->uses('FastLookupController@getItems');

Route::get('/scopes')
    ->name('seatcore::fastlookup.scopes')
    ->uses('FastLookupController@getScopes');

Route::get('/skills')
    ->name('seatcore::fastlookup.skills')
    ->uses('FastLookupController@getSkills');

Route::get('/regions')
    ->name('seatcore::fastlookup.regions')
    ->uses('FastLookupController@getRegions');

Route::get('/constellations')
    ->name('seatcore::fastlookup.constellations')
    ->uses('FastLookupController@getConstellations');

Route::get('/systems')
    ->name('seatcore::fastlookup.systems')
    ->uses('FastLookupController@getSystems');

Route::get('/roles')
    ->name('seatcore::fastlookup.roles')
    ->uses('FastLookupController@getCorporationRoles');
