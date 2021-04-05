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

namespace Seat\Web\Http\Controllers\Character;

use Illuminate\Support\Collection;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Character\CharacterSkill;
use Seat\Eveapi\Models\Sde\InvGroup;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Traits\Stats;

/**
 * Class SkillsController.
 * @package Seat\Web\Http\Controllers\Character
 */
class SkillsController extends Controller
{
    use Stats;

    /**
     * @param \Seat\Eveapi\Models\Character\CharacterInfo $character
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getSkills(CharacterInfo $character)
    {
        return view('web::character.skills',
            compact('character'));
    }

    /**
     * @param \Seat\Eveapi\Models\Character\CharacterInfo $character
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCharacterSkillsLevelChartData(CharacterInfo $character)
    {
        $data = $this->getCharacterSkillsAmountPerLevel($character->character_id);

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
     * @param \Seat\Eveapi\Models\Character\CharacterInfo $character
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCharacterSkillsCoverageChartData(CharacterInfo $character)
    {
        $data = $this->getCharacterSkillCoverage($character->character_id);

        $character = CharacterInfo::where('character_id', $character->character_id)->first();

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

    /**
     * @param int $character_id
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function export(CharacterInfo $character)
    {
        return response()->streamDownload(function () use ($character) {
            $skills = $this->getCharacterSkillsInformation($character->character_id);

            echo $skills->map(function ($skill) {
                return sprintf("%s\t%d", $skill->typeName, $skill->trained_skill_level);
            })->implode(PHP_EOL);
        }, sprintf('characters_%d_skills.txt', $character->character_id), [
            'Content-Type' => 'text/plain',
        ]);
    }

    /**
     * Return the groups that character skills
     * fall in.
     *
     * @return InvGroup[]
     */
    private function getEveSkillsGroups()
    {

        $groups = InvGroup::where('categoryID', 16)
            ->where('groupID', '<>', 505)
            ->orderBy('groupName')
            ->get();

        return $groups;

    }

    /**
     * Return the skills detail for a specific Character.
     *
     * @param int $character_id
     *
     * @return \Illuminate\Support\Collection
     */
    private function getCharacterSkillsInformation(int $character_id): Collection
    {

        return CharacterSkill::join('invTypes',
            'character_skills.skill_id', '=',
            'invTypes.typeID')
            ->join('invGroups', 'invTypes.groupID', '=', 'invGroups.groupID')
            ->where('character_skills.character_id', $character_id)
            ->orderBy('invTypes.typeName')
            ->get();
    }
}
