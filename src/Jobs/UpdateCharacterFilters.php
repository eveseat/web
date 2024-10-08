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

namespace Seat\Web\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Web\Events\CharacterFilterDataUpdate;

class UpdateCharacterFilters implements ShouldQueue
{
    use Queueable, InteractsWithQueue, Dispatchable;

    public function tags()
    {
        return ['web', 'filters'];
    }

    /**
     * Go over all character and trigger a character filter update.
     *
     * @return void
     */
    public function handle()
    {
        // Without chunking, we can run out of memory on large installs.
        CharacterInfo::with('user.squads')->chunk(5, function ($characters) {
            foreach ($characters as $character){
                event(new CharacterFilterDataUpdate($character));
            }
        });
    }
}
