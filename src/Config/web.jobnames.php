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

// String => Class aliases to use in web routes when dispatching jobs.
return [

    'character' => [
        'assets'           => \Seat\Eveapi\Jobs\Assets\Character\Assets::class,
        'blueprints'       => \Seat\Eveapi\Jobs\Character\Blueprints::class,
        'calendar'         => [
            \Seat\Eveapi\Jobs\Calendar\Events::class,
            \Seat\Eveapi\Jobs\Calendar\Detail::class,
            \Seat\Eveapi\Jobs\Calendar\Attendees::class,
        ],
        'contacts'         => [
            \Seat\Eveapi\Jobs\Contacts\Character\Contacts::class,
            \Seat\Eveapi\Jobs\Contacts\Character\Labels::class,
        ],
        'corphistory'      => \Seat\Eveapi\Jobs\Character\CorporationHistory::class,
        'contracts'        => \Seat\Eveapi\Jobs\Contracts\Character\Contracts::class,
        'fittings'         => \Seat\Eveapi\Jobs\Fittings\Character\Fittings::class,
        'jobs'             => \Seat\Eveapi\Jobs\Industry\Character\Jobs::class,
        'killmails'        => \Seat\Eveapi\Jobs\Killmails\Character\Recent::class,
        'mail'             => [
            \Seat\Eveapi\Jobs\Mail\Labels::class,
            \Seat\Eveapi\Jobs\Mail\Mails::class,
            \Seat\Eveapi\Jobs\Mail\MailingLists::class,
        ],
        'orders'           => \Seat\Eveapi\Jobs\Market\Character\Orders::class,
        'mining'           => \Seat\Eveapi\Jobs\Industry\Character\Mining::class,
        'notifications'    => \Seat\Eveapi\Jobs\Character\Notifications::class,
        'pi'               => \Seat\Eveapi\Jobs\PlanetaryInteraction\Character\Planets::class,
        'research'         => \Seat\Eveapi\Jobs\Character\AgentsResearch::class,
        'skills'           => \Seat\Eveapi\Jobs\Skills\Character\Skills::class,
        'skillqueue'       => \Seat\Eveapi\Jobs\Skills\Character\Queue::class,
        'standings'        => \Seat\Eveapi\Jobs\Character\Standings::class,
        'journals'         => \Seat\Eveapi\Jobs\Wallet\Character\Journal::class,
        'transactions'     => \Seat\Eveapi\Jobs\Wallet\Character\Transactions::class,
    ],
    'corporation' => [
        'assets'           => \Seat\Eveapi\Jobs\Assets\Corporation\Assets::class,
        'blueprints'       => \Seat\Eveapi\Jobs\Corporation\Blueprints::class,
        'contacts'         => [
            \Seat\Eveapi\Jobs\Contacts\Corporation\Contacts::class,
            \Seat\Eveapi\Jobs\Contacts\Corporation\Labels::class,
        ],
        'contracts'        => \Seat\Eveapi\Jobs\Contracts\Corporation\Contracts::class,
        'customs_offices'  => \Seat\Eveapi\Jobs\PlanetaryInteraction\Corporation\CustomsOffices::class,
        'divisions'        => \Seat\Eveapi\Jobs\Corporation\Divisions::class,
        'jobs'             => \Seat\Eveapi\Jobs\Industry\Corporation\Jobs::class,
        'killmails'        => \Seat\Eveapi\Jobs\Killmails\Corporation\Recent::class,
        'orders'           => \Seat\Eveapi\Jobs\Market\Corporation\Orders::class,
        'members_tracking' => \Seat\Eveapi\Jobs\Corporation\MemberTracking::class,
        'standings'        => \Seat\Eveapi\Jobs\Corporation\Standings::class,
        'journals'         => \Seat\Eveapi\Jobs\Wallet\Corporation\Journals::class,
        'transactions'     => \Seat\Eveapi\Jobs\Wallet\Corporation\Transactions::class,
        'starbases'        => \Seat\Eveapi\Jobs\Corporation\Starbases::class,
        'structures'       => \Seat\Eveapi\Jobs\Corporation\Structures::class,
    ],
    'alliance' => [
        'contacts'         => [
            \Seat\Eveapi\Jobs\Contacts\Alliance\Contacts::class,
            \Seat\Eveapi\Jobs\Contacts\Alliance\Labels::class,
        ],
    ],
];
