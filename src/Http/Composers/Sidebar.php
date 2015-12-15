<?php
/*
This file is part of SeAT

Copyright (C) 2015  Leon Jacobs

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/

namespace Seat\Web\Http\Composers;

use Illuminate\Contracts\View\View;
use Seat\Web\Exceptions\PackageMenuBuilderException;

/**
 * Class User
 * @package Seat\Web\Http\Composers
 */
class Sidebar
{

    /**
     * Create a new sidebar composer.
     */
    public function __construct()
    {
        //
    }

    /**
     * Bind data to the view.
     *
     * @param  View $view
     *
     * @return void
     */
    public function compose(View $view)
    {

        // Home. This menu item declares the menu and
        // sets it as an array of arrays.
        $menu = [
            [
                'name'          => trans('web::sidebar.home'),
                'icon'          => 'fa-home',
                'route_segment' => 'home',
                'route'         => 'home'
            ]
        ];

        // Key Management
        array_push($menu, [

            'name'          => trans('web::sidebar.key_management'),
            'icon'          => 'fa-key',
            'route_segment' => 'api-key',
            'entries'       => [

                [   // Add Api Key
                    'name'  => trans('web::sidebar.add_api_key'),
                    'icon'  => 'fa-plus',
                    'route' => 'api.key'
                ],
                [
                    'name'  => trans('web::sidebar.list_keys'),
                    'icon'  => 'fa-list',
                    'route' => 'api.key.list'
                ]
            ]
        ]);

        // Corporation
        array_push($menu, [
            'name'          => trans('web::sidebar.corporations'),
            'icon'          => 'fa-building',
            'route_segment' => 'corporation',
            'entries'       => [
                [
                    'name'  => trans('web::sidebar.all_corp'),
                    'icon'  => 'fa-group',
                    'route' => 'corporation.list'
                ]
            ]
        ]);

        // Character
        array_push($menu, [
            'name'          => trans('web::sidebar.characters'),
            'icon'          => 'fa-user',
            'route_segment' => 'character',
            'entries'       => [
                [
                    'name'  => trans('web::sidebar.all_char'),
                    'icon'  => 'fa-group',
                    'route' => 'character.list'
                ],
                [
                    'name'  => trans('web::sidebar.mail_timeline'),
                    'icon'  => 'fa-envelope',
                    'route' => 'character.view.mail.timeline'
                ]
            ]
        ]);

        // Configuration
        if (auth()->user()->hasSuperuser()) {

            array_push($menu, [
                'name'          => trans('web::sidebar.configuration'),
                'icon'          => 'fa-cogs',
                'route_segment' => 'configuration',
                'entries'       => [

                    [   // Access
                        'name'  => trans('web::sidebar.access'),
                        'icon'  => 'fa-shield',
                        'route' => 'configuration.access.roles'
                    ],
                    [   // Import
                        'name'  => trans('web::sidebar.import'),
                        'icon'  => 'fa-upload',
                        'route' => 'configuration.import.list'
                    ],
                    [   // Users
                        'name'  => trans('web::sidebar.users'),
                        'icon'  => 'fa-user',
                        'route' => 'configuration.users'
                    ],
                    [   // Schedule
                        'name'  => trans('web::sidebar.schedule'),
                        'icon'  => 'fa-clock-o',
                        'route' => 'configuration.schedule'
                    ],
                    [   // SeAT Setting
                        'name'  => trans('web::sidebar.settings'),
                        'icon'  => 'fa-cog',
                        'route' => 'seat.settings.view'
                    ],
                    [   // Security
                        'name'  => trans('web::sidebar.security_logs'),
                        'icon'  => 'fa-list',
                        'route' => 'configuration.security.logs'
                    ],

                ]
            ]);
        }

        // Load any menus from any registered packages
        $package_menus = config('package.sidebar');
        foreach ($package_menus as $package_name => $menu_data) {

            $prepared_menu = $this->load_plugin_menu($package_name, $menu_data);

            if (!empty($prepared_menu))
                array_push($menu, $prepared_menu);
        }

        array_push($menu, [
            'name'          => trans('web::sidebar.other'),
            'icon'          => 'fa-circle',
            'route_segment' => 'other',
        ]);

        $view->with('menu', $menu);
    }

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
     * @param $package_name
     * @param $menu_data
     *
     * @return array
     * @throws \Seat\Web\Exceptions\PackageMenuBuilderException
     */
    public function load_plugin_menu($package_name, $menu_data)
    {

        // Validate the package menu
        $this->validate_menu($package_name, $menu_data);

        // Check if the current user has the permission
        // required to see the menu
        if (isset($menu_data['permission']))
            if (!auth()->user()->has($menu_data['permission']))
                return;

        return $menu_data;
    }

    /**
     * The actual menu valiation logic.
     *
     * @param $package_name
     * @param $menu_data
     *
     * @throws \Seat\Web\Exceptions\PackageMenuBuilderException
     */
    public function validate_menu($package_name, $menu_data)
    {

        if (!is_string($package_name))
            throw new PackageMenuBuilderException(
                'Package root menu items should be named by string type');

        if (!is_array($menu_data))
            throw new PackageMenuBuilderException(
                'Package menu data should be defined in an array');

        if (!array_key_exists('name', $menu_data))
            throw new PackageMenuBuilderException(
                'Root menu must define a name');

        if (!array_key_exists('route_segment', $menu_data))
            throw new PackageMenuBuilderException(
                'Root menu must define a route segement');

        // Check if we have sub entries. If not, we have to
        // check if we have a route defined in the parent
        // menu.
        if (!array_key_exists('entries', $menu_data))
            if (!array_key_exists('route', $menu_data))
                throw new PackageMenuBuilderException(
                    'A parent or sub-menu route is required.');

        // Ensure that the entries is actually an array
        if (array_key_exists('entries', $menu_data) && !is_array($menu_data['entries']))
            throw new PackageMenuBuilderException(
                'Root menu must define entries');

        // Loop over the sub menu entries, validating the
        // required fields
        if (isset($menu_data['entries'])) {

            foreach ($menu_data['entries'] as $entry) {

                if (!array_key_exists('name', $entry))
                    throw new PackageMenuBuilderException(
                        'A sub menu entry failed to define a name');

                if (!array_key_exists('icon', $entry))
                    throw new PackageMenuBuilderException(
                        'A sub menu entry failed to define an icon');

                if (!array_key_exists('route', $entry))
                    throw new PackageMenuBuilderException(
                        'A sub menu entry failed to define a route');
            }
        }
    }
}
