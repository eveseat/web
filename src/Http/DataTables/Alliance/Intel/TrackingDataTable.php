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

namespace Seat\Web\Http\DataTables\Alliance\Intel;

use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Yajra\DataTables\Services\DataTable;

/**
 * Class CorporationDataTable.
 *
 * @package Seat\Web\Http\DataTables\Alliance\Intel
 */
class TrackingDataTable extends DataTable
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
            ->editColumn('name', function ($row) {
                return view('web::partials.corporation', ['corporation' => $row])->render();
            })
            ->editColumn('tax_rate', function ($row) {
                return number($row->tax_rate * 100) . '%';
            })
            ->editColumn('member_count', function ($row) {
                if ($row->member_limit->limit < 1)
                    return sprintf('%d/%d (100.00%%)', $row->member_count, $row->member_count);

                return sprintf('%d/%d (%s%%)',
                    $row->member_count, $row->member_limit->limit, number($row->member_count / $row->member_limit->limit * 100));
            })
            ->editColumn('ceo.name', function ($row) {
                return view('web::partials.character', ['character' => $row->ceo])->render();
            })
            ->editColumn('tracking', function ($row) {
                // <dd>{{ $trackings }} / {{ $sheet->member_count }} ({{ number_format($trackings/$sheet->member_count * 100, 2) }}%) {{ trans_choice('web::seat.valid_token', $sheet->member_count) }}</dd>

                $trackings = $row->characters->reject(function ($char) {
                    return is_null($char->refresh_token);
                })->count();

                return $trackings . '/' . $row->member_count . ' (' . number_format($trackings / $row->member_count * 100, 2) . '%)';
            })
            ->editColumn('action', function ($row) {
                return view('web::corporation.partials.delete', compact('row'));
            })
            ->rawColumns(['name', 'ceo.name'])
            ->make(true);
    }

    /**
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->addAction()
            ->orderBy(0, 'asc')
            ->postAjax()
            ->parameters([
                'drawCallback' => 'function() { ids_to_names(); }',
            ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Seat\Eveapi\Models\Corporation\CorporationInfo
     */
    public function query()
    {
        return CorporationInfo::player()->with('member_limit', 'ceo', 'alliance')
            ->select('corporation_infos.*');
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return [
            ['data' => 'name', 'title' => trans_choice('web::seat.name', 1)],
            ['data' => 'ticker', 'title' => trans('web::seat.ticker')],
            ['data' => 'ceo.name', 'title' => trans('web::seat.ceo')],
            ['data' => 'tax_rate', 'title' => trans('web::seat.tax_rate')],
            ['data' => 'member_count', 'title' => trans('web::seat.member_count')],
            ['data' => 'tracking', 'title' => trans_choice('web::seat.valid_token', 2)],
        ];
    }
}
