<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2021 Leon Jacobs
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
    'pool-1' => [
        'connection'   => 'redis',
        'queue'        => ['default', 'high', 'characters', 'corporations', 'public', 'notifications'],
        'balance'      => 'auto', // valid values are simple, auto or false
        'tries'        => 3,
        'timeout'      => 900, // 15 minutes,
        // the minimum workers which must be spawn per queue in the pool - in auto balancing mode
        // this is the worker amount set on an idle queue
        // this value must be greater than 0
        'minProcesses' => 1,
        // the maximum workers which must be spawned by the pool - in auto balancing mode
        'maxProcesses' => (int) env('QUEUE_WORKERS', 2),
        // the processes which must be assigned - in simple mode
        // 'processes'    => (int) env('QUEUE_WORKERS', 6),
    ],
];
