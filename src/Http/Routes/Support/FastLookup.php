<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2022 Leon Jacobs
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

Route::get('/groups', [
    'as'   => 'fastlookup.groups',
    'uses' => 'FastLookupController@getGroups',
]);

Route::get('/titles', [
    'as'   => 'fastlookup.titles',
    'uses' => 'FastLookupController@getTitles',
]);

Route::get('/users', [
    'as'   => 'fastlookup.users',
    'uses' => 'FastLookupController@getUsers',
]);

Route::get('/characters', [
    'as'   => 'fastlookup.characters',
    'uses' => 'FastLookupController@getCharacters',
]);

Route::get('/corporations', [
    'as'   => 'fastlookup.corporations',
    'uses' => 'FastLookupController@getCorporations',
]);

Route::get('/alliances', [
    'as'   => 'fastlookup.alliances',
    'uses' => 'FastLookupController@getAlliances',
]);

Route::get('/entities', [
    'as'         => 'fastlookup.entities',
    'uses'       => 'FastLookupController@getEntities',
    'middleware' => 'can:global.superuser',
]);

Route::get('/items', [
    'as'   => 'fastlookup.items',
    'uses' => 'FastLookupController@getItems',
]);

Route::get('/scopes', [
    'as'   => 'fastlookup.scopes',
    'uses' => 'FastLookupController@getScopes',
]);

Route::get('/skills', [
    'as'   => 'fastlookup.skills',
    'uses' => 'FastLookupController@getSkills',
]);

Route::get('/regions', [
    'as'   => 'fastlookup.regions',
    'uses' => 'FastLookupController@getRegions',
]);

Route::get('/constellations', [
    'as'   => 'fastlookup.constellations',
    'uses' => 'FastLookupController@getConstellations',
]);

Route::get('/systems', [
    'as'   => 'fastlookup.systems',
    'uses' => 'FastLookupController@getSystems',
]);

Route::get('/roles', [
    'as'   => 'fastlookup.roles',
    'uses' => 'FastLookupController@getCorporationRoles',
]);
