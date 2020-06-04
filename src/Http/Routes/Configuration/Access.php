<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2020 Leon Jacobs
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

Route::get('/roles', [
    'as'   => 'configuration.access.roles',
    'uses' => 'AccessController@getAll',
]);

Route::post('/roles', [
    'as'   => 'configuration.access.roles.add',
    'uses' => 'AccessController@newRole',
]);

Route::get('/roles/delete/{id}', [
    'as'   => 'configuration.access.roles.delete',
    'uses' => 'AccessController@deleteRole',
]);

Route::get('/roles/edit/{id}', [
    'as'   => 'configuration.access.roles.edit',
    'uses' => 'AccessController@editRole',
]);

Route::post('/roles/edit/permissions', [
    'as'   => 'configuration.access.roles.edit.permissions',
    'uses' => 'AccessController@grantPermissions',
]);

Route::get('/roles/edit/{role_id}/delete/permission/{permission_id}', [
    'as'   => 'configuration.access.roles.edit.remove.permission',
    'uses' => 'AccessController@removePermissions',
]);

Route::post('/roles/edit/groups', [
    'as'   => 'configuration.access.roles.edit.groups',
    'uses' => 'AccessController@addGroups',
]);

Route::get('/roles/edit/{role_id}/delete/group/{group_id}', [
    'as'   => 'configuration.access.roles.edit.remove.group',
    'uses' => 'AccessController@removeGroup',
]);

Route::post('/roles/edit/affiliations', [
    'as'   => 'configuration.access.roles.edit.affiliations',
    'uses' => 'AccessController@addAffiliations',
]);

Route::get('/roles/edit/{role_id}/delete/affiliation/{affiliation_id}', [
    'as'   => 'configuration.access.roles.edit.remove.affiliation',
    'uses' => 'AccessController@removeAffiliation',
]);
