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
        'permission'     => 'corporation.asset',
        'highlight_view' => 'assets',
        'route'          => 'corporation.view.assets',
    ],
    [
        'name'           => 'contacts',
        'label'          => 'web::seat.contacts',
        'permission'     => 'corporation.contact',
        'highlight_view' => 'contacts',
        'route'          => 'corporation.view.contacts',
    ],
    [
        'name'           => 'contracts',
        'label'          => 'web::seat.contracts',
        'permission'     => 'corporation.contract',
        'highlight_view' => 'contracts',
        'route'          => 'corporation.view.contracts',
    ],
    [
        'name'           => 'extractions',
        'label'          => 'web::seat.extraction',
        'plural'         => true,
        'permission'     => 'corporation.extraction',
        'highlight_view' => 'extractions',
        'route'          => 'corporation.view.extractions',
    ],
    [
        'name'           => 'blueprint',
        'label'          => 'web::seat.blueprint',
        'permission'     => 'corporation.blueprint',
        'highlight_view' => 'blueprint',
        'route'          => 'corporation.view.blueprint',
    ],
    [
        'name'           => 'industry',
        'label'          => 'web::seat.industry',
        'permission'     => 'corporation.industry',
        'highlight_view' => 'industry',
        'route'          => 'corporation.view.industry',
    ],
    [
        'name'           => 'killmails',
        'label'          => 'web::seat.killmails',
        'permission'     => 'corporation.killmail',
        'highlight_view' => 'killmails',
        'route'          => 'corporation.view.killmails',
    ],
    [
        'name'           => 'market',
        'label'          => 'web::seat.market',
        'permission'     => 'corporation.market',
        'highlight_view' => 'market',
        'route'          => 'corporation.view.market',
    ],
    [
        'name'           => 'mining',
        'label'          => 'web::seat.mining',
        'permission'     => 'corporation.mining',
        'highlight_view' => 'mining',
        'route'          => 'corporation.view.mining_ledger',
    ],
    [
        'name'           => 'customs-offices',
        'label'          => 'web::seat.customs-offices',
        'permission'     => 'corporation.customs_office',
        'highlight_view' => 'customs-offices',
        'route'          => 'corporation.view.customs-offices',
    ],
    [
        'name'           => 'security',
        'label'          => 'web::seat.security',
        'permission'     => 'corporation.security',
        'highlight_view' => 'security',
        'route'          => 'corporation.view.security.roles',
    ],
    [
        'name'           => 'starbase',
        'label'          => 'web::seat.starbase',
        'plural'         => true,
        'permission'     => 'corporation.starbase',
        'highlight_view' => 'starbases',
        'route'          => 'corporation.view.starbases',
    ],
    [
        'name'           => 'structure',
        'label'          => 'web::seat.structure',
        'plural'         => true,
        'permission'     => 'corporation.structure',
        'highlight_view' => 'structures',
        'route'          => 'corporation.view.structures',
    ],
    [
        'name'           => 'summary',
        'label'          => 'web::seat.summary',
        'permission'     => 'corporation.summary',
        'highlight_view' => 'summary',
        'route'          => 'corporation.view.summary',
    ],
    [
        'name'           => 'standings',
        'label'          => 'web::seat.standings',
        'plural'         => true,
        'permission'     => 'corporation.standing',
        'highlight_view' => 'standings',
        'route'          => 'corporation.view.standings',
    ],
    [
        'name'           => 'tracking',
        'label'          => 'web::seat.tracking',
        'permission'     => 'corporation.tracking',
        'highlight_view' => 'tracking',
        'route'          => 'corporation.view.tracking',
    ],
    [
        'name'           => 'ledger',
        'label'          => 'web::seat.ledger',
        'permission'     => 'corporation.journal',
        'highlight_view' => 'ledger',
        'route'          => 'corporation.view.ledger.summary',
    ],
    [
        'name'           => 'wallet',
        'label'          => 'web::seat.wallet',
        'permission'     => ['corporation.journal', 'corporation.transaction'],
        'highlight_view' => 'wallet',
        'route'          => 'corporation.view.journal',
    ],
];
