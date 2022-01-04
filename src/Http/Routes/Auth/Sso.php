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

Route::get('/eve', [
    'as'   => 'auth.eve',
    'uses' => 'SsoController@redirectToProvider',
]);

Route::get('/eve/profile/{profile?}', [
    'as'   => 'auth.eve.profile',
    'uses' => 'SsoController@redirectToProvider',
]);

Route::get('/eve/callback', [
    'as'   => 'auth.eve.callback',
    'uses' => 'SsoController@handleProviderCallback',
]);

Route::get('/eve/email', [
    'as'   => 'auth.eve.email',
    'uses' => 'SsoController@getUserEmail',
]);

Route::post('/eve/email', [
    'as'   => 'auth.eve.email.set',
    'uses' => 'SsoController@postUpdateUserEmail',
]);

Route::get('/eve/confirm', [
    'as'   => 'auth.eve.confirmation.get',
    'uses' => 'SsoController@getSsoConfirmation',
]);

Route::post('/eve/confirm', [
    'as'   => 'auth.eve.confirmation.post',
    'uses' => 'SsoController@postSsoConfirmation',
]);
