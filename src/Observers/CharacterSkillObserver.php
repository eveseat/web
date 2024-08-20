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
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Character\CharacterSkill;
use Seat\Web\Models\User;

/**
 * Class CharacterSkillObserver.
 *
 * @package Seat\Web\Observers
 */
class CharacterSkillObserver extends AbstractCharacterFilterObserver
{
    /**
     * @param  \Seat\Eveapi\Models\Character\CharacterSkill  $skill
     */
    public function created(CharacterSkill $skill)
    {
        $this->fireCharacterFilterEvent($skill);
    }

    /**
     * @param  \Seat\Eveapi\Models\Character\CharacterSkill  $skill
     */
    public function updated(CharacterSkill $skill)
    {
        $this->fireCharacterFilterEvent($skill);
    }

    /**
     * @param  \Seat\Eveapi\Models\Character\CharacterSkill  $skill
     */
    public function deleted(CharacterSkill $skill)
    {
        $this->fireCharacterFilterEvent($skill);
    }

    /**
     * {@inheritdoc}
     */
    protected function findRelatedUser(Model $fired_model): ?User
    {
        // retrieve user related to the character affiliation
        return User::with('squads')
            ->standard()
            ->whereHas('characters', function ($query) use ($fired_model) {
                $query->where('character_infos.character_id', $fired_model->character_id);
            })->first();
    }

    /**
     * Return the User owning the model which fired the catch event.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $fired_model  The model which fired the catch event
     * @return ?CharacterInfo The character that is affected by this update
     */
    protected function findRelatedCharacter(Model $fired_model): ?CharacterInfo
    {
        return $fired_model->character;
    }
}
