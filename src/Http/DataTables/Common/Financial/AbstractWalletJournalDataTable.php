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
                return view('web::partials.date', ['datetime' => $row->date]);
            })
            ->editColumn('ref_type', function ($row) {
                return trans(sprintf('web::wallet.%s', $row->ref_type));
            })
            ->editColumn('amount', function ($row) {
                return number($row->amount);
            })
            ->editColumn('balance', function ($row) {
                return number($row->balance);
            })
            ->addColumn('from_party', function ($row) {
                switch ($row->first_party->category) {
                    case 'alliance':
                        return view('web::partials.alliance', ['alliance' => $row->first_party_id]);
                    case 'corporation':
                        return view('web::partials.corporation', ['corporation' => $row->first_party_id]);
                    case 'character':
                        return view('web::partials.character', ['character' => $row->first_party_id]);
                    default:
                        return '';
                }
            })
            ->addColumn('to_party', function ($row) {
                switch ($row->second_party->category) {
                    case 'alliance':
                        return view('web::partials.alliance', ['alliance' => $row->second_party_id]);
                    case 'corporation':
                        return view('web::partials.corporation', ['corporation' => $row->second_party_id]);
                    case 'character':
                        return view('web::partials.character', ['character' => $row->second_party_id]);
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
            ->filterColumn('from_party', function ($query, $keyword) {
                return $query->whereHas('first_party', function ($sub_query) use ($keyword) {
                    return $sub_query->whereRaw('name LIKE ?', ["%$keyword%"]);
                });
            })
            ->filterColumn('to_party', function ($query, $keyword) {
                return $query->whereHas('second_party', function ($sub_query) use ($keyword) {
                    return $sub_query->whereRaw('name LIKE ?', ["%$keyword%"]);
                });
            })
            ->rawColumns(['date', 'from_party', 'to_party'])
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
            ['data' => 'from_party', 'title' => trans('web::wallet.from_party')],
            ['data' => 'to_party', 'title' => trans('web::wallet.to_party')],
            ['data' => 'amount', 'title' => trans('web::wallet.amount')],
            ['data' => 'balance', 'title' => trans('web::wallet.balance')],
        ];
    }
}
