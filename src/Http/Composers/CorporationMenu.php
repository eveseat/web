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

namespace Seat\Web\Http\Composers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;

/**
 * Class CorporationMenu.
 * @package Seat\Web\Http\Composers
 */
class CorporationMenu extends AbstractMenu
{
    /**
     * @var \Seat\Eveapi\Models\Corporation\CorporationInfo
     */
    private $corporation;

    /**
     * Create a new sidebar composer.
     */
    public function __construct()
    {
        $this->corporation = request()->corporation;
    }

    /**
     * Return required keys in menu structure.
     *
     * @return array
     */
    public function getRequiredKeys(): array
    {

        return [
            'name', 'permission', 'highlight_view', 'route',
        ];
    }

    /**
     * Bind data to the view.
     *
     * @param  View $view
     *
     * @return void
     * @throws \Seat\Web\Exceptions\PackageMenuBuilderException
     */
    public function compose(View $view)
    {

        // This menu item declares the menu and
        // sets it as an array of arrays.
        $menu = [];

        // Load any package menus
        if (! empty(config('package.corporation.menu'))) {
            foreach (config('package.corporation.menu') as $menu_data) {
                $prepared_menu = $this->load_plugin_menu('corporation', $menu_data, true);
                if (! is_null($prepared_menu))
                    array_push($menu, $prepared_menu);
            }
        }

        // Sort the menu alphabetically.
        $menu = array_values(Arr::sort($menu, function ($value) {

            return $value['name'];
        }));

        $view->with('menu', $menu);
    }

    /**
     * Return true if the current user can see menu entry.
     *
     * @param array $permissions
     * @return bool
     */
    protected function userHasPermission(array $permissions): bool
    {
        return Gate::any($permissions, $this->corporation);
    }
}
