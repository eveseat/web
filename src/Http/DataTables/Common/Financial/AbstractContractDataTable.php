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

namespace Seat\Web\Http\DataTables\Common\Financial;

use Illuminate\Support\Facades\Lang;
use Yajra\DataTables\Services\DataTable;

/**
 * Class AbstractContractDataTable.
 *
 * @package Seat\Web\Http\DataTables\Common\Financial
 */
abstract class AbstractContractDataTable extends DataTable
{
    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function ajax()
    {
        return datatables()
            ->eloquent($this->applyScopes($this->query()))
            ->editColumn('action', function ($row) {
                return view('web::common.contracts.buttons.details', compact('row'));
            })
            ->editColumn('detail.date_issued', function ($row) {
                return view('web::partials.date', ['datetime' => $row->detail->date_issued]);
            })
            ->editColumn('detail.type', function ($row) {
                return trans(sprintf('web::contract.%s', $row->detail->type));
            })
            ->editColumn('detail.issuer.name', function ($row) {
                switch ($row->detail->issuer->category) {
                    case 'alliance':
                        return view('web::partials.alliance', ['alliance' => $row->detail->issuer]);
                    case 'corporation':
                        return view('web::partials.corporation', ['corporation' => $row->detail->issuer]);
                    case 'character':
                        return view('web::partials.character', ['character' => $row->detail->issuer]);
                    default:
                        return '';
                }
            })
            ->editColumn('detail.assignee.name', function ($row) {
                switch ($row->detail->assignee->category) {
                    case 'alliance':
                        return view('web::partials.alliance', ['alliance' => $row->detail->assignee]);
                    case 'corporation':
                        return view('web::partials.corporation', ['corporation' => $row->detail->assignee]);
                    case 'character':
                        return view('web::partials.character', ['character' => $row->detail->assignee]);
                    default:
                        return '';
                }
            })
            ->editColumn('detail.acceptor.name', function ($row) {
                if ($row->detail->acceptor->entity_id == 0)
                    return '';

                switch ($row->detail->acceptor->category) {
                    case 'alliance':
                        return view('web::partials.alliance', ['alliance' => $row->detail->acceptor]);
                    case 'corporation':
                        return view('web::partials.corporation', ['corporation' => $row->detail->acceptor]);
                    case 'character':
                        return view('web::partials.character', ['character' => $row->detail->acceptor]);
                    default:
                        return '';
                }
            })
            ->editColumn('detail.status', function ($row) {
                return trans(sprintf('web::contract.%s', $row->detail->status));
            })
            ->editColumn('detail.price', function ($row) {
                return number($row->detail->price);
            })
            ->editColumn('detail.reward', function ($row) {
                return number($row->detail->reward);
            })
            ->filterColumn('detail.type', function ($query, $keyword) {
                $query->whereHas('detail', function ($sub_query) use ($keyword) {
                    $captions = Lang::get('web::contract');

                    $status = array_keys(array_filter($captions, function ($value) use ($keyword) {
                        return strpos(strtoupper($value), strtoupper($keyword)) !== false;
                    }));

                    $sub_query->whereIn('type', $status);
                });
            })
            ->filterColumn('detail.status', function ($query, $keyword) {
                $query->whereHas('detail', function ($sub_query) use ($keyword) {
                    $captions = Lang::get('web::contract');

                    $status = array_keys(array_filter($captions, function ($value) use ($keyword) {
                        return strpos(strtoupper($value), strtoupper($keyword)) !== false;
                    }));

                    $sub_query->whereIn('status', $status);
                });
            })
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
            ->addAction()
            ->orderBy(0, 'desc')
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
            ['data' => 'detail.date_issued', 'title' => trans('web::contract.created')],
            ['data' => 'detail.type', 'title' => trans('web::contract.type')],
            ['data' => 'detail.issuer.name', 'title' => trans('web::contract.issuer')],
            ['data' => 'detail.assignee.name', 'title' => trans('web::contract.assignee')],
            ['data' => 'detail.acceptor.name', 'title' => trans('web::contract.acceptor')],
            ['data' => 'detail.status', 'title' => trans('web::contract.status')],
            ['data' => 'detail.price', 'title' => trans('web::contract.price')],
            ['data' => 'detail.reward', 'title' => trans('web::contract.reward')],
        ];
    }
}
