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

namespace Seat\Web\Http\DataTables\Common\Financial;

use Yajra\DataTables\Services\DataTable;

/**
 * Class AbstractWalletTransactionDataTable.
 *
 * @package Seat\Web\Http\DataTables\Common\Financial
 */
abstract class AbstractWalletTransactionDataTable extends DataTable
{
    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function ajax()
    {
        return datatables()
            ->eloquent($this->applyScopes($this->query()))
            ->editColumn('date', function ($row) {
                return view('web::partials.date', ['datetime' => $row->date])->render();
            })
            ->editColumn('is_buy', function ($row) {
                return view('web::partials.marketbuysell', ['is_buy' => $row->is_buy])->render();
            })
            ->editColumn('quantity', function ($row) {
                return number($row->quantity, 0);
            })
            ->editColumn('unit_price', function ($row) {
                return number($row->unit_price);
            })
            ->addColumn('total', function ($row) {
                return number($row->quantity * $row->unit_price);
            })
            ->editColumn('type.typeName', function ($row) {
                return view('web::partials.type', [
                    'type_id' => $row->type->typeID,
                    'type_name' => $row->type->typeName,
                    'variation' => $row->type->group->categoryID == 9 ? 'bpc' : 'icon',
                ])->render();
            })
            ->editColumn('location.name', function ($row) {
                return $row->location->name;
            })
            ->editColumn('party.name', function ($row) {
                switch ($row->party->category) {
                    case 'alliance':
                        return view('web::partials.alliance', ['alliance' => $row->party])->render();
                    case 'corporation':
                        return view('web::partials.corporation', ['corporation' => $row->party])->render();
                    case 'character':
                        return view('web::partials.character', ['character' => $row->party])->render();
                    default:
                        return '';
                }
            })
            ->filterColumn('is_buy', function ($query, $keyword) {
                if (strpos('SELL', strtoupper($keyword)) !== false)
                    return $query->where('is_buy', false)
                        ->orWhereNull('is_buy');

                if (strpos('BUY', strtoupper($keyword)) !== false)
                    return $query->where('is_buy', true);

                return $query;
            })
            ->filterColumn('total', function ($query, $keyword) {
                return $query->whereRaw('(unit_price * quantity) LIKE ?', ["%$keyword%"]);
            })
            ->orderColumn('total', '(unit_price * quantity) $1')
            ->rawColumns(['date', 'is_buy', 'type.typeName', 'party.name'])
            ->make(true);
    }

    /**
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->postAjax()
            ->parameters([
                'dom'          => '<"row"<"col-sm-12 col-md-4"l><"col-sm-12 col-md-4 text-center"B><"col-sm-12 col-md-4"f>><"row"<"col-sm-12"tr>><"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                'buttons' => ['postCsv', 'postExcel'],
                'drawCallback' => 'function() { $("[data-toggle=tooltip]").tooltip(); ids_to_names(); }',
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
            ['data' => 'date', 'title' => trans('web::wallet.date')],
            ['data' => 'is_buy', 'title' => trans('web::wallet.order')],
            ['data' => 'type.typeName', 'title' => trans('web::wallet.type')],
            ['data' => 'location.name', 'title' => trans('web::wallet.location')],
            ['data' => 'unit_price', 'title' => trans('web::wallet.price')],
            ['data' => 'quantity', 'title' => trans('web::wallet.quantity')],
            ['data' => 'total', 'title' => trans('web::wallet.total')],
            ['data' => 'party.name', 'title' => trans('web::wallet.party')],
        ];
    }
}
