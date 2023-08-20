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
    ->name('seatcore::profile.view')
    ->uses('ProfileController@getView');

Route::get('/scopes/{user_id}/{character_id}')
    ->name('seatcore::profile.character.scopes')
    ->uses('ProfileController@getCharacterScopes');

Route::post('/update')
    ->name('seatcore::profile.update.settings')
    ->uses('ProfileController@postUpdateUserSettings');

Route::post('/update/email')
    ->name('seatcore::profile.update.email')
    ->uses('ProfileController@postUpdateEmail');

Route::post('/update/main-character')
    ->name('seatcore::profile.change-character')
    ->uses('ProfileController@postChangeCharacter');

Route::post('/update/sharelink')
    ->name('seatcore::profile.update.sharelink')
    ->uses('ProfileController@postUpdateSharelink');

Route::delete('/update/sharelink')
    ->name('seatcore::profile.update.sharelink.remove')
    ->uses('ProfileController@deleteRemoveSharelink');

Route::delete('/character')
    ->name('seatcore::profile.delete.character')
    ->uses('ProfileController@deleteUnlinkCharacter');
