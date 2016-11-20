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
use Seat\Eveapi\Models\Account\ApiKeyInfoCharacters;
use Seat\Services\Settings\Profile;

class ProfileSettings extends FormRequest
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

        $user_id = auth()->user()->id;

        // For some fail reson I cant get the UserRepository trait
        // to be happy here.
        // TODO: Fix that!
        $allowed_main_character_ids = implode(',', ApiKeyInfoCharacters::with('key')
            ->whereHas('key', function ($query) use ($user_id) {

                $query->where('user_id', $user_id);
            })
            ->pluck('characterID')
            ->toArray());

        $allowed_skins = implode(',', Profile::$options['skins']);
        $allowed_languages = implode(',', array_map(function ($entry) {

            return $entry['short'];
        }, config('web.locale.languages')));
        $allowed_sidebar = implode(',', Profile::$options['sidebar']);

        return [
            'main_character_id'   => 'required|in:' . $allowed_main_character_ids,
            'skin'                => 'required|in:' . $allowed_skins,
            'language'            => 'required|in:' . $allowed_languages,
            'sidebar'             => 'required|in:' . $allowed_sidebar,
            'thousand_seperator'  => 'in:" ",",","."|size:1',
            'decimal_seperator'   => 'required|in:",","."|size:1',
            'email_notifications' => 'required|in:yes,no',
            'require_mfa'         => 'required|in:yes,no'
        ];
    }
}
