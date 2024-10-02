<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to present Leon Jacobs
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

use Illuminate\Http\JsonResponse;
use Seat\Eveapi\Models\Industry\CorporationIndustryMiningExtraction;
use Seat\Web\Models\UniverseMoonReport;
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
    public function ajax(): JsonResponse
    {
        return datatables()
            ->eloquent($this->applyScopes($this->query()))
            ->editColumn('moon.region.name', function ($row) {
                return $row->moon->region->name;
            })
            ->editColumn('moon.constellation.name', function ($row) {
                return $row->moon->constellation->name;
            })
            ->editColumn('moon.solar_system.name', function ($row) {
                return $row->moon->solar_system->name;
            })
            ->editColumn('moon.planet.name', function ($row) {
                return $row->moon->planet->name;
            })
            ->editColumn('moon.solar_system.sovereignty', function ($row) {
                return view('web::partials.sovereignty', ['sovereignty' => $row->moon->solar_system->sovereignty])->render();
            })
            ->editColumn('indicators', function ($row) {
                return view('web::tools.moons.partials.indicators', compact('row'))->render();
            })
            ->editColumn('action', function ($row) {
                return view('web::tools.moons.buttons.action', compact('row'))->render();
            })
            ->editColumn('raw_value', function ($row) {
                return number_format($row->raw_value, 2) . ' ISK';
            })
            ->editColumn('refined_value', function ($row) {
                return number_format($row->refined_value, 2) . ' ISK';
            })
            ->rawColumns(['moon.solar_system.sovereignty', 'indicators', 'action'])
            ->with('stats', [
                'ubiquitous' => $this->applyScopes(UniverseMoonReport::query()->ubiquitous())->count(),
                'common' => $this->applyScopes(UniverseMoonReport::query()->common())->count(),
                'uncommon' => $this->applyScopes(UniverseMoonReport::query()->uncommon())->count(),
                'rare' => $this->applyScopes(UniverseMoonReport::query()->rare())->count(),
                'exceptional' => $this->applyScopes(UniverseMoonReport::query()->exceptional())->count(),
            ])
            ->toJson();
    }

    /**
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->dom('Brtip')
            ->columns($this->getColumns())
            ->addAction()
            ->parameters([
                'drawCallback' => 'function(settings) { ids_to_names(); $("[data-toggle=tooltip]").tooltip(); $(".moon-stats .badge-success").text(settings.json.stats.ubiquitous); $(".moon-stats .badge-primary").text(settings.json.stats.common); $(".moon-stats .badge-info").text(settings.json.stats.uncommon); $(".moon-stats .badge-warning").text(settings.json.stats.rare); $(".moon-stats .badge-danger").text(settings.json.stats.exceptional);}',
            ])
            ->postAjax([
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
        $mining_volume = CorporationIndustryMiningExtraction::BASE_DRILLING_VOLUME;
        $reprocessing_yield = floatval(setting('reprocessing_yield') ?: 0.80);
        $hours_per_month = 30 * 24;

        return UniverseMoonReport::with('moon', 'moon.planet', 'moon.solar_system', 'moon.constellation', 'moon.region',
                'moon.solar_system.sovereignty', 'moon.solar_system.sovereignty.faction',
                'moon.solar_system.sovereignty.alliance', 'moon.solar_system.sovereignty.corporation', 'content')
            ->select()
            //this is  not an SQL injection since $mining_volume is a constant int on a class
            ->selectRaw('(SELECT SUM(rate * ? * ?/invTypes.volume*market_prices.adjusted_price) FROM universe_moon_contents JOIN invTypes ON invTypes.typeID=universe_moon_contents.type_id JOIN market_prices ON market_prices.type_id=universe_moon_contents.type_id WHERE moon_id=universe_moon_reports.moon_id) as raw_value', [$mining_volume, $hours_per_month])
            ->selectRaw('(select SUM((select SUM(invTypeMaterials.quantity*market_prices.adjusted_price) from invTypeMaterials join market_prices on market_prices.type_id=invTypeMaterials.materialTypeID where invTypeMaterials.typeID=universe_moon_contents.type_id) * universe_moon_contents.rate * ? * ? / invTypes.volume * ? / 100) from universe_moon_contents join invTypes on invTypes.typeID=universe_moon_contents.type_id where universe_moon_contents.moon_id=universe_moon_reports.moon_id) as refined_value', [$mining_volume, $hours_per_month, $reprocessing_yield]);
    }

    /**
     * @return array
     */
    public function getColumns() {
        return [
            ['data' => 'moon.name', 'title' => trans_choice('web::moons.moon', 1)],
            ['data' => 'moon.region.name', 'title' => trans_choice('web::moons.region', 1)],
            ['data' => 'moon.constellation.name', 'title' => trans_choice('web::moons.constellation', 1)],
            ['data' => 'moon.solar_system.name', 'title' => trans_choice('web::moons.system', 1)],
            ['data' => 'moon.planet.name', 'title' => trans_choice('web::moons.planet', 1)],
            ['data' => 'moon.solar_system.sovereignty', 'title' => trans_choice('web::moons.sovereignty', 1), 'orderable' => false, 'searchable' => false],
            ['data' => 'indicators', 'title' => trans_choice('web::moons.indicator', 0), 'orderable' => false, 'searchable' => false],
            ['data' => 'raw_value', 'title' => trans('web::moons.raw_value'), 'searchable' => false],
            ['data' => 'refined_value', 'title' => trans('web::moons.refined_value'), 'searchable' => false],
        ];
    }
}
