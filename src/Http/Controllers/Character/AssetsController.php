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

namespace Seat\Web\Http\Controllers\Character;

use Seat\Eveapi\Models\Assets\CharacterAsset;
use Seat\Eveapi\Models\RefreshToken;
use Seat\Services\Repositories\Character\Assets;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\DataTables\Character\Intel\AssetDataTable;
use Seat\Web\Http\DataTables\Scopes\CharacterScope;
use Seat\Web\Models\User;

/**
 * Class AssetsController.
 * @package Seat\Web\Http\Controllers\Character
 */
class AssetsController extends Controller
{
    use Assets;

    /**
     * @param int $character_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAssetsView(int $character_id, AssetDataTable $dataTable)
    {
        $token = RefreshToken::where('character_id', $character_id)->first();
        $characters = collect();
        if ($token) {
            $characters = User::with('characters')->find($token->user_id)->characters;
        }

        return $dataTable->addScope(new CharacterScope('character.asset', $character_id, request()->input('characters')))
            ->render('web::character.assets.assets', compact('characters'));
    }

    /**
     * @param int $corporation_id
     * @param int $item_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getFitting(int $character_id, int $item_id)
    {
        $asset = CharacterAsset::find($item_id);

        return view('web::common.assets.modals.fitting.content', compact('asset'));
    }

    /**
     * @param int $corporation_id
     * @param int $item_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getContainer(int $character_id, int $item_id)
    {
        $asset = CharacterAsset::find($item_id);

        return view('web::character.assets.modals.container.content', compact('asset'));
    }
}
