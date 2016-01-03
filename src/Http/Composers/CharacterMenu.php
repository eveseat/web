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
class CharacterMenu
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
                'permission'     => 'character.assets',
                'highlight_view' => 'assets',
                'route'          => 'character.view.assets'
            ],
            [
                'name'           => trans_choice('web::seat.bookmark', 2),
                'permission'     => 'character.bookmarks',
                'highlight_view' => 'bookmarks',
                'route'          => 'character.view.bookmarks'
            ],
            [
                'name'           => trans('web::seat.calendar'),
                'permission'     => 'character.calendar',
                'highlight_view' => 'calendar',
                'route'          => 'character.view.calendar'
            ],
            [
                'name'           => trans('web::seat.channels'),
                'permission'     => 'character.channels',
                'highlight_view' => 'channels',
                'route'          => 'character.view.channels'
            ],
            [
                'name'           => trans('web::seat.contacts'),
                'permission'     => 'character.contacts',
                'highlight_view' => 'contacts',
                'route'          => 'character.view.contacts'
            ],
            [
                'name'           => trans('web::seat.contracts'),
                'permission'     => 'character.contracts',
                'highlight_view' => 'contracts',
                'route'          => 'character.view.contracts'
            ],
            [
                'name'           => trans('web::seat.industry'),
                'permission'     => 'character.industry',
                'highlight_view' => 'industry',
                'route'          => 'character.view.industry'
            ],
            [
                'name'           => trans('web::seat.killmails'),
                'permission'     => 'character.killmails',
                'highlight_view' => 'killmails',
                'route'          => 'character.view.killmails'
            ],
            [
                'name'           => trans('web::seat.mail'),
                'permission'     => 'character.mail',
                'highlight_view' => 'mail',
                'route'          => 'character.view.mail'
            ],
            [
                'name'           => trans('web::seat.market'),
                'permission'     => 'character.market',
                'highlight_view' => 'market',
                'route'          => 'character.view.market'
            ],
            [
                'name'           => trans('web::seat.notifications'),
                'permission'     => 'character.notifications',
                'highlight_view' => 'notifications',
                'route'          => 'character.view.notifications'
            ],
            [
                'name'           => trans('web::seat.pi'),
                'permission'     => 'character.pi',
                'highlight_view' => 'pi',
                'route'          => 'character.view.pi'
            ],
            [
                'name'           => trans('web::seat.research'),
                'permission'     => 'character.research',
                'highlight_view' => 'research',
                'route'          => 'character.view.research'
            ],
            [
                'name'           => trans('web::seat.sheet'),
                'permission'     => 'character.sheet',
                'highlight_view' => 'sheet',
                'route'          => 'character.view.sheet'
            ],
            [
                'name'           => trans('web::seat.skills'),
                'permission'     => 'character.skills',
                'highlight_view' => 'skills',
                'route'          => 'character.view.skills'
            ],
            [
                'name'           => trans('web::seat.standings'),
                'permission'     => 'character.standings',
                'highlight_view' => 'standings',
                'route'          => 'character.view.standings'
            ],
            [
                'name'           => trans('web::seat.wallet_journal'),
                'permission'     => 'character.journal',
                'highlight_view' => 'journal',
                'route'          => 'character.view.journal'
            ],
            [
                'name'           => trans('web::seat.wallet_transactions'),
                'permission'     => 'character.transactions',
                'highlight_view' => 'transactions',
                'route'          => 'character.view.transactions'
            ],
        ];

        // Load any package menus
        foreach (config('package.character.menu') as $menu_data) {

            $prepared_menu = $this->load_plugin_menu($menu_data);
            array_push($menu, $prepared_menu);
        }

        // Sort the menu alphabetically.
        $menu = array_values(array_sort($menu, function($value) {

            return $value['name'];
        }));

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
     * @param $menu_data
     *
     * @return array
     * @throws \Seat\Web\Exceptions\PackageMenuBuilderException
     */
    public function load_plugin_menu($menu_data)
    {

        // Validate the package menu
        if(count(array_diff_key($menu_data, $this->required_keys)) == 0)
            throw new PackageMenuBuilderException(
                'Not all keys for a character menu is set!');

        return $menu_data;

    }
}
