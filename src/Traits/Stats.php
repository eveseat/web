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

namespace Seat\Web\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Seat\Eveapi\Models\Character\CharacterInfoSkill;
use Seat\Eveapi\Models\Character\CharacterSkill;
use Seat\Eveapi\Models\Industry\CharacterMining;
use Seat\Eveapi\Models\Killmails\KillmailDetail;
use Seat\Eveapi\Models\Wallet\CharacterWalletBalance;
use Seat\Eveapi\Models\Wallet\CharacterWalletJournal;

/**
 * Class Stats.
 *
 * @package Seat\Web\Traits
 */
trait Stats
{
    /**
     * @param  int[]  $character_ids
     * @return float|null
     */
    public function getTotalCharacterIsk(array $character_ids): ?float
    {

        return CharacterWalletBalance::whereIn('character_id', $character_ids)
            ->sum('balance');
    }

    /**
     * @param  int[]  $character_ids
     * @return float|null
     */
    public function getTotalCharacterMiningIsk(array $character_ids): ?float
    {
        return CharacterMining::selectRaw('SUM(quantity * average) as total_mined_value')
            ->leftJoin('market_prices', 'character_minings.type_id', '=', 'market_prices.type_id')
            ->whereIn('character_id', $character_ids)
            ->where('year', carbon()->year)
            ->where('month', carbon()->month)
            ->first()
            ->total_mined_value;
    }

    /**
     * @param  int[]  $character_ids
     * @return float|null
     */
    public function getTotalCharacterRattingIsk(array $character_ids): ?float
    {
        return CharacterWalletJournal::select('amount')
            ->whereIn('second_party_id', $character_ids)
            ->whereIn('ref_type', ['bounty_prizes', 'ess_escrow_transfer'])
            ->sum('amount');
    }

    /**
     * @param  int  $character_id
     * @return int
     */
    public function getTotalCharacterSkillpoints(array $character_ids): ?int
    {

        return CharacterInfoSkill::whereIn('character_id', $character_ids)
            ->sum('total_sp');
    }

    /**
     * Get the numer of skills per Level for a character.
     *
     * @param  int  $character_id
     * @return array
     */
    public function getCharacterSkillsAmountPerLevel(int $character_id): array
    {

        $skills = CharacterSkill::where('character_id', $character_id)
            ->get();

        return [
            $skills->where('trained_skill_level', 0)->count(),
            $skills->where('trained_skill_level', 1)->count(),
            $skills->where('trained_skill_level', 2)->count(),
            $skills->where('trained_skill_level', 3)->count(),
            $skills->where('trained_skill_level', 4)->count(),
            $skills->where('trained_skill_level', 5)->count(),
        ];
    }

    /**
     * Get a characters skill as well as category completion
     * ration rate.
     *
     * TODO: This is definitely a candidate for a better refactor!
     *
     * @param  int  $character_id
     * @return \Illuminate\Support\Collection
     */
    public function getCharacterSkillCoverage(int $character_id): Collection
    {

        $in_game_skills = DB::table('invTypes')
            ->join(
                'invMarketGroups',
                'invMarketGroups.marketGroupID', '=', 'invTypes.marketGroupID'
            )
            ->where('parentGroupID', '?')// binding at [1]
            ->select(
                'marketGroupName',
                DB::raw('COUNT(invTypes.marketGroupID) * 5 as amount')
            )
            ->groupBy('marketGroupName')
            ->toSql();

        $character_skills = CharacterSkill::join(
            'invTypes',
            'invTypes.typeID', '=',
            'character_skills.skill_id'
        )
            ->join(
                'invMarketGroups',
                'invMarketGroups.marketGroupID', '=',
                'invTypes.marketGroupID'
            )
            ->where('character_id', '?')// binding at [2]
            ->select(
                'marketGroupName',
                DB::raw('COUNT(invTypes.marketGroupID) * character_skills.trained_skill_level  as amount')
            )
            ->groupBy(['marketGroupName', 'trained_skill_level'])
            ->toSql();

        $skills = DB::table(
            DB::raw('(' . $in_game_skills . ') a')
        )
            ->leftJoin(
                DB::raw('(' . $character_skills . ') b'),
                'a.marketGroupName',
                'b.marketGroupName'
            )
            ->select(
                'a.marketGroupName',
                DB::raw('a.amount AS gameAmount'),
                DB::raw('SUM(b.amount) AS characterAmount')
            )
            ->groupBy(['a.marketGroupName', 'a.amount'])
            ->addBinding(150, 'select')// binding [1]
            ->addBinding($character_id, 'select')// binding [2]
            ->get();

        return $skills;
    }

    /**
     * @param  int[]  $character_ids
     * @return int
     */
    public function getTotalCharacterKillmails(array $character_ids): int
    {

        return KillmailDetail::whereYear('killmail_time', carbon()->year)
            ->whereMonth('killmail_time', carbon()->month)
            ->where(function ($detail) use ($character_ids) {
                $detail->whereHas('attackers', function ($attackers) use ($character_ids) {
                    $attackers->whereIn('character_id', $character_ids);
                })->orWhereHas('victim', function ($victim) use ($character_ids) {
                    $victim->whereIn('character_id', $character_ids);
                });
            })->count();
    }
}
