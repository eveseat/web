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

namespace Seat\Web\Validation;

use App\Http\Requests\Request;

/**
 * Class RoleUser
 * @package Seat\Web\Validation
 */
class RoleUser extends Request
{

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
            'role_id' => 'required|exists:roles,id'
        ];

        // Ensure that the users is set, if not,
        // complain that it is actually required
        if (!$this->request->get('users')) {

            $rules['users'] = 'required';

            return $rules;

        }

        // Add each user in the multi select dynamically
        foreach ($this->request->get('users') as $key => $value)

            $rules['users.' . $key] = 'required|exists:users,name';

        return $rules;
    }
}
