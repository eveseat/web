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

return [
    [
        'name'           => 'assets',
        'label'          => 'web::seat.assets',
        'permission'     => 'character.asset',
        'highlight_view' => 'assets',
        'route'          => 'character.view.assets',
    ],
    [
        'name'           => 'calendar',
        'label'          => 'web::seat.calendar',
        'permission'     => 'character.calendar',
        'highlight_view' => 'calendar',
        'route'          => 'character.view.calendar',
    ],
    [
        'name'           => 'contacts',
        'label'          => 'web::seat.contacts',
        'permission'     => 'character.contact',
        'highlight_view' => 'contacts',
        'route'          => 'character.view.contacts',
    ],
    [
        'name'           => 'contracts',
        'label'          => 'web::seat.contracts',
        'permission'     => 'character.contract',
        'highlight_view' => 'contracts',
        'route'          => 'character.view.contracts',
    ],
    [
        'name'           => 'fittings',
        'label'          => 'web::seat.fittings',
        'permission'     => 'character.fitting',
        'highlight_view' => 'fittings',
        'route'          => 'character.view.fittings',
    ],
    [
        'name'           => 'blueprint',
        'label'          => 'web::seat.blueprint',
        'permission'     => 'character.blueprint',
        'highlight_view' => 'blueprint',
        'route'          => 'character.view.blueprint',
    ],
    [
        'name'           => 'industry',
        'label'          => 'web::seat.industry',
        'permission'     => 'character.industry',
        'highlight_view' => 'industry',
        'route'          => 'character.view.industry',
    ],
    [
        'name'           => 'intel',
        'label'          => 'web::seat.intel',
        'permission'     => 'character.intel',
        'highlight_view' => 'intel',
        'route'          => 'character.view.intel.summary',
    ],
    [
        'name'           => 'killmails',
        'label'          => 'web::seat.killmails',
        'permission'     => 'character.killmail',
        'highlight_view' => 'killmails',
        'route'          => 'character.view.killmails',
    ],
    [
        'name'           => 'mail',
        'label'          => 'web::seat.mail',
        'permission'     => 'character.mail',
        'highlight_view' => 'mail',
        'route'          => 'character.view.mail',
    ],
    [
        'name'           => 'market',
        'label'          => 'web::seat.market',
        'permission'     => 'character.market',
        'highlight_view' => 'market',
        'route'          => 'character.view.market',
    ],
    [
        'name'           => 'mining-ledger',
        'label'          => 'web::seat.mining',
        'permission'     => 'character.mining',
        'highlight_view' => 'mining-ledger',
        'route'          => 'character.view.mining_ledger',
    ],
    [
        'name'           => 'notifications',
        'label'          => 'web::seat.notifications',
        'permission'     => 'character.notification',
        'highlight_view' => 'notifications',
        'route'          => 'character.view.notifications',
    ],
    [
        'name'           => 'pi',
        'label'          => 'web::seat.pi',
        'permission'     => 'character.planetary',
        'highlight_view' => 'pi',
        'route'          => 'character.view.pi',
    ],
    [
        'name'           => 'research',
        'label'          => 'web::seat.research',
        'permission'     => 'character.research',
        'highlight_view' => 'research',
        'route'          => 'character.view.research',
    ],
    [
        'name'           => 'sheet',
        'label'          => 'web::seat.sheet',
        'permission'     => 'character.sheet',
        'highlight_view' => 'sheet',
        'route'          => 'character.view.sheet',
    ],
    [
        'name'           => 'skills',
        'label'          => 'web::seat.skills',
        'permission'     => 'character.skill',
        'highlight_view' => 'skills',
        'route'          => 'character.view.skills',
    ],
    [
        'name'           => 'standings',
        'label'          => 'web::seat.standings',
        'plural'         => true,
        'permission'     => 'character.standing',
        'highlight_view' => 'standings',
        'route'          => 'character.view.standings',
    ],
    [
        'name'           => 'wallet',
        'label'          => 'web::seat.wallet',
        'permission'     => ['character.journal', 'character.transactions'],
        'highlight_view' => 'wallet',
        'route'          => 'character.view.journal',
    ],
];
