<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to present Leon Jacobs
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

namespace Seat\Web\Events;

use Illuminate\Queue\SerializesModels;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\RefreshToken;
use Seat\Web\Models\User;

/**
 * This event is fired when the state or data of an authenticated character significantly changes.
 * This is for example the case when a character finishes his ESI jobs, or when his token gets deleted.
 * It is mostly used for systems like squad filters or per-token update intervals.
 * This event might be computationally expensive, please try to keep invocations to a minimum.
 */
class AuthedCharacterFilterDataUpdate
{
    use SerializesModels;

    public RefreshToken $token;

    /**
     * @param  User  $user
     */
    public function __construct(RefreshToken $token)
    {
        $this->token = $token;
    }
}
