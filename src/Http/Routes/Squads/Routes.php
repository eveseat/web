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

Route::get('/', [
    'as'   => 'squads.index',
    'uses' => 'SquadsController@index',
]);

Route::group([
    'middleware' => 'can:squads.create',
], function () {
    // squad creation form
    Route::get('/create')
        ->name('squads.create')
        ->uses('SquadsController@create');

    // create a squad
    Route::post('/')
        ->name('squads.store')
        ->uses('SquadsController@store');
});

Route::prefix('/{squad}')
    ->group(function () {

        // squad card
        Route::get('/')
            ->name('squads.show')
            ->uses('SquadsController@show');

        // squad update form
        Route::get('/edit')
            ->name('squads.edit')
            ->uses('SquadsController@edit')
            ->middleware('can:squads.edit,squad');

        // update a squad
        Route::put('/')
            ->name('squads.update')
            ->uses('SquadsController@update')
            ->middleware('can:squads.edit,squad');

        // remove a squad
        Route::delete('/')
            ->name('squads.destroy')
            ->uses('SquadsController@destroy')
            ->middleware('can:squads.delete,squad');
    });

Route::prefix('/{squad}/members')->group(function () {

    // select2 squad member (not already part of active squad)
    Route::get('/lookup')
        ->name('squads.members.lookup')
        ->uses('MembersController@lookup')
        ->middleware('can:squads.manage_members,squad');

    // add member to a squad
    Route::post('/')
        ->name('squads.members.store')
        ->uses('MembersController@store')
        ->middleware('can:squads.manage_members,squad');

    // remove member from a squad
    Route::delete('/{member}')
        ->name('squads.members.kick')
        ->uses('MembersController@destroy')
        ->middleware('can:squads.kick,squad,member');

    // show squad members
    Route::get('/')
        ->name('squads.members.index')
        ->uses('MembersController@index')
        ->middleware('can:squads.show_members,squad');

    // leave a squad
    Route::delete('/')
        ->name('squads.members.leave')
        ->uses('MembersController@leave');
});

Route::prefix('/{squad}/moderators')
    ->middleware('can:squads.manage_moderators,squad')
    ->group(function () {

        // select2 squad moderators (not part of active squad)
        Route::get('/lookup')
            ->name('squads.moderators.lookup')
            ->uses('ModeratorsController@lookup');

        // add moderator to a squad
        Route::post('/')
            ->name('squads.moderators.store')
            ->uses('ModeratorsController@store');

        // remove moderator from a squad
        Route::delete('/{user}')
            ->name('squads.moderators.destroy')
            ->uses('ModeratorsController@destroy');
    });

Route::prefix('/{squad}/roles')
    ->middleware('can:squads.manage_roles,squad')
    ->group(function () {

        // show squad roles
        Route::get('/')
            ->name('squads.roles.show')
            ->uses('RolesController@show');

        // select2 squad roles (not part of active squad)
        Route::get('/lookup')
            ->name('squads.roles.lookup')
            ->uses('RolesController@lookup');

        // add a role to a squad
        Route::post('/')
            ->name('squads.roles.store')
            ->uses('RolesController@store');

        // remove a role from a squad
        Route::delete('/')
            ->name('squads.roles.destroy')
            ->uses('RolesController@destroy');
    });

Route::prefix('/{squad}/applications')
    ->group(function () {

        // show squad applications
        Route::get('/')
            ->name('squads.applications.index')
            ->uses('ApplicationsController@index')
            ->middleware('can:squads.manage_members,squad');

        // apply to a squad
        Route::post('/')
            ->name('squads.applications.store')
            ->uses('ApplicationsController@store');

        // revoke squad application
        Route::delete('/')
            ->name('squads.applications.cancel')
            ->uses('ApplicationsController@cancel');

        // show squad application details
        Route::get('/{id}')
            ->name('squads.applications.show')
            ->uses('ApplicationsController@show')
            ->middleware('can:squads.manage_members,squad');

        // approve a squad application
        Route::post('/{id}')
            ->name('squads.applications.approve')
            ->uses('ApplicationsController@approve')
            ->middleware('can:squads.manage_members,squad');

        // reject a squad application
        Route::delete('/{id}')
            ->name('squads.applications.reject')
            ->uses('ApplicationsController@reject')
            ->middleware('can:squads.manage_members,squad');
    });
