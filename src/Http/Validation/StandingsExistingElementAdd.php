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

namespace Seat\Web\Http\Validation;

use Illuminate\Foundation\Http\FormRequest;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Corporation\CorporationInfo;

/**
 * Class StandingsExistingElementAdd.
 * @package Seat\Web\Http\Validation
 */
class StandingsExistingElementAdd extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'id'          => 'required|exists:standings_profiles,id',
            'character'   => 'nullable|in:' .
                $this->getAllCharactersWithAffiliations()->pluck('character_id')->implode(','),
            'corporation' => 'nullable|in:' .
                $this->getAllCorporationsWithAffiliationsAndFilters()->pluck('corporation_id')->implode(','),
        ];
    }

    /**
     * Query the database for characters, keeping filters,
     * permissions and affiliations in mind.
     *
     * @param bool $get
     *
     * @return mixed
     */
    private function getAllCharactersWithAffiliations(bool $get = true)
    {
        // Start the character information query
        $characters = CharacterInfo::authorized('character.sheet')
            ->with('affiliation.corporation', 'affiliation.alliance')
            ->select('character_infos.*');

        if ($get)
            return $characters
                ->orderBy('name')
                ->get();

        return $characters;
    }

    /**
     * Return the corporations for which a user has access.
     *
     * @param bool $get
     *
     * @return mixed
     */
    private function getAllCorporationsWithAffiliationsAndFilters(bool $get = true)
    {
        // Start a fresh query
        $corporations = CorporationInfo::authorized('corporation.sheet')
            ->with('ceo', 'alliance')
            ->select('corporation_infos.*');

        if ($get)
            return $corporations->orderBy('name', 'desc')
                ->get();

        return $corporations;
    }
}
