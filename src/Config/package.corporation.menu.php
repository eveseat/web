<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to present Leon Jacobs
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
        'name' => 'assets',
        'label' => 'web::seat.assets',
        'permission' => 'corporation.asset',
        'highlight_view' => 'assets',
        'route' => 'seatcore::corporation.view.assets',
    ],
    [
        'name' => 'contacts',
        'label' => 'web::seat.contacts',
        'permission' => 'corporation.contact',
        'highlight_view' => 'contacts',
        'route' => 'seatcore::corporation.view.contacts',
    ],
    [
        'name' => 'contracts',
        'label' => 'web::seat.contracts',
        'permission' => 'corporation.contract',
        'highlight_view' => 'contracts',
        'route' => 'seatcore::corporation.view.contracts',
    ],
    [
        'name' => 'extractions',
        'label' => 'web::seat.extraction',
        'plural' => true,
        'permission' => 'corporation.extraction',
        'highlight_view' => 'extractions',
        'route' => 'seatcore::corporation.view.extractions',
    ],
    [
        'name' => 'blueprint',
        'label' => 'web::seat.blueprint',
        'permission' => 'corporation.blueprint',
        'highlight_view' => 'blueprint',
        'route' => 'seatcore::corporation.view.blueprint',
    ],
    [
        'name' => 'industry',
        'label' => 'web::seat.industry',
        'permission' => 'corporation.industry',
        'highlight_view' => 'industry',
        'route' => 'seatcore::corporation.view.industry',
    ],
    [
        'name' => 'killmails',
        'label' => 'web::seat.killmails',
        'permission' => 'corporation.killmail',
        'highlight_view' => 'killmails',
        'route' => 'seatcore::corporation.view.killmails',
    ],
    [
        'name' => 'market',
        'label' => 'web::seat.market',
        'permission' => 'corporation.market',
        'highlight_view' => 'market',
        'route' => 'seatcore::corporation.view.market',
    ],
    [
        'name' => 'mining',
        'label' => 'web::seat.mining',
        'permission' => 'corporation.mining',
        'highlight_view' => 'mining',
        'route' => 'seatcore::corporation.view.mining_ledger',
    ],
    [
        'name' => 'customs-offices',
        'label' => 'web::seat.customs-offices',
        'permission' => 'corporation.customs_office',
        'highlight_view' => 'customs-offices',
        'route' => 'seatcore::corporation.view.customs-offices',
    ],
    [
        'name' => 'security',
        'label' => 'web::seat.security',
        'permission' => 'corporation.security',
        'highlight_view' => 'security',
        'route' => 'seatcore::corporation.view.security.roles',
    ],
    [
        'name' => 'starbase',
        'label' => 'web::seat.starbase',
        'plural' => true,
        'permission' => 'corporation.starbase',
        'highlight_view' => 'starbases',
        'route' => 'seatcore::corporation.view.starbases',
    ],
    [
        'name' => 'structure',
        'label' => 'web::seat.structure',
        'plural' => true,
        'permission' => 'corporation.structure',
        'highlight_view' => 'structures',
        'route' => 'seatcore::corporation.view.structures',
    ],
    [
        'name' => 'summary',
        'label' => 'web::seat.summary',
        'permission' => 'corporation.summary',
        'highlight_view' => 'summary',
        'route' => 'seatcore::corporation.view.summary',
    ],
    [
        'name' => 'standings',
        'label' => 'web::seat.standings',
        'plural' => true,
        'permission' => 'corporation.standing',
        'highlight_view' => 'standings',
        'route' => 'seatcore::corporation.view.standings',
    ],
    [
        'name' => 'tracking',
        'label' => 'web::seat.tracking',
        'permission' => 'corporation.tracking',
        'highlight_view' => 'tracking',
        'route' => 'seatcore::corporation.view.tracking',
    ],
    [
        'name' => 'ledger',
        'label' => 'web::seat.ledger',
        'permission' => 'corporation.journal',
        'highlight_view' => 'ledger',
        'route' => 'seatcore::corporation.view.ledger.summary',
    ],
    [
        'name' => 'wallet',
        'label' => 'web::seat.wallet',
        'permission' => ['corporation.journal', 'corporation.transaction'],
        'highlight_view' => 'wallet',
        'route' => 'seatcore::corporation.view.journal',
    ],
];
