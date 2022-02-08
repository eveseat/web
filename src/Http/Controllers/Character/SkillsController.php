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

/**
 * Class SkillsController.
 *
 * @package Seat\Web\Http\Controllers\Character
 */
class SkillsController extends Controller
{
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
            }])
            ->with('types')
            ->where('published', true)
            ->where('categoryID', InvGroup::SKILL_CATEGORY_ID)
            ->orderBy('groupName')
            ->get();

        $training_profiles = (object) [
            'core' => (object) [
                'categories' => StatsController::CORE_PROFILE_CATEGORIES,
                'label' => 'web::seat.core',
                'trained' => $character->skills->whereIn('type.groupID', StatsController::CORE_PROFILE_CATEGORIES)->count(),
                'overall' => $skill_categories->whereIn('groupID', StatsController::CORE_PROFILE_CATEGORIES)->sum('types_count'),
                'stats' => $character->skills->whereIn('type.groupID', StatsController::CORE_PROFILE_CATEGORIES)->count() / $skill_categories->whereIn('groupID', StatsController::CORE_PROFILE_CATEGORIES)->sum('types_count') * 100,
            ],
            'leadership' => (object) [
                'categories' => StatsController::LEADERSHIP_PROFILE_CATEGORIES,
                'label' => 'web::seat.leadership',
                'trained' => $character->skills->whereIn('type.groupID', StatsController::LEADERSHIP_PROFILE_CATEGORIES)->count(),
                'overall' => $skill_categories->whereIn('groupID', StatsController::LEADERSHIP_PROFILE_CATEGORIES)->sum('types_count'),
                'stats' => $character->skills->whereIn('type.groupID', StatsController::LEADERSHIP_PROFILE_CATEGORIES)->count() / $skill_categories->whereIn('groupID', StatsController::LEADERSHIP_PROFILE_CATEGORIES)->sum('types_count') * 100,
            ],
            'fighter' => (object) [
                'categories' => StatsController::FIGHTER_PROFILE_CATEGORIES,
                'label' => 'web::seat.fighter',
                'trained' => $character->skills->whereIn('type.groupID', StatsController::FIGHTER_PROFILE_CATEGORIES)->count(),
                'overall' => $skill_categories->whereIn('groupID', StatsController::FIGHTER_PROFILE_CATEGORIES)->sum('types_count'),
                'stats' => $character->skills->whereIn('type.groupID', StatsController::FIGHTER_PROFILE_CATEGORIES)->count() / $skill_categories->whereIn('groupID', StatsController::FIGHTER_PROFILE_CATEGORIES)->sum('types_count') * 100,
            ],
            'industrial' => (object) [
                'categories' => StatsController::INDUSTRIAL_PROFILE_CATEGORIES,
                'label' => 'web::seat.industrial',
                'trained' => $character->skills->whereIn('type.groupID', StatsController::INDUSTRIAL_PROFILE_CATEGORIES)->count(),
                'overall' => $skill_categories->whereIn('groupID', StatsController::INDUSTRIAL_PROFILE_CATEGORIES)->sum('types_count'),
                'stats' => $character->skills->whereIn('type.groupID', StatsController::INDUSTRIAL_PROFILE_CATEGORIES)->count() / $skill_categories->whereIn('groupID', StatsController::INDUSTRIAL_PROFILE_CATEGORIES)->sum('types_count') * 100,
            ],
        ];

        return view('web::character.skills',
            compact('character', 'skill_categories', 'training_profiles'));
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
