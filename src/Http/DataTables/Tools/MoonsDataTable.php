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

use Seat\Eveapi\Models\Sde\Moon;
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
            ->editColumn('region.name', function ($row) {
                return $row->region->name;
            })
            ->editColumn('constellation.name', function ($row) {
                return $row->constellation->name;
            })
            ->editColumn('solar_system.name', function ($row) {
                return $row->solar_system->name;
            })
            ->editColumn('planet.name', function ($row) {
                return $row->planet->name;
            })
            ->editColumn('solar_system.sovereignty', function ($row) {
                switch (true) {
                    case ! is_null($row->solar_system->sovereignty->faction_id):
                        return view('web::partials.faction', ['faction' => $row->solar_system->sovereignty->faction]);
                    case ! is_null($row->solar_system->sovereignty->alliance_id):
                        return view('web::partials.alliance', ['alliance' => $row->solar_system->sovereignty->alliance]);
                    case ! is_null($row->solar_system->sovereignty->corporation_id):
                        return view('web::partials.corporation', ['corporation' => $row->solar_system->sovereignty->corporation]);
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
            ->make(true);
    }

    /**
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->dom('Brtip')
            ->postAjax()
            ->columns($this->getColumns())
            ->addAction()
            ->parameters([
                'drawCallback' => 'function() { ids_to_names(); }',
            ])
            ->ajax([
                'data' => 'function (d) {
                    d.region_id         = $("#dt-filters-region").val() === null ? 0 : $("#dt-filters-region").val();
                    d.constellation_id  = $("#dt-filters-constellation").val() === null ? 0 : $("#dt-filters-constellation").val();
                    d.system_id         = $("#dt-filters-system").val() === null ? 0 : $("#dt-filters-system").val();
                    d.rank_selection    = $("#dt-filters-rank").val() === null ? [] : $("#dt-filters-rank").val();
                    d.product_selection = $("#dt-filters-product").val() === null ? [] : $("#dt-filters-product").val();
                }',
            ]);
    }

    /**
     * @return mixed
     */
    public function query()
    {
        return Moon::has('content')
            ->with('planet', 'solar_system', 'constellation', 'region', 'solar_system.sovereignty', 'solar_system.sovereignty.faction',
                   'solar_system.sovereignty.alliance', 'solar_system.sovereignty.corporation', 'content');
    }

    /**
     * @return array
     */
    public function getColumns() {
        return [
            ['data' => 'name', 'title' => trans_choice('web::moons.moon', 1)],
            ['data' => 'region.name', 'title' => trans_choice('web::moons.region', 1)],
            ['data' => 'constellation.name', 'title' => trans_choice('web::moons.constellation', 1)],
            ['data' => 'solar_system.name', 'title' => trans_choice('web::moons.system', 1)],
            ['data' => 'planet.name', 'title' => trans_choice('web::moons.planet', 1)],
            ['data' => 'solar_system.sovereignty', 'title' => trans_choice('web::moons.sovereignty', 1), 'orderable' => false, 'searchable' => false],
            ['data' => 'indicators', 'title' => trans_choice('web::moons.indicator', 0), 'orderable' => false, 'searchable' => false],
        ];
    }
}
