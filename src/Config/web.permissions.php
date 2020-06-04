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

return [

    'superuser',
    'queue_manager',

    'apikey' => [
        'delete',
        'detail',
        'toggle_status',
        'list',
        'update',
    ],

    'character' => [
        'assets',
        'bookmarks',
        'calendar',
        'channels',
        'contacts',
        'contracts',
        'fittings',
        'industry',
        'intel',
        'jobs',     // Allows for the dispatching of jobs.
        'killmails',
        'list',
        'mail',
        'market',
        'mining',
        'notifications',
        'pi',
        'research',
        'skills',
        'standings',
        'sheet',
        'journal',
        'transactions',
    ],

    'corporation' => [
        'assets',
        'bookmarks',
        'contacts',
        'contracts',
        'extractions',
        'industry',
        'killmails',
        'ledger',
        'list_all',
        'market',
        'mining',
        'security',
        'standings',
        'tracking',
        'pocos',
        'starbases',
        'structures',
        'summary',
        'journal',
        'transactions',
    ],

    'mail' => [
        'bodies',
        'subjects',
    ],

    'people' => [
        'create',
        'edit',
        'view',
    ],

    'search' => [
        'character_assets',
        'character_contact_lists',
        'character_mail',
        'characters',
        'character_skills',
        'character_standings',
        'corporation_assets',
        'corporation_standings',
    ],
];
