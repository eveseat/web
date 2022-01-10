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

use Seat\Eveapi\Models\Assets\CharacterAsset;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\DataTables\Character\Intel\Assets\DataTable;
use Seat\Web\Http\DataTables\Scopes\CharacterScope;

/**
 * Class AssetsController.
 *
 * @package Seat\Web\Http\Controllers\Character
 */
class AssetsController extends Controller
{
    /**
     * @param  \Seat\Eveapi\Models\Character\CharacterInfo  $character
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAssetsView(CharacterInfo $character, DataTable $dataTable)
    {
        return $dataTable->addScope(new CharacterScope('character.asset', request()->input('characters')))
            ->render('web::character.assets.assets', compact('character'));
    }

    /**
     * @param  \Seat\Eveapi\Models\Character\CharacterInfo  $character
     * @param  int  $item_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getFitting(CharacterInfo $character, int $item_id)
    {
        $asset = CharacterAsset::find($item_id);

        return view('web::common.assets.modals.fitting.content', compact('asset'));
    }

    /**
     * @param  \Seat\Eveapi\Models\Character\CharacterInfo  $character
     * @param  int  $item_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getContainer(CharacterInfo $character, int $item_id)
    {
        $asset = CharacterAsset::find($item_id);

        return view('web::character.assets.modals.container.content', compact('asset'));
    }
}
