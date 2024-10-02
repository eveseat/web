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

use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\RefreshToken;
use Seat\Eveapi\Models\RefreshTokenSchedule;
use Seat\Services\Models\ExtensibleModel;
use stdClass;

/**
 * @property int $id
 * @property string name
 * @property string filter
 * @property int interval
 */
class CharacterSchedulingRule extends ExtensibleModel
{
    use Filterable;

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * The filters to use.
     *
     * @return \stdClass
     */
    public function getFilters(): stdClass
    {
        return json_decode($this->filter);
    }

    /**
     * Recomputes the update interval of a character and saves it in the refresh_token_schedules table.
     *
     * @param  RefreshToken  $token
     * @return void
     */
    public static function updateRefreshTokenSchedule(RefreshToken $token): void
    {
        $schedule = $token->token_schedule;

        if($schedule === null) {
            $schedule = new RefreshTokenSchedule();
            $schedule->character_id = $token->character_id;
            $schedule->last_update = now()->subYears(10); // Hopefully this is far enough in the past to mean never?
        }

        $schedule->update_interval = self::getCharacterSchedulingInterval($token->character);
        $schedule->save();
    }

    /**
     * Computes the scheduling interval from the character scheduling rules for a character.
     *
     * @param  CharacterInfo  $character
     * @return int
     */
    private static function getCharacterSchedulingInterval(CharacterInfo $character): int
    {
        $scheduling_rules = CharacterSchedulingRule::orderBy('interval', 'asc')->get();

        foreach ($scheduling_rules as $rule) {
            if($rule->isEligible($character)) {
                return $rule->interval;
            }
        }

        return 60 * 60; // 1 hour
    }
}
