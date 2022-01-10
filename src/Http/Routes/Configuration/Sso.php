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

Route::match(['get', 'post'], '/', [
    'as'   => 'configuration.sso',
    'uses' => 'SsoController@getConfigurationHome',
]);

Route::post('/update-scopes', [
    'as'   => 'configuration.sso.update_scopes',
    'uses' => 'SsoController@postUpdateScopes',
]);

Route::get('/set-default-profile/{id}', [
    'as'   => 'configuration.sso.set_default_profile',
    'uses' => 'SsoController@getSetDefaultProfile',
]);

Route::get('/add-profile', [
    'as'   => 'configuration.sso.add_profile',
    'uses' => 'SsoController@getAddProfile',
]);

Route::get('/delete-profile/{id}', [
    'as'   => 'configuration.sso.delete_profile',
    'uses' => 'SsoController@getDeleteProfile',
]);

Route::post('/update-custom-signin', [
    'as'   => 'configuration.sso.update_custom_signin',
    'uses' => 'SsoController@postUpdateCustomSignin',
]);
