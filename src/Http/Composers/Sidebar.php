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

namespace Seat\Web\Http\Composers;

use Illuminate\Contracts\View\View;

/**
 * Class User.
 * @package Seat\Web\Http\Composers
 */
class Sidebar extends AbstractMenu
{
    /**
     * Return required keys in menu structure.
     *
     * @return array
     */
    public function getRequiredKeys(): array
    {

        return [
            'name', 'route_segment',
        ];
    }

    /**
     * Bind data to the view.
     *
     * @param  View $view
     */
    public function compose(View $view)
    {

        // Grab the menu and sort it.
        $menu = config('package.sidebar');
        ksort($menu);

        // Return the sidebar with the loaded packages menus
        $view->with('menu', collect($menu)->map(function ($menu_data, $package_name) {

            return $this->load_plugin_menu($package_name, $menu_data);

        })->filter(function ($entry) {

            // Clean out empty entries that may appear as a result of
            // permissions not being granted.
            if (! is_null($entry))
                return $entry;
        }));
    }
}
