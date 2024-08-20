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

use Illuminate\Database\Eloquent\Model;
use Seat\Eveapi\Models\Character\CharacterAffiliation;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Web\Models\User;

/**
 * Class CharacterAffiliationObserver.
 *
 * @package Seat\Web\Observers
 */
class CharacterAffiliationObserver extends AbstractCharacterFilterObserver
{
    /**
     * @param  \Seat\Eveapi\Models\Character\CharacterAffiliation  $affiliation
     */
    public function created(CharacterAffiliation $affiliation)
    {
        $this->fireCharacterFilterEvent($affiliation);
    }

    /**
     * @param  \Seat\Eveapi\Models\Character\CharacterAffiliation  $affiliation
     */
    public function updated(CharacterAffiliation $affiliation)
    {
        $this->fireCharacterFilterEvent($affiliation);
    }

    /**
     * Return the User owning the model which fired the catch event.
     *
     * @param \Illuminate\Database\Eloquent\Model $fired_model The model which fired the catch event
     * @return ?CharacterInfo The character that is affected by this update
     */
    protected function findRelatedCharacter(Model $fired_model): ?CharacterInfo
    {
        return $fired_model->character;
    }
}
