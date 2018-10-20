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
use Yajra\Datatables\Datatables;

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
    public function getAssetsView(int $character_id)
    {

        return view('web::character.assets');
    }

    /**
     * @param int $character_id
     * @param int $item_id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAssetsContents(int $character_id, int $item_id)
    {

            $contents = $this->getCharacterAssetContentsBuilder($character_id, $item_id);

            return Datatables::of($contents)
                ->editColumn('quantity', function ($row) {

                    return number($row->quantity, 0);
                })
                ->addColumn('type', function ($row) {
                    return view('web::character.partials.asset-type', compact('row'));
                })
                ->addColumn('volume', function ($row) {
                    return number_metric($row->quantity * optional($row->type)->volume ?? 0) . 'm&sup3';
                })
                ->addColumn('group', function ($row) {
                    if($row->type)
                        return $row->type->group->groupName;

                    return 'Unknown';
                })
                ->make(true);
    }

    /**
     * @param int $character_id
     *
     * @return mixed
     */
    public function getCharacterAssets(int $character_id)
    {
        if(request('all_linked_characters') === 'false')
            $character_ids = collect($character_id);

        if(request('all_linked_characters') === 'true')
            $character_ids = auth()->user()->group->users
                ->filter(function ($user) {
                    if(! $user->name === 'admin' || $user->id === 1)
                        return false;

                    return true;
                })
                ->pluck('id');

        $assets = $this->getCharacterAssetsBuilder($character_ids);

        return Datatables::of($assets)
            ->editColumn('quantity', function ($row) {
                if($row->content->count() < 1)
                    return number($row->quantity, 0);
            })
            ->editColumn('item', function ($row) {
                return view('web::character.partials.asset-type', compact('row'));
            })
            ->editColumn('volume', function ($row) {
                return number_metric($row->quantity * optional($row->type)->volume ?? 0) . 'm&sup3';
            })
            ->addColumn('group', function ($row) {
                if($row->type)
                    return $row->type->group->groupName;

                return 'Unknown';
            })
            ->addColumn('details_url', function ($row) {
                if($row->content->count() > 0)
                    return route('character.view.assets.contents', ['character_id' => $row->character_id, 'item_id' => $row->item_id]);

                return '';
            })
            ->make(true);

    }
}
