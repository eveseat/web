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

namespace Seat\Web\Views\Components\Menus;

use Illuminate\View\Component;

/**
 * Class DropDown.
 * @package Seat\Web\Views\Components\Menus
 */
class DropDown extends Component
{
    /**
     * The URL that this drop-down entry must target.
     *
     * @var string
     */
    public string $href;

    /**
     * The menu entry icon.
     *
     * @var string
     */
    public string $icon;

    /**
     * The menu title.
     *
     * @var string
     */
    public string $title;

    /**
     * Create a new component instance.
     *
     * @param  string  $href
     * @param  string  $icon
     * @param  string  $title
     */
    public function __construct(string $href, string $icon, string $title)
    {
        $this->href = $href;
        $this->icon = $icon;
        $this->title = $title;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('web::components.menus.drop-down');
    }
}
