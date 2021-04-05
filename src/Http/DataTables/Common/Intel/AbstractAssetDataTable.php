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

namespace Seat\Web\Http\DataTables\Common\Intel;

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
        $location_column = $this->getLocationFlagColumn($this);
        $station_column = $this->getStationColumn($this);

        $ajax = datatables()
            ->eloquent($this->applyScopes($this->query()))
            ->editColumn('type.typeName', function ($row) {
                return view('web::partials.type', [
                    'type_id'   => $row->type->typeID,
                    'type_name' => $row->name ? sprintf('%s (%s)', $row->name, $row->type->typeName) : $row->type->typeName,
                    'variation' => $row->type->group->categoryID == 9 ? 'bpc' : 'icon',
                ])->render();
            })
            ->editColumn('quantity', function ($row) {
                return number($row->quantity, 0);
            })
            ->editColumn('type.volume', function ($row) {
                return number_metric($row->quantity * $row->type->volume) . 'm&sup3';
            })
            ->editColumn('location_flag', $location_column)
            ->editColumn('action', function ($row) {
                if ($row->content->isNotEmpty()) {
                    if (in_array($row->type->group->categoryID, [6, 65]))
                        return view('web::common.assets.buttons.fitting', compact('row'))->render();

                    return view('web::common.assets.buttons.cargo', compact('row'))->render();
                }

                return '';
            })
            ->addColumn('station', $station_column)
            ->filterColumn('location_flag', $location_column)
            ->filterColumn('station', $station_column)
            ->filterColumn('type.typeName', function ($query, $keyword) {
                $query->whereHas('type', function ($item) use ($keyword) {
                    $item->whereRaw('typeName LIKE ?', ["%{$keyword}%"]);
                })->orWhereHas('content.type', function ($content) use ($keyword) {
                    $content->whereRaw('typeName LIKE ?', ["%{$keyword}%"]);
                })->orWhereHas('content.content.type', function ($content) use ($keyword) {
                    $content->whereRaw('typeName LIKE ?', ["%$keyword%"]);
                });
            })
            ->setRowClass(function ($row) {
                if (in_array('AssetSafety', [$row->location_flag, $row->container->location_flag, $row->container->container->location_flag]))
                    return 'table-danger';

                if (in_array('CorpDeliveries', [$row->location_flag, $row->container->location_flag, $row->container->container->location_flag]))
                    return 'table-warning';

                return '';
            })
            ->rawColumns(['type.typeName', 'type.volume', 'action']);

        foreach ($this->extraColumns() as $name => $column) {
            $ajax->addColumn($name, $column);
        }

        return $ajax->make(true);
    }

    /**
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->postAjax()
            ->columns($this->getColumns())
            ->orderBy(0, 'asc')
            ->addAction();
    }

    /**
     * Give ability to define extra column to base class.
     *
     * @return \Seat\Web\Http\DataTables\Common\IColumn[]
     */
    protected function extraColumns(): array
    {
        return [];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    abstract public function query();

    /**
     * @param self $table
     * @return \Seat\Web\Http\DataTables\Common\IColumn
     */
    abstract protected function getLocationFlagColumn($table): IColumn;

    /**
     * @param self $table
     * @return \Seat\Web\Http\DataTables\Common\IColumn
     */
    abstract protected function getStationColumn($table): IColumn;

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
            ['data' => 'station', 'title' => trans('web::assets.station_or_structure'), 'orderable' => false],
            ['data' => 'name', 'visible' => false],
        ];
    }
}
