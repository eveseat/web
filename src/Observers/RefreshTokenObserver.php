<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2022 Leon Jacobs
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
use Illuminate\Database\Eloquent\Model;
use Seat\Eveapi\Bus\Character;
use Seat\Eveapi\Models\RefreshToken;
use Seat\Web\Models\User;

/**
 * Class RefreshTokenObserver.
 *
 * @package Seat\Web\Observers
 */
class RefreshTokenObserver extends AbstractSquadObserver
{
    /**
     * @param  \Seat\Eveapi\Models\RefreshToken  $token
     */
    public function created(RefreshToken $token)
    {
        try {
            $job = new Character($token->character_id, $token);
            $job->fire();

            // enqueue squads update
            $this->updateUserSquads($token);
        } catch (Exception $e) {
            logger()->error($e->getMessage());
        }
    }

    /**
     * @param  \Seat\Eveapi\Models\RefreshToken  $token
     */
    public function updated(RefreshToken $token)
    {
        try {
            $this->updateUserSquads($token);
        } catch (Exception $e) {
            logger()->error($e->getMessage());
        }
    }

    /**
     * @param  \Seat\Eveapi\Models\RefreshToken  $token
     */
    public function softDeleted(RefreshToken $token)
    {
        $this->deleted($token);
    }

    /**
     * @param  \Seat\Eveapi\Models\RefreshToken  $token
     */
    public function deleted(RefreshToken $token)
    {
        try {
            $this->updateUserSquads($token);
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

            // enqueue squads update
            $this->updateUserSquads($token);
        } catch (Exception $e) {
            logger()->error($e->getMessage());
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function findRelatedUser(Model $fired_model): ?User
    {
        return User::with('squads')
            ->find($fired_model->user_id);
    }
}
