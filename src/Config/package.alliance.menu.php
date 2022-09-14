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
        'name'    => '0-alliance',
        'label'   => 'web::seat.alliance',
        'plural'  => false,
        'icon'    => 'fas fa-city',
        'entries' => [
            [
                'name'       => '0-summary',
                'label'      => 'web::seat.summary',
                'icon'       => 'fas fa-passport',
                'permission' => 'alliance.summary',
                'route'      => 'seatcore::alliance.view.summary',
            ],
            [
                'name'       => '2-tracking',
                'label'      => 'web::seat.tracking',
                'icon'       => 'fas fa-user-shield',
                'permission' => 'alliance.tracking',
                'route'      => 'seatcore::alliance.view.tracking',
            ],
        ],
    ],
    [
        'name'    => '1-military',
        'label'   => 'web::seat.military',
        'icon'    => 'fas fa-rocket',
        'entries' => [
            [
                'name'       => '0-contacts',
                'label'      => 'web::seat.contacts',
                'icon'       => 'fas fa-address-book',
                'permission' => 'alliance.contact',
                'route'      => 'seatcore::alliance.view.contacts',
            ],
        ],
    ],
    [
        'name'       => '6-monitoring',
        'label'      => 'web::seat.monitoring',
        'icon'       => 'fas fa-heartbeat',
        'permission' => 'global.queue_manager',
        'route'      => 'seatcore::alliance.view.monitoring',
    ],
];
