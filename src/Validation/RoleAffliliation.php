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
 * Class RoleAffliliation
 * @package Seat\Web\Validation
 */
class RoleAffliliation extends Request
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

        // Check that we got either a character/corp
        if (!$this->request->get('characters') && !$this->request->get('corporations')) {

            $rules['characters'] = 'required_without_all:corporations';
            $rules['corporations'] = 'required_without_all:characters';

            return $rules;
        }

        // If we have characters, validate them
        if ($this->request->get('characters')) {

            // Add each character in the multi select dynamically
            foreach ($this->request->get('characters') as $key => $value)

                $rules['characters.' . $key] = 'required|exists:account_api_key_info_characters,characterID';
        }

        // If we have corporations, validate them
        if ($this->request->get('corporations')) {

            // Add each corporation in the multi select dynamically
            foreach ($this->request->get('corporations') as $key => $value)

                $rules['corporations.' . $key] = 'required|exists:corporation_sheets,corporationID';
        }

        return $rules;
    }
}
