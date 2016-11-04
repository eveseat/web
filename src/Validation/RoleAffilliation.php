<?php
/*
This file is part of SeAT

Copyright (C) 2015, 2016  Leon Jacobs

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

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class RoleAffilliation
 * @package Seat\Web\Validation
 */
class RoleAffilliation extends FormRequest
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
            'role_id'        => 'required|exists:roles,id',
            'inverse'        => 'required|nullable|in:on',
            'characters'     => 'required_without_all:corporations',
            'corporations'   => 'required_without_all:characters',
            'characters.*'   => 'exists:account_api_key_info_characters,characterID',
            'corporations.*' => 'exists:corporation_sheets,corporationID'
        ];

        return $rules;
    }
}
