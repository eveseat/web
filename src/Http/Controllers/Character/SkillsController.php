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

namespace Seat\Web\Http\Controllers\Character;

use Illuminate\Support\Collection;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Character\CharacterSkill;
use Seat\Eveapi\Models\Sde\InvGroup;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Traits\Stats;

/**
 * Class SkillsController.
 *
 * @package Seat\Web\Http\Controllers\Character
 */
class SkillsController extends Controller
{
    use Stats;

    const CORE_PROFILE_CATEGORIES = [1210, 1216, 275, 1220, 269, 1209, 257];
    const LEADERSHIP_PROFILE_CATEGORIES = [266, 258, 278, 1545];
    const FIGHTER_PROFILE_CATEGORIES = [273, 272, 256, 255, 1240, 1213];
    const INDUSTRIAL_PROFILE_CATEGORIES = [1241, 268, 1218, 1217, 270, 274];

    /**
     * @param  \Seat\Eveapi\Models\Character\CharacterInfo  $character
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getSkills(CharacterInfo $character)
    {
        $character->load('skills', 'skills.type', 'skills.type.group', 'skills.type.dogma_attributes');

        $skill_categories = InvGroup::withCount([
            'types' => function ($query) {
                $query->where('published', true);
            }, ])
            ->with('types')
            ->where('published', true)
            ->where('categoryID', InvGroup::SKILL_CATEGORY_ID)
            ->orderBy('groupName')
            ->get();

        $training_profiles = (object) [
            'core' => (object) [
                'categories' => self::CORE_PROFILE_CATEGORIES,
                'label' => 'web::seat.core',
                'trained' => $character->skills->whereIn('type.groupID', self::CORE_PROFILE_CATEGORIES)->count(),
                'overall' => $skill_categories->whereIn('groupID', self::CORE_PROFILE_CATEGORIES)->sum('types_count'),
                'stats' => $character->skills->whereIn('type.groupID', self::CORE_PROFILE_CATEGORIES)->count() / $skill_categories->whereIn('groupID', self::CORE_PROFILE_CATEGORIES)->sum('types_count') * 100,
            ],
            'leadership' => (object) [
                'categories' => self::LEADERSHIP_PROFILE_CATEGORIES,
                'label' => 'web::seat.leadership',
                'trained' => $character->skills->whereIn('type.groupID', self::LEADERSHIP_PROFILE_CATEGORIES)->count(),
                'overall' => $skill_categories->whereIn('groupID', self::LEADERSHIP_PROFILE_CATEGORIES)->sum('types_count'),
                'stats' => $character->skills->whereIn('type.groupID', self::LEADERSHIP_PROFILE_CATEGORIES)->count() / $skill_categories->whereIn('groupID', self::LEADERSHIP_PROFILE_CATEGORIES)->sum('types_count') * 100,
            ],
            'fighter' => (object) [
                'categories' => self::FIGHTER_PROFILE_CATEGORIES,
                'label' => 'web::seat.fighter',
                'trained' => $character->skills->whereIn('type.groupID', self::FIGHTER_PROFILE_CATEGORIES)->count(),
                'overall' => $skill_categories->whereIn('groupID', self::FIGHTER_PROFILE_CATEGORIES)->sum('types_count'),
                'stats' => $character->skills->whereIn('type.groupID', self::FIGHTER_PROFILE_CATEGORIES)->count() / $skill_categories->whereIn('groupID', self::FIGHTER_PROFILE_CATEGORIES)->sum('types_count') * 100,
            ],
            'industrial' => (object) [
                'categories' => self::INDUSTRIAL_PROFILE_CATEGORIES,
                'label' => 'web::seat.industrial',
                'trained' => $character->skills->whereIn('type.groupID', self::INDUSTRIAL_PROFILE_CATEGORIES)->count(),
                'overall' => $skill_categories->whereIn('groupID', self::INDUSTRIAL_PROFILE_CATEGORIES)->sum('types_count'),
                'stats' => $character->skills->whereIn('type.groupID', self::INDUSTRIAL_PROFILE_CATEGORIES)->count() / $skill_categories->whereIn('groupID', self::INDUSTRIAL_PROFILE_CATEGORIES)->sum('types_count') * 100,
            ],
        ];

        return view('web::character.skills',
            compact('character', 'skill_categories', 'training_profiles'));
    }

    /**
     * @param  \Seat\Eveapi\Models\Character\CharacterInfo  $character
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
     * @param  \Seat\Eveapi\Models\Character\CharacterInfo  $character
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
                        return round($item->character_amount / $item->game_amount * 100, 2);  // character / in game rate
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
     * @param  int  $character_id
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
     * @param  int  $character_id
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
