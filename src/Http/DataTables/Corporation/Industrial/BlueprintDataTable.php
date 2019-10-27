<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015, 2016, 2017, 2018, 2019  Leon Jacobs
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

use Seat\Eveapi\Models\Corporation\CorporationBlueprint;
use Seat\Eveapi\Models\Corporation\CorporationDivision;
use Seat\Web\Http\DataTables\Common\Industrial\AbstractBlueprintDataTable;

/**
 * Class BlueprintDataTable.
 *
 * @package Seat\Web\Http\DataTables\Corporation\Industrial
 */
class BlueprintDataTable extends AbstractBlueprintDataTable
{
    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return CorporationBlueprint::with('type');
    }

    public function ajaxDataResponse()
    {
        $divisions = CorporationDivision::where('type', 'hangar')
            ->where('corporation_id', $this->request()->corporation_id)
            ->get();

        return parent::ajaxDataResponse()
            ->editColumn('location_flag', function ($row) use ($divisions) {
                switch ($row->location_flag) {
                    case 'CorpSAG1':
                        return $divisions->where('division', 1)->first() ?
                            $divisions->where('division', 1)->first()->name : $row->location_flag;
                    case 'CorpSAG2':
                        return $divisions->where('division', 2)->first() ?
                            $divisions->where('division', 2)->first()->name : $row->location_flag;
                    case 'CorpSAG3':
                        return $divisions->where('division', 3)->first() ?
                            $divisions->where('division', 3)->first()->name : $row->location_flag;
                    case 'CorpSAG4':
                        return $divisions->where('division', 4)->first() ?
                            $divisions->where('division', 4)->first()->name : $row->location_flag;
                    case 'CorpSAG5':
                        return $divisions->where('division', 5)->first() ?
                            $divisions->where('division', 5)->first()->name : $row->location_flag;
                    case 'CorpSAG6':
                        return $divisions->where('division', 6)->first() ?
                            $divisions->where('division', 6)->first()->name : $row->location_flag;
                    case 'CorpSAG7':
                        return $divisions->where('division', 7)->first() ?
                            $divisions->where('division', 7)->first()->name : $row->location_flag;
                    default:
                        return preg_replace('([A-Z])', ' $0', $row->location_flag);
                }
            })
            ->filterColumn('location_flag', function ($query, $filter) use ($divisions) {
                $division_candidates = $divisions->filter(function ($division, $key) use ($filter) {
                    return strpos(strtolower($division->name), strtolower($filter)) !== false;
                });

                $query->where(function ($sub_query) use ($filter, $division_candidates) {
                    if ($division_candidates->count() > 0) {
                        $sub_query->whereIn('location_flag', $division_candidates->map(function ($division) {
                            return sprintf('CorpSAG%d', $division->division);
                        })->toArray());
                    }

                    $sub_query->orWhereRaw('location_flag like ?', ["%$filter%"]);
                });
            });
    }

    /**
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return parent::html()->ajax([
            'data' => 'function(d) { d.filters = {}; $("[data-filter-field].dt-filters.active").each(function (i, e) { var a = $(e); var field = a.data("filter-field"); var value = a.data("filter-value"); if (! d.filters[field]) { d.filters[field] = []; } d.filters[field].push(value); }); }',
        ]);
    }
}
