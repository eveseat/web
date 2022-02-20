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

use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Sde\InvGroup;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Traits\Stats;

class StatsController extends Controller
{
    use Stats;

    const CORE_PROFILE_CATEGORIES = [1210, 1216, 275, 1220, 269, 1209, 257];
    const LEADERSHIP_PROFILE_CATEGORIES = [266, 258, 278, 1545];
    const FIGHTER_PROFILE_CATEGORIES = [273, 272, 256, 255, 1240, 1213];
    const INDUSTRIAL_PROFILE_CATEGORIES = [1241, 268, 1218, 1217, 270, 274];

    // <!-- Characters Badge -->
    // <x-seat::widgets.badge icon="fas fa-key" color="bg-info" title="{{ trans('web::seat.linked_characters') }}" value="{{ count(auth()->user()->associatedCharacterIds()) }}" />
    /**
     * @param  \Seat\Eveapi\Models\Character\CharacterInfo  $character
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCharactersCount(CharacterInfo $character)
    {
        return response()->json(count($character->user->associatedCharacterIds()));
    }

    // <!-- Skills Badge -->
    // <x-seat::widgets.badge icon="fas fa-graduation-cap" color="bg-black" title="{{ trans('web::seat.total_character_skillpoints') }}" value="{{ number_format($total_character_skillpoints, 0)  }}" />
    /**
     * @param  \Seat\Eveapi\Models\Character\CharacterInfo  $character
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCharacterSkillpoints(CharacterInfo $character)
    {
        return response()->json($this->getTotalCharacterSkillpoints([$character->character_id]));
    }

    // <!-- Wallet Badge -->
    // <x-seat::widgets.badge icon="far fa-money-bill-alt" color="bg-blue" title="{{ trans('web::seat.total_character_isk') }}" value="{{ number_format($total_character_isk)  }}" />
    /**
     * @param  \Seat\Eveapi\Models\Character\CharacterInfo  $character
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCharactersBalance(CharacterInfo $character)
    {
        return response()->json($this->getTotalCharacterIsk($character->user->associatedCharacterIds()));
    }

    // <!-- Ore Badge -->
    // <x-seat::widgets.badge icon="far fa-gem" color="bg-purple" title="{{ trans('web::seat.total_character_mined_isk') }}" value="{{ number_format($total_character_mining) }}" />
    /**
     * @param  \Seat\Eveapi\Models\Character\CharacterInfo  $character
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCharactersMining(CharacterInfo $character)
    {
        return response()->json($this->getTotalCharacterMiningIsk($character->user->associatedCharacterIds()));
    }

    // <!-- NPC Badge -->
    // <x-seat::widgets.badge icon="fas fa-coins" color="bg-yellow" title="{{ trans('web::seat.total_character_ratted_isk') }}" value="{{ number_format($total_character_ratting) }}" />
    /**
     * @param  \Seat\Eveapi\Models\Character\CharacterInfo  $character
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCharactersRating(CharacterInfo $character)
    {
        return response()->json($this->getTotalCharacterRattingIsk($character->user->associatedCharacterIds()));
    }

    // <!-- Kills Badge -->
    // <x-seat::widgets.badge icon="fas fa-space-shuttle" color="bg-red" title="{{ trans('web::seat.total_killmails') }}" value="{{ $total_character_killmails }}" />
    /**
     * @param  \Seat\Eveapi\Models\Character\CharacterInfo  $character
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCharactersKills(CharacterInfo $character)
    {
        return response()->json($this->getTotalCharacterKillmails($character->user->associatedCharacterIds()));
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
     * @param  \Seat\Eveapi\Models\Character\CharacterInfo  $character
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCharacterTrainingProfileChartData(CharacterInfo $character)
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

        $training_profiles = [
            'core' => [
                'categories' => self::CORE_PROFILE_CATEGORIES,
                'label' => 'web::seat.core',
                'trained' => $character->skills->whereIn('type.groupID', self::CORE_PROFILE_CATEGORIES)->count(),
                'overall' => $skill_categories->whereIn('groupID', self::CORE_PROFILE_CATEGORIES)->sum('types_count'),
                'stats' => $character->skills->whereIn('type.groupID', self::CORE_PROFILE_CATEGORIES)->count() / $skill_categories->whereIn('groupID', self::CORE_PROFILE_CATEGORIES)->sum('types_count') * 100,
            ],
            'leadership' => [
                'categories' => self::LEADERSHIP_PROFILE_CATEGORIES,
                'label' => 'web::seat.leadership',
                'trained' => $character->skills->whereIn('type.groupID', self::LEADERSHIP_PROFILE_CATEGORIES)->count(),
                'overall' => $skill_categories->whereIn('groupID', self::LEADERSHIP_PROFILE_CATEGORIES)->sum('types_count'),
                'stats' => $character->skills->whereIn('type.groupID', self::LEADERSHIP_PROFILE_CATEGORIES)->count() / $skill_categories->whereIn('groupID', self::LEADERSHIP_PROFILE_CATEGORIES)->sum('types_count') * 100,
            ],
            'fighter' => [
                'categories' => self::FIGHTER_PROFILE_CATEGORIES,
                'label' => 'web::seat.fighter',
                'trained' => $character->skills->whereIn('type.groupID', self::FIGHTER_PROFILE_CATEGORIES)->count(),
                'overall' => $skill_categories->whereIn('groupID', self::FIGHTER_PROFILE_CATEGORIES)->sum('types_count'),
                'stats' => $character->skills->whereIn('type.groupID', self::FIGHTER_PROFILE_CATEGORIES)->count() / $skill_categories->whereIn('groupID', self::FIGHTER_PROFILE_CATEGORIES)->sum('types_count') * 100,
            ],
            'industrial' => [
                'categories' => self::INDUSTRIAL_PROFILE_CATEGORIES,
                'label' => 'web::seat.industrial',
                'trained' => $character->skills->whereIn('type.groupID', self::INDUSTRIAL_PROFILE_CATEGORIES)->count(),
                'overall' => $skill_categories->whereIn('groupID', self::INDUSTRIAL_PROFILE_CATEGORIES)->sum('types_count'),
                'stats' => $character->skills->whereIn('type.groupID', self::INDUSTRIAL_PROFILE_CATEGORIES)->count() / $skill_categories->whereIn('groupID', self::INDUSTRIAL_PROFILE_CATEGORIES)->sum('types_count') * 100,
            ],
        ];

        return response()->json($training_profiles);
    }

    public function getCharacterSkillQueueChartData(CharacterInfo $character)
    {
        //$character->load('skill_queue', 'skill_queue.type');

        $data = $character->skill_queue()->orderBy('queue_position')->limit(10)->get()->map(function ($skill) {
            return [
                'x' => $skill->type->typeName,
                'y' => [
                    $skill->start_date->getTimestampMs(),
                    $skill->finish_date->getTimestampMs(),
                ],
            ];
        });

        return response()->json($data);
    }

    /**
     * @param  \Seat\Eveapi\Models\Character\CharacterInfo  $character
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCharacterWalletEarningsChartData(CharacterInfo $character)
    {
        $data = $character->wallet_journal()
            ->whereDate('date', '>=', carbon()->subMonths(12))
            ->groupByRaw('to_char(date, \'YYYY-mm-01\')')
            ->selectRaw('to_char(date, \'YYYY-mm-01\') as period, SUM(amount) as aggregate')
            //->selectRaw('SUM(amount) as aggregate')
            ->get();

        return response()->json([
            'datasets' => [
                [
                    'data' => $data->pluck('aggregate'),
                    'categories' => $data->pluck('period'),
                ],
            ],
        ]);
    }
}
