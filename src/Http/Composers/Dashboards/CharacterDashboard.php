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

namespace Seat\Web\Http\Composers\Dashboards;

use Seat\Web\Contracts\Dashboard;
use Seat\Web\Traits\Stats;

/**
 * Class CharacterDashboard.
 *
 * @package Seat\Web\Http\Composers\Dashboards
 */
class CharacterDashboard implements Dashboard
{
    use Stats; // TODO : switch to repository

    public function blade(): string
    {
        return 'web::dashboards.character';
    }

    public function data(): array
    {
        $characterIds = auth()->user()->associatedCharacterIds();

        return [
            'total_character_isk' => $this->getTotalCharacterIsk($characterIds),
            'total_character_mining' => $this->getTotalCharacterMiningIsk($characterIds),
            'total_character_ratting' => $this->getTotalCharacterRattingIsk($characterIds),
            'total_character_skillpoints' => $this->getTotalCharacterSkillpoints($characterIds),
            'total_character_killmails' => $this->getTotalCharacterKillmails($characterIds),
        ];
    }
}
