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

namespace Seat\Web\Http\DataTables\Common\Intel;

use Seat\Eveapi\Models\Corporation\CorporationDivision;
use Seat\Web\Http\DataTables\Common\IColumn;
use Yajra\DataTables\Services\DataTable;

/**
 * Class AbstractAssetDataTable.
 *
 * @package Seat\Web\Http\DataTables\Common\Intel
 */
abstract class AbstractAssetDataTable extends DataTable
{
    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function ajax()
    {
        $divisions = CorporationDivision::where('type', 'hangar')
            ->where('corporation_id', request()->route()->parameter('corporation_id', 0))
            ->get();

        return datatables()
            ->eloquent($this->applyScopes($this->query()))
            ->editColumn('type.typeName', function ($row) {
                return view('web::partials.type', [
                    'type_id'   => $row->type->typeID,
                    'type_name' => $row->name ? sprintf('%s (%s)', $row->name, $row->type->typeName) : $row->type->typeName,
                    'variation' => $row->type->group->categoryID == 9 ? 'bpc' : 'icon',
                ]);
            })
            ->editColumn('quantity', function ($row) {
                return number_format($row->quantity);
            })
            ->editColumn('type.volume', function ($row) {
                return number_metric($row->quantity * $row->type->volume) . 'm&sup3';
            })
            ->editColumn('location_flag', function ($row) use ($divisions) {
                switch ($row->location_flag) {
                    case 'CorpSAG1':
                        return $divisions->where('division', 1)->first()->name;
                    case 'CorpSAG2':
                        return $divisions->where('division', 2)->first()->name;
                    case 'CorpSAG3':
                        return $divisions->where('division', 3)->first()->name;
                    case 'CorpSAG4':
                        return $divisions->where('division', 4)->first()->name;
                    case 'CorpSAG5':
                        return $divisions->where('division', 5)->first()->name;
                    case 'CorpSAG6':
                        return $divisions->where('division', 6)->first()->name;
                    case 'CorpSAG7':
                        return $divisions->where('division', 7)->first()->name;
                    case 'CorpDeliveries':
                        return 'Delivery Hangar';
                    default:
                        return ($row->location_flag == 'AutoFit' && $row->location_type == 'solar_system') ? 'In Space' : '';
                }
            })
            ->editColumn('action', function ($row) {
                if ($row->content->isNotEmpty()) {
                    if (in_array($row->type->group->categoryID, [6, 65]))
                        return view('web::common.assets.buttons.fitting', compact('row'));

                    return view('web::common.assets.buttons.cargo');
                }

                return '';
            })
            ->addColumn('station', $this->getStationColumn())
            ->setRowClass(function ($row) {
                if (in_array('AssetSafety', [$row->location_flag, $row->container->location_flag, $row->container->container->location_flag]))
                    return 'table-danger';

                if (in_array('CorpDeliveries', [$row->location_flag, $row->container->location_flag, $row->container->container->location_flag]))
                    return 'table-warning';

                return '';
            })
            ->rawColumns(['type.volume'])
            ->make(true);
    }

    /**
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->addAction();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    abstract public function query();

    /**
     * @return \Seat\Web\Http\DataTables\Common\IColumn
     */
    abstract protected function getStationColumn(): IColumn;

    /**
     * @return array
     */
    public function getColumns()
    {
        return [
            ['data' => 'type.typeName', 'title' => trans_choice('web::seat.type', 1)],
            ['data' => 'quantity', 'title' => trans('web::seat.quantity')],
            ['data' => 'type.volume', 'title' => trans('web::seat.volume')],
            ['data' => 'type.group.groupName', 'title' => trans_choice('web::seat.group', 1)],
            ['data' => 'location_flag', 'title' => trans_choice('web::assets.division', 1)],
            ['data' => 'station', 'title' => trans('web::assets.station_or_structure'), 'searchable' => false, 'orderable' => false],
            ['data' => 'name', 'visible' => false],
        ];
    }
}
