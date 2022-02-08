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

namespace Seat\Web\Views\Components\Widgets;

use Illuminate\View\Component;

/**
 * Class Badge.
 * @package Seat\Web\View\Components\Widgets
 */
class Badge extends Component
{
    /**
     * The badge icon.
     *
     * @var string
     */
    public string $icon;

    /**
     * The badge color.
     *
     * @var string
     */
    public string $color;

    /**
     * The badge title (first line).
     *
     * @var string
     */
    public string $title;

    /**
     * The badge value (second line).
     *
     * @var mixed
     */
    public mixed $value;

    /**
     * Create a new component instance.
     *
     * @param  string  $icon
     * @param  string  $color
     * @param  string  $title
     * @param  mixed  $value
     */
    public function __construct(string $icon, string $color, string $title, mixed $value)
    {
        $this->icon = $icon;
        $this->color = $color;
        $this->title = $title;
        $this->value = $value;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('web::components.widgets.badge');
    }
}
