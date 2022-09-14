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
        'name'    => '0-character',
        'label'   => 'web::seat.character',
        'plural'  => false,
        'icon'    => 'fas fa-user-tie',
        'entries' => [
            [
                'name'       => '0-sheet',
                'label'      => 'web::seat.sheet',
                'icon'       => 'fas fa-address-card',
                'permission' => 'character.sheet',
                'route'      => 'seatcore::character.view.sheet',
            ],
            [
                'name'       => '1-skills',
                'label'      => 'web::seat.skills',
                'icon'       => 'fas fa-graduation-cap',
                'permission' => 'character.skill',
                'route'      => 'seatcore::character.view.skills',
            ],
        ],
    ],
    [
        'name'           => '1-assets',
        'label'          => 'web::seat.assets',
        'icon'           => 'fas fa-cubes',
        'permission'     => 'character.asset',
        'route'          => 'seatcore::character.view.assets',
    ],
    [
        'name'    => '2-industry',
        'label'   => 'web::seat.industry',
        'icon'    => 'fas fa-industry',
        'entries' => [
            [
                'name'           => '0-blueprint',
                'label'          => 'web::seat.blueprint',
                'icon'           => 'fas fa-scroll',
                'permission'     => 'character.blueprint',
                'route'          => 'seatcore::character.view.blueprint',
            ],
            [
                'name'           => '1-jobs',
                'label'          => 'web::seat.jobs',
                'plural'         => false,
                'icon'           => 'fas fa-hammer',
                'permission'     => 'character.industry',
                'route'          => 'seatcore::character.view.industry',
            ],
            [
                'name'           => '2-mining',
                'label'          => 'web::seat.mining',
                'icon'           => 'fas fa-gem',
                'permission'     => 'character.mining',
                'route'          => 'seatcore::character.view.mining_ledger',
            ],
            [
                'name'           => '3-pi',
                'label'          => 'web::seat.pi',
                'icon'           => 'fas fa-globe',
                'permission'     => 'character.planetary',
                'route'          => 'seatcore::character.view.pi',
            ],
            [
                'name'           => '4-research',
                'label'          => 'web::seat.research',
                'icon'           => 'fas fa-microscope',
                'permission'     => 'character.research',
                'route'          => 'seatcore::character.view.research',
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
                'permission'     => 'character.contract',
                'route'          => 'seatcore::character.view.contracts',
            ],
            [
                'name'           => '1-market',
                'label'          => 'web::seat.market',
                'icon'           => 'fas fa-shopping-cart',
                'permission'     => 'character.market',
                'route'          => 'seatcore::character.view.market',
            ],
            [
                'name'           => '2-loyalty-points',
                'label'          => 'web::seat.loyalty_points',
                'icon'           => 'fas fa-crown',
                'permission'     => ['character.loyalty_points'],
                'highlight_view' => 'loyalty-points',
                'route'          => 'character.view.loyalty_points',
            ],
            [
                'name'           => '3-wallet',
                'label'          => 'web::seat.wallet',
                'icon'           => 'fas fa-money-bill-alt',
                'entries'        => [
                    [
                        'name'       => '0-journal',
                        'label'      => 'web::seat.journal',
                        'icon'       => 'fas fa-book',
                        'permission' => 'character.journal',
                        'route'      => 'seatcore::character.view.journal',
                    ],
                    [
                        'name'       => '1-transaction',
                        'label'      => 'web::seat.transaction',
                        'icon'       => 'fas fa-cash-register',
                        'permission' => 'character.transaction',
                        'route'      => 'seatcore::character.view.transactions',
                    ],
                ],
            ],
        ],
    ],
    [
        'name'    => '4-military',
        'label'   => 'web::seat.military',
        'icon'    => 'fas fa-rocket',
        'entries' => [
            [
                'name'           => '0-fittings',
                'label'          => 'web::seat.fittings',
                'icon'           => 'fas fa-tools',
                'permission'     => 'character.fitting',
                'route'          => 'seatcore::character.view.fittings',
            ],
            [
                'name'           => '1-killmails',
                'label'          => 'web::seat.killmails',
                'icon'           => 'fas fa-crosshairs',
                'permission'     => 'character.killmail',
                'route'          => 'seatcore::character.view.killmails',
            ],
        ],
    ],
    [
        'name'  => '5-other',
        'label' => 'web::seat.other',
        'icon'  => 'fas fa-random',
        'entries' => [
            [
                'name'           => '0-calendar',
                'label'          => 'web::seat.calendar',
                'icon'           => 'fas fa-calendar-alt',
                'permission'     => 'character.calendar',
                'route'          => 'seatcore::character.view.calendar',
            ],
            [
                'name'           => '1-contacts',
                'label'          => 'web::seat.contacts',
                'icon'           => 'fas fa-address-book',
                'permission'     => 'character.contact',
                'route'          => 'seatcore::character.view.contacts',
            ],
            [
                'name'           => '2-intel',
                'label'          => 'web::seat.intel',
                'icon'           => 'fas fa-user-secret',
                'entries'        => [
                    [
                        'name'       => '0-summary',
                        'label'      => 'web::seat.summary',
                        'icon'       => 'fas fa-stream',
                        'permission' => 'character.intel',
                        'route'      => 'seatcore::character.view.intel.summary',
                    ],
                    [
                        'name'       => '1-compare',
                        'label'      => 'web::seat.standing-compare',
                        'icon'       => 'fas fa-not-equal',
                        'permission' => 'character.intel',
                        'route'      => 'seatcore::character.view.intel.standingscomparison',
                    ],
                    [
                        'name'       => '2-notes',
                        'label'      => 'web::seat.note',
                        'plural'     => true,
                        'icon'       => 'fas fa-sticky-note',
                        'permission' => 'character.intel',
                        'route'      => 'seatcore::character.view.intel.notes',
                    ],
                ],
            ],
            [
                'name'           => '3-mail',
                'label'          => 'web::seat.mail',
                'icon'           => 'fas fa-envelope',
                'permission'     => 'character.mail',
                'route'          => 'seatcore::character.view.mail',
            ],
            [
                'name'           => '4-notifications',
                'label'          => 'web::seat.notifications',
                'icon'           => 'fas fa-bell',
                'permission'     => 'character.notification',
                'route'          => 'seatcore::character.view.notifications',
            ],
            [
                'name'           => '5-standings',
                'label'          => 'web::seat.standings',
                'plural'         => true,
                'icon'           => 'fas fa-thumbs-up',
                'permission'     => 'character.standing',
                'route'          => 'seatcore::character.view.standings',
            ],
        ],
    ],
    [
        'name'       => '6-monitoring',
        'label'      => 'web::seat.monitoring',
        'icon'       => 'fas fa-heartbeat',
        'permission' => 'global.queue_manager',
        'route'      => 'seatcore::character.view.monitoring',
    ]
];
