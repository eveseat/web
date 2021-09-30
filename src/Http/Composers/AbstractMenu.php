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
use Seat\Web\Exceptions\PackageMenuBuilderException;

abstract class AbstractMenu
{
    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\Contracts\View\View  $view
     * @return mixed
     */
    abstract public function compose(View $view);

    /**
     * Load menus from any registered plugins.
     *
     * This may end up being quite a complex method, as we need
     * to validate a lot of the menu structure that is set
     * out.
     *
     * Packages should register menu items in a config file,
     * loaded in a ServiceProvider's register() method in the
     * 'package.sidebar' namespace. The structure of these
     * menus can be seen in the SeAT Wiki.
     *
     * @param  string  $package_name
     * @param  array  $menu_data
     * @param  bool  $require_affiliation
     * @return array|null
     *
     * @throws \Seat\Web\Exceptions\PackageMenuBuilderException
     */
    public function load_plugin_menu(string $package_name, array $menu_data, bool $require_affiliation = false): ?array
    {

        // Validate the package menu
        $this->validate_menu($package_name, $menu_data);

        // Check if the current user has the permission
        // required to see the menu
        if (isset($menu_data['permission'])) {
            $permissions = is_array($menu_data['permission']) ? $menu_data['permission'] : [$menu_data['permission']];

            if (! $this->userHasPermission($permissions))
                return null;
        }

        return $menu_data;
    }

    /**
     * The actual menu validation logic.
     *
     * @param  string  $package_name
     * @param  array  $menu_data
     *
     * @throws \Seat\Web\Exceptions\PackageMenuBuilderException
     */
    public function validate_menu(string $package_name, array $menu_data)
    {

        if (! is_string($package_name))
            throw new PackageMenuBuilderException(
                'Package root menu items should be named by string type');

        if (! is_array($menu_data))
            throw new PackageMenuBuilderException(
                'Package menu data should be defined in an array');

        // check if required keys are all sets
        foreach ($this->getRequiredKeys() as $key)
            if (! array_key_exists($key, $menu_data))
                throw new PackageMenuBuilderException(
                    'Menu should contain a ' . $key . ' entry');

        // Check if we have sub entries. If not, we have to
        // check if we have a route defined in the parent
        // menu.
        if (! array_key_exists('entries', $menu_data))
            if (! array_key_exists('route', $menu_data))
                throw new PackageMenuBuilderException(
                    'A parent or sub-menu route is required.');

        // Ensure that the entries is actually an array
        if (array_key_exists('entries', $menu_data) && ! is_array($menu_data['entries']))
            throw new PackageMenuBuilderException(
                'Root menu must define entries');

        // Loop over the sub menu entries, validating the
        // required fields
        if (isset($menu_data['entries'])) {

            foreach ($menu_data['entries'] as $entry) {

                if (! array_key_exists('name', $entry))
                    throw new PackageMenuBuilderException(
                        'A sub menu entry failed to define a name');

                if (! array_key_exists('icon', $entry))
                    throw new PackageMenuBuilderException(
                        'A sub menu entry failed to define an icon');

                if (! array_key_exists('route', $entry))
                    throw new PackageMenuBuilderException(
                        'A sub menu entry failed to define a route');

                if (isset($entry['entries'])) {

                    foreach ($entry['entries'] as $subentry) {

                        if (! array_key_exists('name', $subentry))
                            throw new PackageMenuBuilderException(
                                'A sub menu entry failed to define a name');

                        if (! array_key_exists('icon', $subentry))
                            throw new PackageMenuBuilderException(
                                'A sub menu entry failed to define an icon');

                        if (! array_key_exists('route', $subentry))
                            throw new PackageMenuBuilderException(
                                'A sub menu entry failed to define a route');

                    }

                }
            }
        }
    }

    /**
     * Return required keys in menu structure.
     *
     * @return array
     */
    abstract public function getRequiredKeys(): array;

    /**
     * Return true if the current user can see menu entry.
     *
     * @param  array  $permissions
     * @return bool
     */
    abstract protected function userHasPermission(array $permissions): bool;
}
