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
    ->name('seatcore::squads.index')
    ->uses('SquadsController@index');

Route::group([
    'middleware' => 'can:squads.create',
], function () {
    // squad creation form
    Route::get('/create')
        ->name('seatcore::squads.create')
        ->uses('SquadsController@create');

    // create a squad
    Route::post('/')
        ->name('seatcore::squads.store')
        ->uses('SquadsController@store');
});

Route::prefix('/{squad}')
    ->group(function () {

        // squad card
        Route::get('/')
            ->name('seatcore::squads.show')
            ->uses('SquadsController@show');

        // squad update form
        Route::get('/edit')
            ->name('seatcore::squads.edit')
            ->uses('SquadsController@edit')
            ->middleware('can:squads.edit,squad');

        // update a squad
        Route::put('/')
            ->name('seatcore::squads.update')
            ->uses('SquadsController@update')
            ->middleware('can:squads.edit,squad');

        // remove a squad
        Route::delete('/')
            ->name('seatcore::squads.destroy')
            ->uses('SquadsController@destroy')
            ->middleware('can:squads.delete,squad');
    });

Route::prefix('/{squad}/members')->group(function () {

    // select2 squad member (not already part of active squad)
    Route::get('/lookup')
        ->name('seatcore::squads.members.lookup')
        ->uses('MembersController@lookup')
        ->middleware('can:squads.manage_members,squad');

    // add member to a squad
    Route::post('/')
        ->name('seatcore::squads.members.store')
        ->uses('MembersController@store')
        ->middleware('can:squads.manage_members,squad');

    // remove member from a squad
    Route::delete('/{member}')
        ->name('seatcore::squads.members.kick')
        ->uses('MembersController@destroy')
        ->middleware('can:squads.kick,squad,member');

    // show squad members
    Route::get('/')
        ->name('seatcore::squads.members.index')
        ->uses('MembersController@index')
        ->middleware('can:squads.show_members,squad');

    // leave a squad
    Route::delete('/')
        ->name('seatcore::squads.members.leave')
        ->uses('MembersController@leave');
});

Route::prefix('/{squad}/moderators')
    ->middleware('can:squads.manage_moderators,squad')
    ->group(function () {

        // select2 squad moderators (not part of active squad)
        Route::get('/lookup')
            ->name('seatcore::squads.moderators.lookup')
            ->uses('ModeratorsController@lookup');

        // add moderator to a squad
        Route::post('/')
            ->name('seatcore::squads.moderators.store')
            ->uses('ModeratorsController@store');

        // remove moderator from a squad
        Route::delete('/{user}')
            ->name('seatcore::squads.moderators.destroy')
            ->uses('ModeratorsController@destroy');
    });

Route::prefix('/{squad}/roles')
    ->middleware('can:squads.manage_roles,squad')
    ->group(function () {

        // show squad roles
        Route::get('/')
            ->name('seatcore::squads.roles.show')
            ->uses('RolesController@show');

        // select2 squad roles (not part of active squad)
        Route::get('/lookup')
            ->name('seatcore::squads.roles.lookup')
            ->uses('RolesController@lookup');

        // add a role to a squad
        Route::post('/')
            ->name('seatcore::squads.roles.store')
            ->uses('RolesController@store');

        // remove a role from a squad
        Route::delete('/')
            ->name('seatcore::squads.roles.destroy')
            ->uses('RolesController@destroy');
    });

Route::prefix('/{squad}/applications')
    ->group(function () {

        // show squad applications
        Route::get('/')
            ->name('seatcore::squads.applications.index')
            ->uses('ApplicationsController@index')
            ->middleware('can:squads.manage_members,squad');

        // apply to a squad
        Route::post('/')
            ->name('seatcore::squads.applications.store')
            ->uses('ApplicationsController@store');

        // revoke squad application
        Route::delete('/')
            ->name('seatcore::squads.applications.cancel')
            ->uses('ApplicationsController@cancel');

        // show squad application details
        Route::get('/{id}')
            ->name('seatcore::squads.applications.show')
            ->uses('ApplicationsController@show')
            ->middleware('can:squads.manage_members,squad');

        // approve a squad application
        Route::post('/{id}')
            ->name('seatcore::squads.applications.approve')
            ->uses('ApplicationsController@approve')
            ->middleware('can:squads.manage_members,squad');

        // reject a squad application
        Route::delete('/{id}')
            ->name('seatcore::squads.applications.reject')
            ->uses('ApplicationsController@reject')
            ->middleware('can:squads.manage_members,squad');
    });
