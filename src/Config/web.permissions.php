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

return [

    'superuser',
    'queue_manager',

    'character' => [
        'asset' => [
            'label' => 'Grant access to Character Assets',
            'description' => 'The Character Assets is showing every single owned assets, their location and quantity. ',
        ],
        'bookmark' => [
            'label' => 'Grant access to Character Bookmarks',
            'description' => 'The Character Bookmarks is showing every single bookmarks made by a character, including folder structure, name, location and optional note.',
        ],
        'calendar' => [
            'label' => 'Grant access to Character Calendar Events ',
            'description' => 'The Character Calendar is showing event, created or subscribed by the character.',
        ],
        'contact' => [
            'label' => 'Grant access to Character Contacts',
            'description' => 'The Character Contacts is showing contacts including name, standing and link to third party platform (like zkillboard).',
        ],
        'contract' => [
            'label' => 'Grant access to Character Contracts ',
            'description' => 'The Character Contracts is showing contracts including creation date, type, status and content.',
        ],
        'fitting' => [
            'label' => 'Grant access to Character Fittings',
            'description' => 'The Character Fittings is showing fitting registered by the character, including name, hull type and modules.',
        ],
        'industry' => [
            'label' => 'Grant access to Character Industry',
            'description' => 'The Character Industry is listing all jobs owned by the character, showing their starting date, expected ending, location, activity, runs amount, input blueprint and output product.',
        ],
        'intel' => [
            'label' => 'Grant access to Character Intel',
            'description' => ' The Character Intel is a tool showing aggregated information for a character based on built profile. It will also show you in-game interaction based on transactions and mails.',
        ],
        'killmail' => [
            'label' => 'Grant access to Character Killmails',
            'description' => 'The Character Killmails is listing all kill made or received by a character. It will show data, hull type, location and victim information.',
        ],
        'mail' => [
            'label' => 'Grant access to Character Mail',
            'description' => 'The Character Mail is showing all mails received by a character.',
        ],
        'market' => [
            'label' => 'Grant access to Character Market',
            'description' => 'The Character Market is showing all buy or sell orders made by a character.',
        ],
        'mining' => [
            'label' => 'Grant access to Character Mining',
            'description' => 'The Character Mining is showing stats regarding mining made by a character. It is based on the in-game Personal Mining Ledger and show date, system, ore, quantity, volume and average value.',
        ],
        'notification' => [
            'label' => 'Grant access to Character Notification',
            'description' => '',
        ],
        'planetary' => [
            'label' => 'Grant access to Character Planetary Interaction',
            'description' => 'Display planets on which the character deploy a command center and the linked installations.',
        ],
        'research' => [
            'label' => 'Grant access to Character Research Agents',
            'description' => 'List all research agents which are currently working for the character.',
        ],
        'skill' => [
            'label' => 'Grant access to Character Skills',
            'description' => 'List all skills owned by the character, including their trained level.',
        ],
        'standing' => [
            'label' => 'Grant access to Character Standing List',
            'description' => 'List all character standings with assigned level.',
        ],
        'sheet' => [
            'label' => 'Grant access to Character Sheet',
            'description' => 'The Character Sheet is containing some basic information like the character attributes, its titles, implants etc... It is also showing summary related to skill queue and current training skill.',
        ],
        'journal' => [
            'label' => 'Grant access to Character Wallet Journal',
            'description' => 'This will show the Wallet Journal from the character.',
        ],
        'transaction' => [
            'label' => 'Grant access to Character Wallet Transaction',
            'description' => 'This will show the Wallet Transaction from the character.',
        ],
    ],

    'corporation' => [
        'assets' => [
            'label' => 'Grant access to Corporation Assets',
            'description' => 'The Corporation Assets is showing every singled owned assets, their location and quantity.',
        ],
        'bookmarks' => [
            'label' => 'Grant access to Corporation Bookmarks',
            'description' => 'The Corporation Bookmarks is showing every single bookmarks made on behalf a corporation, including folder structure, name, location and optional note.',
        ],
        'contacts' => [
            'label' => 'Grant access to Corporation Contacts',
            'description' => 'The Corporation Contacts is showing contacts including name, standings and link to third party platform (like zkillboard).',
        ],
        'contracts' => [
            'label' => 'Grant access to Corporation Contracts',
            'description' => 'The Corporation Contract is showing contracts including creation date, type, status and content.',
        ],
        'extractions' => [
            'label' => 'Grant access to Corporation Extractions',
            'description' => 'The Corporation Extractions is showing moon extraction information from refineries owned by a corporation.',
        ],
        'industry' => [
            'label' => 'Grant access to Corporation Industry',
            'description' => 'The Corporation Industry is listing all jobs made on behalf a corporation, showing their starting date, expected ending, location, activity, runs amount, input blueprint and output product.',
        ],
        'killmails' => [
            'label' => 'Grant access to Corporation Kill Mails',
            'description' => 'The Corporation Kill Mails is listing all kill made or received by a corporation member. It will show data, hull type, location and victim information.',
        ],
        'ledger' => [
            'label' => 'Grant access to Corporation Wallet Ledger',
            'description' => 'The Corporation Wallet Ledger is a tool showing wallet transactions groups by category and per division.',
        ],
        'market' => [
            'label' => 'Grant access to Corporation Market',
            'description' => 'The Corporation Market is showing all buy or sell orders made on behalf a corporation.',
        ],
        'mining' => [
            'label' => 'Grant access to Corporation Mining',
            'description' => 'The Corporation Mining is showing stats regarding mining made by a character. It is based on the in-game Personal Mining Ledger of each corporation members and show date, system, ore, quantity, volume and average value.',
        ],
        'security' => [
            'label' => 'Grant access to Corporation Security',
            'description' => 'Provide information regarding roles setup, titles and hangar logs.',
        ],
        'standings' => [
            'label' => 'Grant access to Corporation Standings',
            'description' => 'List all standings with assigned level for the corporation.',
        ],
        'tracking' => [
            'label' => 'Grant access to Corporation Tracking',
            'description' => 'The Corporation Tracking page is showing a report of SeAT registered users compared to all members.',
        ],
        'pocos' => [
            'label' => 'Grant access to Corporation Customs Offices',
            'description' => 'The Corporation Customs Offices is listing all POCOs owned by a corporation including their location, tax settings and accessibility level.',
        ],
        'starbases' => [
            'label' => 'Grant access to Corporation Starbases',
            'description' => 'The Corporation Starbases is listing all starbases owned by the corporation including type, location, estimated offline period, reinforcement status and modules.',
        ],
        'structures' => [
            'label' => 'Grant access to Corporation Structures',
            'description' => 'The Corporation Structures is listing all structures owned by the corporation including type, location, estimated offline period, reinforcement settings and services.',
        ],
        'summary' => [
            'label' => 'Grant access to Corporation Sheet',
            'description' => 'The Corporation Sheet is containing some basic information like the corporation name, description, divisions, etc...',
        ],
        'journal' => [
            'label' => 'Grant access to Corporation Wallet Journal',
            'description' => 'This will show the Wallet Journal from the corporation.',
        ],
        'transactions' => [
            'label' => 'Grant access to Corporation Wallet Transactions',
            'description' => 'This will show the Wallet Transaction from the corporation.',
        ],
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
