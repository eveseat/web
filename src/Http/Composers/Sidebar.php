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

        // TODO: Allow plugins to add menu items
        // Menus are structured as follows:

        $menu = [

            [   // Home
                'name'          => trans('web::sidebar.home'),
                'icon'          => 'fa-home',
                'route_segment' => 'home',
                'route'         => route('home')
            ],
            [   // Configuration
                'name'          => trans('web::sidebar.configuration'),
                'icon'          => 'fa-cogs',
                'route_segment' => 'configuration',
                'acl_slug'      => 'admin',
                'entries'       => [

                    [   // Users
                        'name'  => trans('web::sidebar.users'),
                        'icon'  => 'fa-user',
                        'route' => route('configuration.users')
                    ],
                    [   // Access
                        'name'  => trans('web::sidebar.access'),
                        'icon'  => 'fa-shield',
                        'route' => route('configuration.access.roles')
                    ]
                ]
            ],
            [   // Other
                'name'          => trans('web::sidebar.other'),
                'icon'          => 'fa-circle',
                'route_segment' => 'other',
            ]
        ];

        $view->with('menu', $menu);
    }
}
