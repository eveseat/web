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

namespace Seat\Web\Http\Controllers\Character;

use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Services\Repositories\Character\Skills;
use Seat\Services\Repositories\Eve\EveRepository;
use Seat\Web\Http\Controllers\Controller;

/**
 * Class SkillsController.
 * @package Seat\Web\Http\Controllers\Character
 */
class SkillsController extends Controller
{
    use Skills;
    use EveRepository;

    /**
     * @param $character_id
     *
     * @return \Illuminate\View\View
     */
    public function getSkills(int $character_id)
    {

        $skills = $this->getCharacterSkillsInformation($character_id);
        $skill_groups = $this->getEveSkillsGroups();

        return view('web::character.skills',
            compact('skills', 'skill_groups'));
    }

    /**
     * @param $character_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCharacterSkillsLevelChartData(int $character_id)
    {

        if ($character_id == 1) {
            return response()->json([]);
        }

        $data = $this->getCharacterSkillsAmountPerLevel($character_id);

        return response()->json([
            'labels'   => [
                'Level 0', 'Level 1', 'Level 2', 'Level 3', 'Level 4', 'Level 5',
            ],
            'datasets' => [
                [
                    'data'            => $data,
                    'backgroundColor' => [
                        '#00c0ef',
                        '#39cccc',
                        '#00a65a',
                        '#605ca8',
                        '#001f3f',
                        '#3c8dbc',
                    ],
                ],
            ],
        ]);
    }

    /**
     * @param $character_id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCharacterSkillsCoverageChartData($character_id)
    {

        if ($character_id == 1) {
            return response()->json([]);
        }

        $data = $this->getCharacterSkillCoverage($character_id);

        $character = CharacterInfo::where('character_id', $character_id)->first();

        return response()->json([
            'labels'   => $data->map(function ($item) {

                return $item->marketGroupName;
            })->toArray(), // skills category
            'datasets' => [
                [
                    'label'                => $character->name,
                    'data'                 => $data->map(function ($item) {

                        return round($item->characterAmount / $item->gameAmount * 100, 2);  // character / in game rate
                    })->toArray(),
                    'fill'                 => true,
                    'backgroundColor'      => 'rgba(60,141,188,0.3)',
                    'borderColor'          => '#3c8dbc',
                    'pointBackgroundColor' => '#3c8dbc',
                    'pointBorderColor'     => '#fff',
                ],
            ],
        ]);
    }
}
