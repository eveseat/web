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

return [

    'superuser',
    'queue_manager',

    'apikey'      => [
        'delete',
        'detail',
        'toggle_status',
        'list',
        'update'
    ],

    'character'   => [
        'assets',
        'calendar',
        'contacts',
        'contracts',
        'industry',
        'killmails',
        'list',
        'mail',
        'market_orders',
        'notifications',
        'pi',
        'research_agents',
        'skills',
        'standings',
        'sheet',
        'wallet_journal',
        'wallet_transactions',
    ],

    'corporation' => [
        'assets',
        'contracts',
        'industry',
        'killmails',
        'ledger',
        'list_all',
        'market_orders',
        'member_security',
        'member_standings',
        'member_tracking',
        'poco',
        'star_bases',
        'summary',
        'wallet_journal',
        'transactions',
    ],

    'mail'        => [
        'bodies',
        'subjects'
    ],

    'people'      => [
        'create',
        'edit',
        'view'
    ],

    'search'      => [
        'character_assets',
        'character_contact_lists',
        'character_mail',
        'characters',
        'character_skills',
        'character_standings',
        'corporation_assets',
        'corporation_standings',
    ]
];
