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
            ->addColumn('action', function ($row) {
                return view('web::common.contracts.buttons.details', compact('row'));
            })
            ->addColumn('created', function ($row) {
                return view('web::partials.date', ['datetime' => $row->detail->date_issued]);
            })
            ->addColumn('type', function ($row) {
                return trans(sprintf('web::contract.%s', $row->detail->type));
            })
            ->addColumn('issuer', function ($row) {
                switch ($row->detail->issuer->category) {
                    case 'alliance':
                        return view('web::partials.alliance', ['alliance' => $row->detail->issuer_id]);
                    case 'corporation':
                        return view('web::partials.corporation', ['corporation' => $row->detail->issuer_id]);
                    case 'character':
                        return view('web::partials.character', ['character' => $row->detail->issuer_id]);
                    default:
                        return '';
                }
            })
            ->addColumn('assignee', function ($row) {
                switch ($row->detail->assignee->category) {
                    case 'alliance':
                        return view('web::partials.alliance', ['alliance' => $row->detail->assignee_id]);
                    case 'corporation':
                        return view('web::partials.corporation', ['corporation' => $row->detail->assignee_id]);
                    case 'character':
                        return view('web::partials.character', ['character' => $row->detail->assignee_id]);
                    default:
                        return '';
                }
            })
            ->addColumn('acceptor', function ($row) {
                switch ($row->detail->acceptor->category) {
                    case 'alliance':
                        return view('web::partials.alliance', ['alliance' => $row->detail->acceptor_id]);
                    case 'corporation':
                        return view('web::partials.corporation', ['corporation' => $row->detail->acceptor_id]);
                    case 'character':
                        return view('web::partials.character', ['character' => $row->detail->acceptor_id]);
                    default:
                        return '';
                }
            })
            ->addColumn('status', function ($row) {
                return trans(sprintf('web::contract.%s', $row->detail->status));
            })
            ->addColumn('price', function ($row) {
                return number($row->detail->price);
            })
            ->addColumn('reward', function ($row) {
                return number($row->detail->reward);
            })
            ->filterColumn('type', function ($query, $keyword) {
                $query->whereHas('detail', function ($sub_query) use ($keyword) {
                    $captions = Lang::get('web::contract');
                    $status = array_keys(array_filter($captions, function ($value) use ($keyword) {
                        return strpos(strtoupper($value), strtoupper($keyword)) !== false;
                    }));

                    $sub_query->whereIn('type', $status);
                });
            })
            ->filterColumn('issuer', function ($query, $keyword) {
                return $query->whereHas('detail.issuer', function ($sub_query) use ($keyword) {
                    return $sub_query->whereRaw('name LIKE ?', ["%$keyword%"]);
                });
            })
            ->filterColumn('assignee', function ($query, $keyword) {
                return $query->whereHas('detail.assignee', function ($sub_query) use ($keyword) {
                    return $sub_query->whereRaw('name LIKE ?', ["%$keyword%"]);
                });
            })
            ->filterColumn('acceptor', function ($query, $keyword) {
                return $query->whereHas('detail.acceptor', function ($sub_query) use ($keyword) {
                    return $sub_query->whereRaw('name LIKE ?', ["%$keyword%"]);
                });
            })
            ->filterColumn('status', function ($query, $keyword) {
                $query->whereHas('detail', function ($sub_query) use ($keyword) {
                    $captions = Lang::get('web::contract');
                    $status = array_keys(array_filter($captions, function ($value) use ($keyword) {
                        return strpos(strtoupper($value), strtoupper($keyword)) !== false;
                    }));

                    $sub_query->whereIn('status', $status);
                });
            })
            ->filterColumn('price', function ($query, $keyword) {
                return $query->whereHas('detail', function ($sub_query) use ($keyword) {
                    $sub_query->whereRaw('(price) LIKE ?', ["%$keyword%"]);
                });
            })
            ->filterColumn('reward', function ($query, $keyword) {
                return $query->whereHas('detail', function ($sub_query) use ($keyword) {
                    $sub_query->whereRaw('(reward) LIKE ?', ["%$keyword%"]);
                });
            })
            ->rawColumns(['created', 'issuer', 'assignee', 'acceptor', 'action'])
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
            ['data' => 'created', 'title' => trans('web::contract.created'), 'orderable' => false],
            ['data' => 'type', 'title' => trans('web::contract.type'), 'orderable' => false],
            ['data' => 'issuer', 'title' => trans('web::contract.issuer'), 'orderable' => false],
            ['data' => 'assignee', 'title' => trans('web::contract.assignee'), 'orderable' => false],
            ['data' => 'acceptor', 'title' => trans('web::contract.acceptor'), 'orderable' => false],
            ['data' => 'status', 'title' => trans('web::contract.status'), 'orderable' => false],
            ['data' => 'price', 'title' => trans('web::contract.price'), 'orderable' => false],
            ['data' => 'reward', 'title' => trans('web::contract.reward'), 'orderable' => false],
        ];
    }
}
