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

namespace Seat\Web\Observers;

use Exception;
use Seat\Eveapi\Bus\Character;
use Seat\Eveapi\Models\RefreshToken;
use Seat\Web\Events\CharacterFilterDataUpdate;

/**
 * Class RefreshTokenObserver.
 *
 * @package Seat\Web\Observers
 */
class RefreshTokenObserver
{
    /**
     * @param  \Seat\Eveapi\Models\RefreshToken  $token
     */
    public function created(RefreshToken $token)
    {
        try {
            $job = new Character($token->character_id, $token);
            $job->fire();
        } catch (Exception $e) {
            logger()->error($e->getMessage());
        }
    }

    /**
     * @param  \Seat\Eveapi\Models\RefreshToken  $token
     */
    public function restored(RefreshToken $token)
    {
        try {
            $job = new Character($token->character_id, $token);
            $job->fire();
        } catch (Exception $e) {
            logger()->error($e->getMessage());
        }
    }

    /**
     * @param  \Seat\Eveapi\Models\RefreshToken  $token
     */
    public function deleted(RefreshToken $token)
    {
        event(new CharacterFilterDataUpdate($token->character));
    }
}
