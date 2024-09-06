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

namespace Seat\Web\Commands\Seat\Filters;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Seat\Web\Jobs\UpdateCharacterFilters;

class Update extends Command
{
    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seat:filters:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs squad and character scheduling rule updates';

    /**
     * Run the command.
     *
     * @return void
     */
    public function handle(): void
    {
        UpdateCharacterFilters::dispatch()->onQueue('high');
        $this->line('Scheduled character filter updates for all characters!');
    }
}
