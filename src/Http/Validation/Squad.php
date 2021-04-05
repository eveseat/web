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

namespace Seat\Web\Http\Validation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class Squad.
 *
 * @package Seat\Web\Http\Validation
 */
class Squad extends FormRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                $this->route()->parameter('squad') ?
                    Rule::unique('squads', 'name')->ignore($this->route()->parameter('squad')) :
                    Rule::unique('squads', 'name'),
                'max:255',
            ],
            'type'        => 'required|in:manual,auto,hidden',
            'description' => 'required',
            'logo'        => 'mimes:jpeg,jpg,png|max:2000',
            'filters'     => 'json',
        ];
    }
}
