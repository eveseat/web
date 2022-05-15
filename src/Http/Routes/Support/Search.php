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

Route::match(['get', 'post'], '/search', [
    'as'   => 'support.search',
    'uses' => 'SearchController@getSearch',
]);

Route::get('/search/characters/data', [
    'as'   => 'support.search.characters.data',
    'uses' => 'SearchController@getSearchCharactersData',
]);

Route::get('/search/corporations/data', [
    'as'   => 'support.search.corporations.data',
    'uses' => 'SearchController@getSearchCorporationsData',
]);

Route::get('/search/mail/data', [
    'as'   => 'support.search.mail.data',
    'uses' => 'SearchController@getSearchMailData',
]);

Route::get('/search/assets/data', [
    'as'   => 'support.search.assets.data',
    'uses' => 'SearchController@getSearchCharacterAssetsData',
]);

Route::get('/search/skills/data', [
    'as'   => 'support.search.skills.data',
    'uses' => 'SearchController@getSearchCharacterSkillsData',
]);
