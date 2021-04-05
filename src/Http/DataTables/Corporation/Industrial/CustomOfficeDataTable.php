<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2021 Leon Jacobs
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

namespace Seat\Web\Http\DataTables\Corporation\Industrial;

use Seat\Eveapi\Models\PlanetaryInteraction\CorporationCustomsOffice;
use Yajra\DataTables\Services\DataTable;

/**
 * Class CustomOfficeDataTable.
 *
 * @package Seat\Web\Http\DataTables\Corporation\Industrial
 */
class CustomOfficeDataTable extends DataTable
{
    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function ajax()
    {
        return datatables()
            ->eloquent($this->applyScopes($this->query()))
            ->editColumn('planet.name', function ($row) {
                return view('web::partials.planet', ['planet' => $row->planet])->render();
            })
            ->addColumn('reinforcement', function ($row) {
                return sprintf('between %dh and %dh', $row->reinforce_exit_start, $row->reinforce_exit_end);
            })
            ->addColumn('alliance', function ($row) {
                if ($row->allow_alliance_access)
                    return trans('web::seat.yes');

                return trans('web::seat.no');
            })
            ->addColumn('standings', function ($row) {
                if ($row->allow_access_with_standings)
                    return trans('web::seat.yes');

                return trans('web::seat.no');
            })
            ->editColumn('standing_level', function ($row) {
                switch ($row->standing_level) {
                    case 'terrible':
                        return '<span class="badge badge-danger">Terrible</span>';
                    case 'bad':
                        return '<span class="badge badge-warning">Bad</span>';
                    case 'good':
                        return '<span class="badge badge-info">Good</span>';
                    case 'excellent':
                        return '<span class="badge badge-primary">Excellent</span>';
                    default:
                        return '<span class="badge badge-default">Neutral</span>';
                }
            })
            ->addColumn('tax_alliance_corp', function ($row) {
                return sprintf('<div class="row"><div class="col-md-6">%d%%</div><div class="col-md-6">%d%%</div></div>',
                    round((float) $row->alliance_tax_rate * 100),
                    round((float) $row->corporation_tax_rate * 100));
            })
            ->addColumn('tax_standings', function ($row) {
                return sprintf('<div class="row"><div class="col-md-2 text-blue">%d%%</div><div class="col-md-2 text-info">%d%%</div><div class="col-md-2 text-gray">%d%%</div><div class="col-md-2 text-orange">%d%%</div><div class="col-md-2 text-red">%d%%</div></div>',
                        round((float) $row->excellent_standing_tax_rate * 100),
                        round((float) $row->good_standing_tax_rate * 100),
                        round((float) $row->neutral_standing_tax_rate * 100),
                        round((float) $row->bad_standing_tax_rate * 100),
                        round((float) $row->terrible_standing_tax_rate * 100)
                    );
            })
            ->rawColumns(['planet.name', 'standing_level', 'tax_alliance_corp', 'tax_standings'])
            ->make(true);
    }

    /**
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->postAjax()
            ->columns($this->getColumns());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Seat\Eveapi\Models\PlanetaryInteraction\CorporationCustomsOffice
     */
    public function query()
    {
        return CorporationCustomsOffice::with('planet');
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return [
            ['data' => 'planet.name',       'title' => trans('web::custom_offices.planet')],
            ['data' => 'reinforcement',     'title' => trans('web::custom_offices.reinforcement')],
            ['data' => 'alliance',          'title' => trans('web::custom_offices.alliance')],
            ['data' => 'standings',         'title' => trans('web::custom_offices.standings')],
            ['data' => 'standing_level',    'title' => trans('web::custom_offices.standing_level')],
            ['data' => 'tax_alliance_corp', 'title' => trans('web::custom_offices.tax_alliance_corp')],
            ['data' => 'tax_standings',     'title' => trans('web::custom_offices.tax_standings')],
        ];
    }
}
