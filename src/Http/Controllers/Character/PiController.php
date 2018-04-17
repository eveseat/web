<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015, 2016, 2017, 2018  Leon Jacobs
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

use Seat\Services\Repositories\Character\Pi;
use Seat\Web\Http\Controllers\Controller;

class PiController extends Controller
{
    use Pi;

    /**
     * @param $character_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getPi(int $character_id)
    {

        $colonies = $this->getCharacterPlanetaryColonies($character_id);
        $extractors = $this->getCharacterPlanetaryExtractors($character_id);

        $extractors = $extractors->map(function($item){
            $item->celestialIndex = $this->getRomanNumerals($item->celestialIndex);
            $item->cycle_time = $this->getCountdown($item->expiry_time);
           return $item;
        });

        // TODO: Complete the Links and stuff™

        return view('web::character.pi', compact('colonies','extractors'));
    }

    function getCountdown($end){
        $end = strtotime($end);

        $current = strtotime(now());
        $completed = (1-1/($end-$current))* 100;

        return $completed;

    }

    function getRomanNumerals($decimalInteger)
    {
        $n = intval($decimalInteger);
        $res = '';

        $roman_numerals = array(
            'M'  => 1000,
            'CM' => 900,
            'D'  => 500,
            'CD' => 400,
            'C'  => 100,
            'XC' => 90,
            'L'  => 50,
            'XL' => 40,
            'X'  => 10,
            'IX' => 9,
            'V'  => 5,
            'IV' => 4,
            'I'  => 1);

        foreach ($roman_numerals as $roman => $numeral)
        {
            $matches = intval($n / $numeral);
            $res .= str_repeat($roman, $matches);
            $n = $n % $numeral;
        }

        return $res;
    }
}
