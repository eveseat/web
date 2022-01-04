<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2022 Leon Jacobs
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

namespace Seat\Web\Http\DataTables\Common\Financial;

use Yajra\DataTables\Services\DataTable;

/**
 * Class AbstractMarketDataTable.
 *
 * @package Seat\Web\Http\DataTables\Common\Financial
 */
abstract class AbstractMarketDataTable extends DataTable
{
    /**
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function ajax()
    {
        return datatables()
            ->eloquent($this->applyScopes($this->query()))
            ->editColumn('issued', function ($row) {
                return view('web::partials.date', ['datetime' => $row->issued])->render();
            })
            ->editColumn('is_buy_order', function ($row) {
                return view('web::partials.marketbuysell', ['is_buy' => $row->is_buy_order])->render();
            })
            ->editColumn('price', function ($row) {
                return number($row->price);
            })
            ->addColumn('expires', function ($row) {
                return view('web::partials.date', ['datetime' => carbon($row->issued)->addDays($row->duration)])->render();
            })
            ->editColumn('volume_total', function ($row) {
                return sprintf('%s / %s', number($row->volume_remain, 0), number($row->volume_total, 0));
            })
            ->addColumn('total', function ($row) {
                return number($row->price * $row->volume_total);
            })
            ->editColumn('type.typeName', function ($row) {
                return view('web::partials.type', [
                    'type_id' => $row->type->typeID,
                    'type_name' => $row->type->typeName,
                    'variation' => $row->type->group->categoryID == 9 ? 'bpc' : 'icon',
                ])->render();
            })
            ->filterColumn('is_buy_order', function ($query, $keyword) {
                if (strpos('SELL', strtoupper($keyword)) !== false)
                    return $query->where('is_buy_order', false)
                        ->orWhereNull('is_buy_order');

                if (strpos('BUY', strtoupper($keyword)) !== false)
                    return $query->where('is_buy_order', true);

                return $query;
            })
            ->filterColumn('volume', function ($query, $keyword) {
                return $query->where(function ($sub_query) use ($keyword) {
                    $sub_query->whereRaw('volume_remain LIKE ?', ["%$keyword%"]);
                    $sub_query->orWhereRaw('volume_total LIKE ?', ["%$keyword%"]);
                });
            })
            ->filterColumn('total', function ($query, $keyword) {
                return $query->whereRaw('(price * volume_total) LIKE ?', ["%$keyword%"]);
            })
            ->filterColumn('type', function ($query, $keyword) {
                $query->whereHas('type', function ($sub_query) use ($keyword) {
                    $sub_query->whereRaw('typeName LIKE ?', ["%$keyword%"]);
                });
            })
            ->orderColumn('expires', 'DATE_ADD(issued, INTERVAL duration DAY) $1')
            ->orderColumn('total', '(price * volume_total) $1')
            ->orderColumn('volume', '(volume_total - volume_remain) $1')
            ->rawColumns(['issued', 'is_buy_order', 'expires', 'type.typeName'])
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
            ->parameters([
                'dom'          => '<"row"<"col-sm-12 col-md-4"l><"col-sm-12 col-md-4 text-center"B><"col-sm-12 col-md-4"f>><"row"<"col-sm-12"tr>><"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                'buttons' => ['postCsv', 'postExcel'],
                'drawCallback' => 'function() { $("[data-toggle=tooltip]").tooltip(); }',
            ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    abstract public function query();

    /**
     * @return array
     */
    public function getColumns()
    {
        return [
            ['data' => 'issued', 'title' => trans('web::market.issued')],
            ['data' => 'expires', 'title' => trans('web::market.expires')],
            ['data' => 'is_buy_order', 'title' => trans('web::market.order')],
            ['data' => 'range', 'title' => trans('web::market.range')],
            ['data' => 'volume_total', 'title' => trans('web::market.volume')],
            ['data' => 'price', 'title' => trans('web::market.price')],
            ['data' => 'total', 'title' => trans('web::market.total')],
            ['data' => 'type.typeName', 'title' => trans('web::market.type')],
        ];
    }
}
