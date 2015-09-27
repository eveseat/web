<?php
/*
This file is part of SeAT

Copyright (C) 2015  Leon Jacobs

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

// Authentication routes
Route::get('login', [
    'as'   => 'auth.login',
    'uses' => 'AuthController@getLogin'
]);
Route::post('login', [
    'as'   => 'auth.login.post',
    'uses' => 'AuthController@postLogin'
]);
Route::get('logout', [
    'as'   => 'auth.logout',
    'uses' => 'AuthController@getLogout'
]);

// Registration routes
Route::get('register', [
    'as'   => 'auth.register',
    'uses' => 'AuthController@getRegister'
]);
Route::post('register', [
    'as'   => 'auth.register.post',
    'uses' => 'AuthController@postRegister'
]);

