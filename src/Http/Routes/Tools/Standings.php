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

Route::group([
    'prefix'    => 'standings',
    'middleware' => 'can:global.standing_builder',
], function () {

    Route::get('/')
        ->name('tools.standings')
        ->uses('StandingsController@getAvailableProfiles');

    Route::post('/')
        ->name('tools.standings.new')
        ->uses('StandingsController@postNewStanding');

    Route::get('/delete/{profile_id}')
        ->name('tools.standings.delete')
        ->uses('StandingsController@getDeleteStandingsProfile');

    Route::get('/edit/{id}')
        ->name('tools.standings.edit')
        ->uses('StandingsController@getStandingEdit');

    // Ajax Search
    Route::post('/ajax/element')
        ->name('tools.standings.ajax.element')
        ->uses('StandingsController@getStandingsAjaxElementName');

    Route::post('/edit/add-element')
        ->name('tools.standings.edit.addelement')
        ->uses('StandingsController@postAddElementToStanding');

    Route::post('/edit/add-element/fromcorpchar')
        ->name('tools.standings.edit.addelement.fromcorpchar')
        ->uses('StandingsController@postAddStandingsFromCorpOrChar');

    Route::get('/edit/remove/{entity_id}/{profile_id}')
        ->name('tools.standings.edit.remove')
        ->uses('StandingsController@getRemoveElementFromProfile');
});
