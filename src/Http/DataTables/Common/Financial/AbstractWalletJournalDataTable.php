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

use Illuminate\Support\Facades\Lang;
use Yajra\DataTables\Services\DataTable;

/**
 * Class AbstractWalletJournalDataTable.
 *
 * @package Seat\Web\Http\DataTables\Common\Financial
 */
abstract class AbstractWalletJournalDataTable extends DataTable
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
            ->editColumn('ref_type', function ($row) {
                return view('web::common.wallets.journaltranstype', compact('row'));
            })
            ->editColumn('amount', function ($row) {
                return number($row->amount);
            })
            ->editColumn('balance', function ($row) {
                return number($row->balance);
            })
            ->editColumn('first_party.name', function ($row) {
                switch ($row->first_party->category) {
                    case 'alliance':
                        return view('web::partials.alliance', ['alliance' => $row->first_party])->render();
                    case 'corporation':
                        return view('web::partials.corporation', ['corporation' => $row->first_party])->render();
                    case 'character':
                        return view('web::partials.character', ['character' => $row->first_party])->render();
                    default:
                        return '';
                }
            })
            ->editColumn('second_party.name', function ($row) {
                switch ($row->second_party->category) {
                    case 'alliance':
                        return view('web::partials.alliance', ['alliance' => $row->second_party])->render();
                    case 'corporation':
                        return view('web::partials.corporation', ['corporation' => $row->second_party])->render();
                    case 'character':
                        return view('web::partials.character', ['character' => $row->second_party])->render();
                    default:
                        return '';
                }
            })
            ->filterColumn('ref_type', function ($query, $keyword) {
                $captions = Lang::get('web::wallet');
                $ref_types = array_keys(
                    array_filter($captions, function ($value) use ($keyword) {
                        return strpos(strtoupper($value), strtoupper($keyword)) !== false;
                    }));

                $query->whereIn('ref_type', $ref_types);
            })
            ->rawColumns(['date', 'first_party.name', 'second_party.name'])
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
            ['data' => 'ref_type', 'title' => trans('web::wallet.ref_type')],
            ['data' => 'first_party.name', 'title' => trans('web::wallet.from_party')],
            ['data' => 'second_party.name', 'title' => trans('web::wallet.to_party')],
            ['data' => 'amount', 'title' => trans('web::wallet.amount')],
            ['data' => 'balance', 'title' => trans('web::wallet.balance')],
        ];
    }
}
