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


use Illuminate\Http\Request;
use Monolog\Logger;
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
    public function getAssets(int $character_id, Request $request)
    {
        if($request->ajax())
        {

            //$asset_locations =  $this->getCharacterAssetsLocation($character_id, request('extra_search'));

            $asset_locations =  $this->getCharacterAssetsLocation($character_id);



            return Datatables::of($asset_locations)
                ->addColumn('location', function($row) {

                    $assets = $this->getCharacterAssetsAtLocation($row->character_id, $row->location_id)
                        ->get()
                        ->map(function ($value){
                            return $value->quantity * optional($value->type)->volume ?? 0;
                        });
                    $volume = $assets->sum();
                    $number_items = $assets->count();

                    return view('web::character.partials.assets-location',compact('row','volume', 'number_items'));
                })
                ->addColumn('details_url', function ($row){
                    return route('character.view.location.assets',['character_id' => $row->character_id, 'location_id' => $row->location_id]);
                })
                ->filter(function ($query) use ($character_id){
                    if(request()->has('extra_search')){

                        $location_ids = $this->getCharacterAssetsLocation($character_id,\request('extra_search'))
                            ->get()
                            ->pluck('location_id');
                        $query->whereIn('location_id', $location_ids->toArray());
                    }
                }, true)
                ->make(true);

        }

        $assets = $this->getCharacterAssets($character_id);

        return view('web::character.assets', compact('assets', 'locations'));
    }

    public function getLocationAssets(int $character_id) //TODO: refactor this to not use request
    {

        $assets = $this->getCharacterAssetsAtLocation($character_id, request('location_id'));

        return Datatables::of($assets)
            ->editColumn('quantity', function ($row){
                if($row->content->count() < 1)
                    return number($row->quantity,0);
            })
            ->addColumn('type', function ($row){
                return view('web::character.partials.asset-type', compact('row'));
            })
            ->addColumn('volume', function ($row){
                return number_metric($row->quantity * optional($row->type)->volume ?? 0) . "m&sup3";
            })
            ->addColumn('group', function ($row){
                if($row->type)
                    return $row->type->group->groupName;

                return "Unknown";
            })
            ->addColumn('details_url', function ($row){
                if($row->content->count() > 0)
                    return route('character.view.assets.contents', ['character_id' => $row->character_id, 'item_id' => $row->item_id]);

                return "";
            })
            ->make(true);

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

            return Datatables::of($contents)
                ->editColumn('quantity', function ($row){

                    return number($row->quantity,0);
                })
                ->addColumn('type', function ($row){
                    return view('web::character.partials.asset-type', compact('row'));
                })
                ->addColumn('volume', function ($row){
                    return number_metric($row->quantity * optional($row->type)->volume ?? 0) . "m&sup3";
                })
                ->addColumn('group', function ($row){
                    if($row->type)
                        return $row->type->group->groupName;

                    return "Unknown";
                })
                ->make(true);


        //TODO: this getAssetContent is missing a class, maybe remove this?
       /* $contents = $this->getCharacterAssetContents($character_id, $item_id);

        return view('web::partials.assetscontents', compact('contents'));*/
    }
}
