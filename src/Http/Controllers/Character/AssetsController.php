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

use Seat\Services\Repositories\Character\Assets;
use Seat\Web\Http\Controllers\Controller;

/**
 * Class AssetsController.
 * @package Seat\Web\Http\Controllers\Character
 */
class AssetsController extends Controller
{
    use Assets;

    /**
     * @param $character_id
     *
     * @return \Illuminate\View\View
     */
    public function getAssets(int $character_id)
    {

        $assets = $this->getCharacterAssets($character_id);

        return view('web::character.assets', compact('assets'));
    }

    /**
     * @param int $character_id
     * @param int $item_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAssetsContents(int $character_id, int $item_id)
    {

        $contents = $this->getCharacterAssetContents($character_id, $item_id);

        return view('web::partials.assetscontents', compact('contents'));
    }
}
