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
    '0home'       => [
        'name'          => 'home',
        'label'         => 'web::seat.home',
        'icon'          => 'fa-home',
        'route_segment' => 'home',
        'route'         => 'home',
    ],
    'corporation' => [
        'name'          => 'corporation',
        'label'         => 'web::seat.corporation',
        'plural'        => true,
        'icon'          => 'fa-building',
        'route_segment' => 'corporation',
        'entries'       => [
            [
                'name'  => 'all corporations',
                'label' => 'web::seat.all_corp',
                'icon'  => 'fa-group',
                'route' => 'corporation.list',
            ],
        ],
    ],
    'character'   => [
        'name'          => 'character',
        'label'         => 'web::seat.character',
        'plural'        => true,
        'icon'          => 'fa-user',
        'route_segment' => 'character',
        'entries'       => [
            [
                'name'  => 'all characters',
                'label' => 'web::seat.all_char',
                'icon'  => 'fa-group',
                'route' => 'character.list',
            ],
            [
                'name'  => 'mail timeline',
                'label' => 'web::seat.mail_timeline',
                'icon'  => 'fa-envelope',
                'route' => 'character.view.mail.timeline',
            ],
        ],
    ],
    'settings'    => [
        'name'          => 'configuration',
        'label'         => 'web::seat.configuration',
        'permission'    => 'superuser',
        'icon'          => 'fa-cogs',
        'route_segment' => 'configuration',
        'entries'       => [

            [   // Access
                'name'  => 'access',
                'label' => 'web::seat.access',
                'icon'  => 'fa-shield',
                'route' => 'configuration.access.roles',
            ],
            [   // Users
                'name'   => 'user',
                'plural' => true,
                'label'  => 'web::seat.user',
                'icon'   => 'fa-user',
                'route'  => 'configuration.users',
            ],
            [   // Schedule
                'name'  => 'schedule',
                'label' => 'web::seat.schedule',
                'icon'  => 'fa-clock-o',
                'route' => 'configuration.schedule',
            ],
            [   // Sso
                'name'  => 'sso',
                'label' => 'web::seat.sso',
                'icon'  => 'fa-user-circle',
                'route' => 'configuration.sso',
            ],
            [   // SeAT Setting
                'name'  => 'settings',
                'label' => 'web::seat.settings',
                'icon'  => 'fa-cog',
                'route' => 'seat.settings.view',
            ],
            [   // Security
                'name'  => 'security logs',
                'label' => 'web::seat.security_logs',
                'icon'  => 'fa-list',
                'route' => 'configuration.security.logs',
            ],
            [
                // About
                'name'  => 'about',
                'label' => 'web::seat.about',
                'icon'  => 'fa-info',
                'route' => 'seat.settings.about',
            ],
        ],
    ],
    'tools'       => [
        'name'          => 'tools',
        'label'         => 'web::seat.tools',
        'icon'          => 'fa-wrench',
        'route_segment' => 'tools',
        'entries'       => [
            [
                'name'  => 'standing profiles',
                'label' => 'web::seat.standings_builder',
                'icon'  => 'fa-dot-circle-o',
                'route' => 'tools.standings',
            ],
        ],
    ],
];
