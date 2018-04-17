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

use Seat\Services\Repositories\Character\Industry;
use Seat\Web\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;

/**
 * Class IndustryController.
 * @package Seat\Web\Http\Controllers\Character
 */
class IndustryController extends Controller
{
    use Industry;

    /**
     * @param $character_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndustry(int $character_id)
    {

        return view('web::character.industry');
    }

    /**
     * @param int $character_id
     *
     * @return mixed
     */
    public function getIndustryData(int $character_id)
    {

        $jobs = $this->getCharacterIndustry($character_id, false);

        return Datatables::of($jobs)
            ->editColumn('installerName', function ($row) {

                return view('web::partials.industryinstaller', compact('row'))
                    ->render();
            })
            ->editColumn('solarSystemName', function ($row) {

                return view('web::partials.industrysystem', compact('row'))
                    ->render();
            })
            ->editColumn('blueprintTypeName', function ($row) {

                return view('web::partials.industryblueprint', compact('row'))
                    ->render();
            })
            ->editColumn('productTypeName', function ($row) {

                return view('web::partials.industryproduct', compact('row'))
                    ->render();
            })
            ->make(true);

    }
}
