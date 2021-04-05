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

namespace Seat\Web\Observers;

use Illuminate\Database\Eloquent\Model;
use Seat\Eveapi\Models\Assets\CharacterAsset;
use Seat\Web\Models\User;

/**
 * Class CharacterAssetObserver.
 *
 * @package Seat\Web\Observers
 */
class CharacterAssetObserver extends AbstractSquadObserver
{
    /**
     * @param \Seat\Eveapi\Models\Assets\CharacterAsset $asset
     */
    public function created(CharacterAsset $asset)
    {
        $this->updateUserSquads($asset);
    }

    /**
     * @param \Seat\Eveapi\Models\Assets\CharacterAsset $asset
     */
    public function updated(CharacterAsset $asset)
    {
        $this->updateUserSquads($asset);
    }

    /**
     * @param \Seat\Eveapi\Models\Assets\CharacterAsset $asset
     */
    public function deleted(CharacterAsset $asset)
    {
        $this->updateUserSquads($asset);
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
}
