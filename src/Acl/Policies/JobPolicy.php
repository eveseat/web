<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2020 Leon Jacobs
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

namespace Seat\Web\Acl\Policies;

use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Web\Models\User;

/**
 * Class JobPolicy.
 *
 * @package Seat\Web\Acl\Policies
 */
class JobPolicy extends AbstractPolicy
{
    /**
     * @param \Seat\Web\Models\User $user
     * @param \Seat\Eveapi\Models\Character\CharacterInfo|integer $character_info
     *
     * @return bool
     */
    public function queue_character_job(User $user, $character_info)
    {
        $entity = $character_info;

        if (is_numeric($character_info))
            $entity = CharacterInfo::find($character_info);

        return in_array($entity->character_id, $user->associatedCharacterIds());
    }

    /**
     * @param \Seat\Web\Models\User $user
     * @param \Seat\Eveapi\Models\Corporation\CorporationInfo|integer $corporation_info
     *
     * @return bool
     */
    public function queue_corporation_job(User $user, $corporation_info)
    {
        $entity = $corporation_info;

        if (is_numeric($corporation_info))
            $entity = CorporationInfo::find($corporation_info);

        return in_array($entity->ceo_id, $user->associatedCharacterIds());
    }
}
