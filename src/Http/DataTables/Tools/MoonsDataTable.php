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

namespace Seat\Web\Http\DataTables\Tools;

use Seat\Eveapi\Models\Sde\MapDenormalize;
use Seat\Eveapi\Models\Universe\UniverseMoonContent;
use Yajra\DataTables\Services\DataTable;

/**
 * Class MoonsDataTable.
 *
 * @package Seat\Web\Http\DataTables\Tools
 */
class MoonsDataTable extends DataTable
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return datatables()
            ->eloquent($this->applyScopes($this->query()))
            ->editColumn('region', function ($item) {
                return $item->region->itemName;
            })
            ->editColumn('constellation', function ($item) {
                return $item->constellation->itemName;
            })
            ->editColumn('system', function ($item) {
                return $item->system->itemName;
            })
            ->editColumn('planet', function ($item) {
                return $item->planet->itemName;
            })
            ->editColumn('sovereignty', function ($row) {
                switch (true) {
                    case ! is_null($row->system->sovereignty->faction_id):
                        return view('web::partials.faction', ['faction' => $row->system->sovereignty->faction]);
                    case ! is_null($row->system->sovereignty->alliance_id):
                        return view('web::partials.alliance', ['alliance' => $row->system->sovereignty->alliance]);
                    case ! is_null($row->system->sovereignty->corporation_id):
                        return view('web::partials.corporation', ['corporation' => $row->system->sovereignty->corporation]);
                    default:
                        return '';
                }
            })
            ->editColumn('indicators', function ($row) {
                return view('web::tools.moons.partials.indicators', compact('row'));
            })
            ->editColumn('action', function ($row) {
                return view('web::tools.moons.buttons.show', compact('row'));
            })
            ->orderColumn('region', 'regionID $1')
            ->orderColumn('constellation','constellationID $1')
            ->orderColumn('system', 'solarSystemID $1')
            ->make(true);
    }

    /**
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->postAjax()
            ->columns($this->getColumns())
            ->addAction()
            ->parameters([
                'drawCallback' => 'function() { ids_to_names(); }',
            ])
            ->ajax([
                'data' => 'function (d) { 
                    d.region_id         = $("#region-selector").val();
                    d.constellation_id  = $("#constellation-selector").val();
                    d.system_id         = $("#system-selector").val();
                    d.moon_selection    = $("#moon-content-selector").val();
                    d.moon_inclusive    = $("#moon-content-inclusive-selector:checked").is(":checked");
                }',
            ]);
    }

    /**
     * @return mixed
     */
    public function query()
    {
        return MapDenormalize::whereIn(
            'itemID', UniverseMoonContent::groupBy('moon_id')->pluck('moon_id'))
            ->with('planet', 'system', 'constellation', 'region', 'sovereignty', 'sovereignty.faction',
                   'sovereignty.alliance', 'sovereignty.corporation', 'moon_contents', 'moon_contents.type');
    }

    /**
     * @return array
     */
    public function getColumns() {
        return [
            ['data' => 'itemName', 'title' => trans_choice('web::moons.moon', 1)],
            [
                'data'  => 'region',
                'title' => trans_choice('web::moons.region', 1),
            ],
            [
                'data'  => 'constellation',
                'title' => trans_choice('web::moons.constellation', 1),
            ],
            [
                'data'  => 'system',
                'title' => trans_choice('web::moons.system', 1),
            ],
            [
                'data'  => 'planet',
                'title' => trans_choice('web::moons.planet', 1),
            ],
            [
                'data'  => 'sovereignty',
                'title' => trans_choice('web::moons.sovereignty', 1),
                'orderable' => false,
            ],
            [
                'data'  => 'indicators',
                'title' => trans_choice('web::moons.indicator', 0),
                'orderable' => false,
            ],
        ];
    }
}
