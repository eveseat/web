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

namespace Seat\Web\Http\Composers;

use Exception;
use Seat\Web\Contracts\Dashboard;
use Seat\Web\Exceptions\InvalidDashboardException;
use Seat\Web\Http\Composers\Dashboards\CharacterDashboard;

/**
 * Class DashboardManager.
 * @package Seat\Web\Http\Composers
 */
class DashboardManager
{
    /**
     * @var \Seat\Web\Contracts\Dashboard
     */
    private $dashboard;

    public function __construct()
    {
        //setting(['dashboard', CharacterDashboard::class]); // TODO : implement menu list

        try {
            $this->dashboard = new (setting('dashboard') ?: CharacterDashboard::class);
        } catch (Exception $e) {
            $this->dashboard = new (CharacterDashboard::class);

            logger()->warning('An error occurred while retrieving dashboard settings.', [
                'message' => $e->getMessage(),
                'user' => auth()->user()->getAuthIdentifierName(),
            ]);
        }
    }

    /**
     * @return \BladeView|false|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @throws \Seat\Services\Exceptions\SettingException|\Seat\Web\Exceptions\InvalidDashboardException
     */
    public function render()
    {
        if (! $this->dashboard instanceof Dashboard)
            throw new InvalidDashboardException();

        // Warn if the admin contact has not been set yet.
        if (auth()->user()->isAdmin())
            if (setting('admin_contact', true) === 'seatadmin@localhost.local')
                session()->flash('warning', trans('web::seat.admin_contact_warning'));

        $merged_data = array_merge(['dashboard' => $this->dashboard->blade()], $this->dashboard->data());

        return view('web::home', $merged_data);
    }
}
