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

// String => Class aliases to use in web routes when dispatching jobs.
return [

    'character' => [
        'assets'        => \Seat\Eveapi\Jobs\Assets\Character\Assets::class,
        'bookmarks'     => [
            \Seat\Eveapi\Jobs\Bookmarks\Character\Bookmarks::class,
            \Seat\Eveapi\Jobs\Bookmarks\Character\Folders::class,
        ],
        'calendar'      => [
            \Seat\Eveapi\Jobs\Calendar\Events::class,
            \Seat\Eveapi\Jobs\Calendar\Detail::class,
            \Seat\Eveapi\Jobs\Calendar\Attendees::class,
        ],
        'contacts'      => [
            \Seat\Eveapi\Jobs\Contacts\Character\Contacts::class,
            \Seat\Eveapi\Jobs\Contacts\Character\Labels::class,
        ],
        'corphistory'   => \Seat\Eveapi\Jobs\Character\CorporationHistory::class,
        'contracts'     => [
            \Seat\Eveapi\Jobs\Contracts\Character\Contracts::class,
            \Seat\Eveapi\Jobs\Contracts\Character\Bids::class,
            \Seat\Eveapi\Jobs\Contracts\Character\Items::class,
        ],
        'fittings'      => \Seat\Eveapi\Jobs\Fittings\Character\Fittings::class,
        'industry'      => \Seat\Eveapi\Jobs\Industry\Character\Jobs::class,
        'killmails'     => [
            \Seat\Eveapi\Jobs\Killmails\Character\Recent::class,
            \Seat\Eveapi\Jobs\Killmails\Character\Detail::class,
        ],
        'mail'          => [
            \Seat\Eveapi\Jobs\Mail\Headers::class,
            \Seat\Eveapi\Jobs\Mail\Bodies::class,
            \Seat\Eveapi\Jobs\Mail\Labels::class,
            \Seat\Eveapi\Jobs\Mail\MailingLists::class,
        ],
        'market'        => \Seat\Eveapi\Jobs\Market\Character\Orders::class,
        'mining'        => \Seat\Eveapi\Jobs\Industry\Character\Mining::class,
        'notifications' => \Seat\Eveapi\Jobs\Character\Notifications::class,
        'pi'            => [
            \Seat\Eveapi\Jobs\PlanetaryInteraction\Character\Planets::class,
            \Seat\Eveapi\Jobs\PlanetaryInteraction\Character\PlanetDetail::class,
        ],
        'research'      => \Seat\Eveapi\Jobs\Character\AgentsResearch::class,
        'skills'        => \Seat\Eveapi\Jobs\Skills\Character\Skills::class,
        'skillqueue'    => \Seat\Eveapi\Jobs\Skills\Character\Queue::class,
        'wallet'        => [
            \Seat\Eveapi\Jobs\Wallet\Character\Journal::class,
            \Seat\Eveapi\Jobs\Wallet\Character\Transactions::class,
        ],
    ],
];
