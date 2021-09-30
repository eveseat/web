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

namespace Seat\Web\Http\Composers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\RefreshToken;

class CharacterLayout
{

    protected $request;

    /**
     * CharacterLayout constructor.
     *
     * @param  Request  $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Bind Character Name to the view.
     *
     * @param  View  $view
     */
    public function compose(View $view)
    {
        $character_info = CharacterInfo::find($this->request->character_id);

        if (! is_null($character_info))
            $view->with('character_name', $character_info->name);

        if (! RefreshToken::where('character_id', $this->request->character_id)->exists()) {
            $token = RefreshToken::onlyTrashed()->where('character_id', $this->request->character_id)->first();

            if (is_null($token))
                return $view->with('error', trans_choice('web::seat.invalid_token', 1));

            redirect()->back()->with('warning', trans('web::seat.deleted_refresh_token', ['time' => human_diff($token->deleted_at)]));
        }
    }
}
