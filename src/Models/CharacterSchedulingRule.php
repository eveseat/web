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

namespace Seat\Web\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Seat\Eveapi\Models\RefreshToken;
use Seat\Eveapi\Models\RefreshTokenSchedule;
use Seat\Services\Models\ExtensibleModel;
use Seat\Web\Models\Acl\Role;

/**
 * @property int $id
 * @property int $role_id
 * @property int update_interval
 * @property Role $role
 */
class CharacterSchedulingRule extends ExtensibleModel
{
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string
     */
    protected $primaryKey = 'role_id';

    /**
     * @var bool
     */
    public $incrementing = false;

    const DEFAULT_UPDATE_INTERVAL = 60 * 60; // 1 hour, the esi cache timer for most endpoints

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public static function updateUserRefreshTokenSchedule(User $user): void
    {
        $update_interval = $user->roles()
            ->whereHas('character_scheduling_rule')
            ->get()
            ->min('character_scheduling_rule.update_interval') ?? self::DEFAULT_UPDATE_INTERVAL;

        $user->refresh_tokens()->with('token_schedule')->get()
            ->each(function (RefreshToken $token) use ($update_interval) {
                $schedule = $token->token_schedule;

                if($schedule === null) {
                    $schedule = new RefreshTokenSchedule();
                    $schedule->character_id = $token->character_id;
                    $schedule->last_update = Carbon::createFromTimestamp(0); // this field is not optional, therefore set it to the earliest date possible
                }

                $schedule->update_interval = $update_interval;
                $schedule->save();
            });
    }
}
