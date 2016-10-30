<?php
/*
This file is part of SeAT

Copyright (C) 2015, 2016  Leon Jacobs

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

Route::get('/status', [
    'as'         => 'queue.status',
    'middleware' => 'bouncer:queue_manager',
    'uses'       => 'QueueController@getStatus'
]);

Route::get('/json/supervisor/status', [
    'as'         => 'json.supervisor.status',
    'middleware' => 'bouncer:queue_manager',
    'uses'       => 'QueueController@getSupervisorStatus'
]);

Route::get('/json/supervisor/processes', [
    'as'         => 'json.supervisor.processes',
    'middleware' => 'bouncer:queue_manager',
    'uses'       => 'QueueController@getSupervisorProcesses'
]);

Route::get('/supervisor/information', [
    'as'         => 'queue.supervisor.information',
    'middleware' => 'bouncer:queue_manager',
    'uses'       => 'QueueController@getSupervisorInformation'
]);

Route::get('/short-status', [
    'as'   => 'queue.status.short',
    'uses' => 'QueueController@getShortStatus'
]);

Route::get('/run/{command_name}', [
    'as'         => 'queue.command.run',
    'middleware' => 'bouncer:queue_manager',
    'uses'       => 'QueueController@getSubmitJob'
]);

Route::get('/errors', [
    'as'         => 'queue.errors',
    'middleware' => 'bouncer:queue_manager',
    'uses'       => 'QueueController@getErrors'
]);

Route::get('/errors/detail/{job_id}', [
    'as'         => 'queue.errors.detail',
    'middleware' => 'bouncer:queue_manager',
    'uses'       => 'QueueController@getErrorDetail'
]);

Route::get('/errors/clear', [
    'as'         => 'queue.errors.clear',
    'middleware' => 'bouncer:queue_manager',
    'uses'       => 'QueueController@getClearErrors'
]);

Route::get('/history', [
    'as'         => 'queue.history',
    'middleware' => 'bouncer:queue_manager',
    'uses'       => 'QueueController@getHistory'
]);

Route::get('/history/clear', [
    'as'         => 'queue.history.clear',
    'middleware' => 'bouncer:queue_manager',
    'uses'       => 'QueueController@getClearHistory'
]);
