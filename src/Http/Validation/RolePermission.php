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

namespace Seat\Web\Http\Validation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

/**
 * Class RolePermission.
 *
 * @package Seat\Web\Http\Validation
 */
class RolePermission extends FormRequest
{
    /**
     * Authorize the request by default.
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

        // Start with a default rules array for the
        // role_id check
        $rules = [
            'role_id' => 'required|exists:roles,id',
            'inverse' => 'nullable|in:on',
        ];

        // Ensure that the permissions is set, if not,
        // complain that it is actually required
        if (! $this->request->get('permissions')) {

            $rules['permissions'] = 'required';

            return $rules;

        }

        // Add each permission in the multi select dynamically
        foreach ($this->request->get('permissions') as $key => $value)

            // Permissions can be written in . notation. If this is
            // the case, we need to build the filter so that the
            // actual value we got from the form request exists in the
            // 'in' constraint on the validation rules. We do this by
            // running array map on the categorized permissions and
            // appending the category to the rule to match the value
            // the form request would have sent.
            if (Str::contains($value, '.')) {

                $category = explode('.', $value)[0];

                $rules['permissions.' . $key] = 'required|in:' .
                    implode(',', array_filter(
                        array_map(function ($web_perm) use ($category) {

                            return $category . '.' . $web_perm;

                        }, config('seat.permissions.' . $category))));

            } else {

                // If the string does not have . notation,
                // then we can assume its flat and no category needs
                // appending to the permission name
                $rules['permissions.' . $key] = 'required|in:' .
                    implode(',', array_filter(
                        array_map(function ($web_perm) {

                            // If the permission is an array, then
                            // we just return null
                            if (is_array($web_perm))
                                return null;

                            return $web_perm;

                        }, config('seat.permissions'))));
            }

        return $rules;
    }
}
