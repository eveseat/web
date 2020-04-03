<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015, 2016, 2017, 2018, 2019  Leon Jacobs
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

// list all squads
Route::get('/', [
    'as'   => 'squads.index',
    'uses' => 'SquadsController@index',
]);

Route::middleware(['bouncer::global.superuser'])
    ->group(function () {

        // squad creation form
        Route::get('/create', [
            'as'   => 'squads.create',
            'uses' => 'SquadsController@create',
        ]);

        // create a squad
        Route::post('/', [
            'as'   => 'squads.store',
            'uses' => 'SquadsController@store',
        ]);
    });

Route::prefix('/{id}')
    ->group(function () {

        // squad card
        Route::get('/', [
            'as'   => 'squads.show',
            'uses' => 'SquadsController@show',
        ]);

        Route::middleware(['bouncer:global.superuser'])
            ->group(function () {

                // squad update form
                Route::get('/edit', [
                    'as'   => 'squads.edit',
                    'uses' => 'SquadsController@edit',
                ]);

                // update a squad
                Route::put('/', [
                    'as'   => 'squads.update',
                    'uses' => 'SquadsController@update',
                ]);

                // remove a squad
                Route::delete('/', [
                    'as' => 'squads.destroy',
                    'uses' => 'SquadsController@destroy',
                ]);
            });
    });

Route::prefix('/{id}/members')->group(function () {

    Route::middleware(['squad.moderator.bouncer'])
        ->group(function () {

            // select2 squad member (not already part of active squad)
            Route::get('/lookup', [
                'as'   => 'squads.members.lookup',
                'uses' => 'MembersController@lookup',
            ]);

            // add member to a squad
            Route::post('/', [
                'as'   => 'squads.members.store',
                'uses' => 'MembersController@store',
            ]);

            // remove member from a squad
            Route::delete('/', [
                'as'   => 'squads.members.kick',
                'uses' => 'MembersController@destroy',
            ]);
        });

    Route::middleware(['squad.member.bouncer'])
        ->group(function () {

            // show squad members
            Route::get('/', [
                'as'   => 'squads.members.index',
                'uses' => 'MembersController@index',
            ]);

            // leave a squad
            Route::delete('/leave', [
                'as' => 'squads.members.leave',
                'uses' => 'MembersController@leave',
            ]);
        });
});

Route::prefix('/{id}/moderators')
    ->middleware('bouncer:global.superuser')
    ->group(function () {

        // select2 squad moderators (not part of active squad)
        Route::get('/lookup', [
            'as'   => 'squads.moderators.lookup',
            'uses' => 'ModeratorsController@lookup',
        ]);

        // add moderator to a squad
        Route::post('/', [
            'as'   => 'squads.moderators.store',
            'uses' => 'ModeratorsController@store',
        ]);

        // remove moderator from a squad
        Route::delete('/', [
            'as'   => 'squads.moderators.destroy',
            'uses' => 'ModeratorsController@destroy',
        ]);
    });

Route::prefix('/{id}/roles')
    ->middleware('bouncer:global.superuser')
    ->group(function () {

        // show squad roles
        Route::get('/', [
            'as'   => 'squads.roles.show',
            'uses' => 'RolesController@show',
        ]);

        // select2 squad roles (not part of active squad)
        Route::get('/lookup', [
            'as'   => 'squads.roles.lookup',
            'uses' => 'RolesController@lookup',
        ]);

        // add a role to a squad
        Route::post('/', [
            'as'   => 'squads.roles.store',
            'uses' => 'RolesController@store',
        ]);

        // remove a role from a squad
        Route::delete('/', [
            'as'   => 'squads.roles.destroy',
            'uses' => 'RolesController@destroy',
        ]);
    });

Route::prefix('/{id}/applications')
    ->group(function () {

        // show squad applications
        Route::get('/', [
            'as'   => 'squads.applications.index',
            'uses' => 'ApplicationsController@index',
            'middleware' => ['squad.moderator.bouncer'],
        ]);

        // apply to a squad
        Route::post('/', [
            'as'   => 'squads.applications.store',
            'uses' => 'ApplicationsController@store',
        ]);

        // revoke squad application
        Route::delete('/', [
            'as'   => 'squads.applications.cancel',
            'uses' => 'ApplicationsController@cancel',
        ])->middleware('squad.author.bouncer');
    });

Route::prefix('/applications/{id}')
    ->middleware('squad.moderator.bouncer')
    ->group(function () {

        // show squad application details
        Route::get('/', [
            'as'   => 'squads.applications.show',
            'uses' => 'ApplicationsController@show',
        ]);

        // approve a squad application
        Route::post('/', [
            'as'   => 'squads.applications.approve',
            'uses' => 'ApplicationsController@approve',
        ]);

        // reject a squad application
        Route::delete('/', [
            'as'   => 'squads.applications.reject',
            'uses' => 'ApplicationsController@reject',
        ]);
    });
