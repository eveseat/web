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

namespace Seat\Web\Http\Validation;

use Illuminate\Foundation\Http\FormRequest;
use Seat\Web\Models\Group;

/**
 * Class ReassignUser.
 * @package Seat\Web\Http\Validation
 */
class ReassignUser extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'user_id'  => 'required|exists:users,id',
            'group_id' => [
                'required',
                'exists:groups,id',
                function ($attribute, $value, $fail) {

                    // retrieve admin group, if any
                    $admin_group = Group::whereHas('users', function ($query) {

                        $query->where('name', 'admin');
                    })->first();

                    // if the requested group_id is matching the admin one; skip
                    if (! is_null($admin_group) && $admin_group->id == $value) {
                        return $fail('You cannot attach any user to the admin group relationship.');
                    }
                },
            ],
        ];
    }
}
