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
use Seat\Eveapi\Bus\Corporation;
use Seat\Eveapi\Models\Character\CharacterRole;
use Seat\Eveapi\Models\RefreshToken;

/**
 * Class CharacterRoleObserver.
 *
 * @package Seat\Web\Observers
 */
class CharacterRoleObserver
{
    /**
     * @param  \Seat\Eveapi\Models\Character\CharacterRole  $role
     */
    public function created(CharacterRole $role)
    {
        // in case the created role is not a Director role, ignore
        if ($role->role != 'Director')
            return;

        // retrieve character related token
        $token = RefreshToken::find($role->character_id);

        if (is_null($token))
            return;

        try {
            // enqueue jobs related to the character corporation
            $job = new Corporation($token->affiliation->corporation_id, $token);
            $job->fire();
        } catch (Exception $e) {
            logger()->error($e->getMessage());
        }
    }
}
