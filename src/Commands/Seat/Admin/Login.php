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

namespace Seat\Web\Commands\Seat\Admin;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Str;
use Seat\Services\Helpers\AnalyticsContainer;
use Seat\Services\Jobs\Analytics;
use Seat\Web\Models\User;

/**
 * Class Login.
 *
 * @package Seat\Web\Commands\Seat\Admin
 */
class Login extends Command
{
    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seat:admin:login';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate an administrative login URL.';

    /**
     * Execute the console command.
     *
     * @throws \Exception
     */
    public function handle()
    {

        $this->line('SeAT Admin Login URL Generator');

        $admin = User::firstOrNew(['name' => 'admin']);

        if (! $admin->exists) {

            $this->warn('User \'admin\' does not exist. It will be created.');

            $admin->fill([
                'name'              => 'admin',
                'main_character_id' => 0,
                'admin'             => true,
            ]);
            $admin->id = 1; // Needed as id is not fillable
            $admin->save();
        }

        $this->line('Checking if \'admin\' is a super user');

        if (! $admin->isAdmin()) {

            $this->comment('Adding \'admin\' to the Superuser role');
            $admin->admin = true;
            $admin->save();
        }

        $this->line('Generating authentication token');
        $token = Str::random(32);
        cache(['admin_login_token' => $token], 60);

        $this->line('');
        $this->info('Your authentication URL is valid for 60 seconds.');
        $this->line(route('auth.admin.login', ['token' => $token]));

        // Analytics
        $this->dispatch(new Analytics((new AnalyticsContainer)
            ->set('type', 'event')
            ->set('ec', 'admin')
            ->set('ea', 'password_reset')
            ->set('el', 'console')));
    }
}
