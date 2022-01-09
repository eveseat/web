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
        'name'    => '0-corporation',
        'label'   => 'web::seat.corporation',
        'plural'  => false,
        'icon'    => 'fas fa-building',
        'entries' => [
            [
                'name'           => '0-summary',
                'label'          => 'web::seat.summary',
                'icon'           => 'fas fa-sitemap',
                'permission'     => 'corporation.summary',
                'route'          => 'seatcore::corporation.view.summary',
            ],
            [
                'name'           => '1-security',
                'label'          => 'web::seat.security',
                'icon'           => 'fas fa-user-shield',
                'permission'     => 'corporation.security',
                'route'          => 'seatcore::corporation.view.security.roles',
            ],
            [
                'name'           => '2-tracking',
                'label'          => 'web::seat.tracking',
                'icon'           => 'fas fa-users',
                'permission'     => 'corporation.tracking',
                'route'          => 'seatcore::corporation.view.tracking',
            ],
        ],
    ],
    [
        'name'    => '1-warehouse',
        'label'   => 'web::seat.warehouse',
        'plural'  => false,
        'icon'    => 'fas fa-warehouse',
        'entries' => [
            [
                'name'           => '0-assets',
                'label'          => 'web::seat.assets',
                'icon'           => 'fas fa-cubes',
                'permission'     => 'corporation.asset',
                'route'          => 'seatcore::corporation.view.assets',
            ],
            [
                'name'           => '1-customs-offices',
                'label'          => 'web::seat.customs-offices',
                'icon'           => 'fas fa-satellite',
                'permission'     => 'corporation.customs_office',
                'route'          => 'seatcore::corporation.view.customs-offices',
            ],
            [
                'name'           => '2-structure',
                'label'          => 'web::seat.upwell_structure',
                'plural'         => true,
                'icon'           => 'fab fa-fort-awesome',
                'permission'     => 'corporation.structure',
                'route'          => 'seatcore::corporation.view.structures',
            ],
            [
                'name'           => '3-starbase',
                'label'          => 'web::seat.starbase',
                'plural'         => true,
                'icon'           => 'fas fa-chess-rook',
                'permission'     => 'corporation.starbase',
                'route'          => 'seatcore::corporation.view.starbases',
            ],
        ],
    ],
    [
        'name'    => '1-industry',
        'label'   => 'web::seat.industry',
        'icon'    => 'fas fa-industry',
        'entries' => [
            [
                'name'           => '0-blueprint',
                'label'          => 'web::seat.blueprint',
                'icon'           => 'fas fa-scroll',
                'permission'     => 'corporation.blueprint',
                'route'          => 'seatcore::corporation.view.blueprint',
            ],
            [
                'name'           => '1-industry',
                'label'          => 'web::seat.jobs',
                'plural'         => false,
                'icon'           => 'fas fa-hammer',
                'permission'     => 'corporation.industry',
                'route'          => 'seatcore::corporation.view.industry',
            ],
            [
                'name'           => '2-extractions',
                'label'          => 'web::seat.extraction',
                'plural'         => true,
                'icon'           => 'fas fa-snowplow',
                'permission'     => 'corporation.extraction',
                'route'          => 'seatcore::corporation.view.extractions',
            ],
            [
                'name'           => '3-mining',
                'label'          => 'web::seat.mining',
                'icon'           => 'fas fa-gem',
                'permission'     => 'corporation.mining',
                'route'          => 'seatcore::corporation.view.mining_ledger',
            ],
        ],
    ],
    [
        'name'    => '3-finance',
        'label'   => 'web::seat.finance',
        'icon'    => 'fas fa-coins',
        'entries' => [
            [
                'name'           => '0-contracts',
                'label'          => 'web::seat.contracts',
                'icon'           => 'fas fa-file',
                'permission'     => 'corporation.contract',
                'route'          => 'seatcore::corporation.view.contracts',
            ],
            [
                'name'           => '1-market',
                'label'          => 'web::seat.market',
                'icon'           => 'fas fa-shopping-cart',
                'permission'     => 'corporation.market',
                'route'          => 'seatcore::corporation.view.market',
            ],
            [
                'name'           => '2-wallet',
                'label'          => 'web::seat.wallet',
                'icon'           => 'fas fa-money-bill-alt',
                'permission'     => ['corporation.journal', 'corporation.transaction'],
                'route'          => 'seatcore::corporation.view.journal',
            ],
            [
                'name'           => '3-ledger',
                'label'          => 'web::seat.ledger',
                'icon'           => 'fas fa-funnel-dollar',
                'permission'     => 'corporation.journal',
                'highlight_view' => 'ledger',
                'route'          => 'seatcore::corporation.view.ledger.summary',
            ],
        ],
    ],
    [
        'name'    => '4-military',
        'label'   => 'web::seat.military',
        'icon'    => 'fas fa-rocket',
        'entries' => [
            [
                'name'           => '0-contacts',
                'label'          => 'web::seat.contacts',
                'icon'           => 'fas fa-address-book',
                'permission'     => 'corporation.contact',
                'route'          => 'seatcore::corporation.view.contacts',
            ],
            [
                'name'           => '1-killmails',
                'label'          => 'web::seat.killmails',
                'icon'           => 'fas fa-crosshairs',
                'permission'     => 'corporation.killmail',
                'route'          => 'seatcore::corporation.view.killmails',
            ],
        ],
    ],
    [
        'name' => '5-other',
        'label' => 'web::seat.other',
        'entries' => [
            [
                'name'           => '0-standings',
                'label'          => 'web::seat.standings',
                'plural'         => true,
                'icon'           => 'fas fa-thumbs-up',
                'permission'     => 'corporation.standing',
                'route'          => 'seatcore::corporation.view.standings',
            ],
        ],
    ],
];
