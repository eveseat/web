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

use App\Http\Requests\Request;
use Seat\Services\Settings\Seat;

/**
 * Class SeatSettings
 * @package Seat\Web\Validation
 */
class SeatSettings extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $allowed_registration = implode(',', Seat::$options['registration']);
        $allowed_force_min_mask = implode(',', Seat::$options['force_min_mask']);

        return [
            'registration'    => 'required|in:' . $allowed_registration,
            'admin_contact'   => 'required|email',
            'force_min_mask'  => 'required|in:' . $allowed_force_min_mask,
            'min_access_mask' => 'required|numeric'
        ];
    }
}
