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
 * Class CorporationMenu
 * @package Seat\Web\Http\Composers
 */
class CorporationMenu
{

    /**
     * The keys required to build a menu
     *
     * @var array
     */
    protected $required_keys = [
        'name', 'permission', 'highlight_view', 'route'];

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

        // This menu item declares the menu and
        // sets it as an array of arrays.
        $menu = [
            [
                'name'           => trans('web::seat.assets'),
                'permission'     => 'corporation.assets',
                'highlight_view' => 'assets',
                'route'          => 'corporation.view.assets'
            ],
            [
                'name'           => trans_choice('web::seat.bookmark', 2),
                'permission'     => 'corporation.bookmarks',
                'highlight_view' => 'bookmarks',
                'route'          => 'corporation.view.bookmarks'
            ],
            [
                'name'           => trans('web::seat.contacts'),
                'permission'     => 'corporation.contacts',
                'highlight_view' => 'contacts',
                'route'          => 'corporation.view.contacts'
            ],
            [
                'name'           => trans('web::seat.contracts'),
                'permission'     => 'corporation.contracts',
                'highlight_view' => 'contracts',
                'route'          => 'corporation.view.contracts'
            ],
            [
                'name'           => trans('web::seat.industry'),
                'permission'     => 'corporation.industry',
                'highlight_view' => 'industry',
                'route'          => 'corporation.view.industry'
            ],
            [
                'name'           => trans('web::seat.killmails'),
                'permission'     => 'corporation.killmails',
                'highlight_view' => 'killmails',
                'route'          => 'corporation.view.killmails'
            ],
            [
                'name'           => trans('web::seat.market'),
                'permission'     => 'corporation.market',
                'highlight_view' => 'market',
                'route'          => 'corporation.view.market'
            ],
            [
                'name'           => trans('web::seat.pocos'),
                'permission'     => 'corporation.pocos',
                'highlight_view' => 'pocos',
                'route'          => 'corporation.view.pocos'
            ],
            [
                'name'           => trans('web::seat.security'),
                'permission'     => 'corporation.security',
                'highlight_view' => 'security',
                'route'          => 'corporation.view.security.roles'
            ],
            [
                'name'           => trans_choice('web::seat.starbase', 2),
                'permission'     => 'corporation.starbases',
                'highlight_view' => 'starbases',
                'route'          => 'corporation.view.starbases'
            ],
            [
                'name'           => trans('web::seat.summary'),
                'permission'     => 'corporation.summary',
                'highlight_view' => 'summary',
                'route'          => 'corporation.view.summary'
            ],
            [
                'name'           => trans('web::seat.standings'),
                'permission'     => 'corporation.standings',
                'highlight_view' => 'standings',
                'route'          => 'corporation.view.standings'
            ],
            [
                'name'           => trans('web::seat.tracking'),
                'permission'     => 'corporation.tracking',
                'highlight_view' => 'tracking',
                'route'          => 'corporation.view.tracking'
            ],
            [
                'name'           => trans('web::seat.wallet_journal'),
                'permission'     => 'corporation.journal',
                'highlight_view' => 'journal',
                'route'          => 'corporation.view.journal'
            ],
            [
                'name'           => trans('web::seat.wallet_transactions'),
                'permission'     => 'corporation.transactions',
                'highlight_view' => 'transactions',
                'route'          => 'corporation.view.transactions'
            ],

        ];

        // Load any package menus
        if (!empty(config('package.corporation.menu'))) {

            foreach (config('package.corporation.menu') as $menu_data) {

                $prepared_menu = $this->load_plugin_menu($menu_data);
                array_push($menu, $prepared_menu);
            }
        }

        // Sort the menu alphabetically.
        $menu = array_values(array_sort($menu, function ($value) {

            return $value['name'];
        }));

        $view->with('menu', $menu);
    }

    /**
     * Load menus from any registered plugins.
     *
     * Packages should register menu items in a config file,
     * loaded in a ServiceProvider's register() method in the
     * 'package.corporation.menu' namespace. The structure of these
     * menus can be seen in the SeAT Wiki.
     *
     * @param $menu_data
     *
     * @return array
     * @throws \Seat\Web\Exceptions\PackageMenuBuilderException
     */
    public function load_plugin_menu($menu_data)
    {

        // Validate the package menu
        if (count(array_diff_key($menu_data, $this->required_keys)) == 0)
            throw new PackageMenuBuilderException(
                'Not all keys for a corporation menu is set!');

        return $menu_data;

    }
}
