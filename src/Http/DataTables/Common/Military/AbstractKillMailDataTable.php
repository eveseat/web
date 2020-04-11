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

namespace Seat\Web\Http\DataTables\Common\Military;

use Yajra\DataTables\Services\DataTable;

/**
 * Class AbstractKillMailDataTable.
 *
 * @package Seat\Web\Http\DataTables\Common\Military
 */
abstract class AbstractKillMailDataTable extends DataTable
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
                return view('web::common.killmails.killmailzkb', compact('row'));
            })
            ->addColumn('date', function ($row) {
                return view('web::partials.date', ['datetime' => $row->detail->killmail_time]);
            })
            ->addColumn('ship', function ($row) {
                return view('web::partials.type', [
                    'type_id' => $row->victim->ship->typeID,
                    'type_name' => $row->victim->ship->typeName,
                ]);
            })
            ->addColumn('system', function ($row) {
                return view('web::partials.system', [
                    'system' => $row->detail->system->itemName,
                    'security' => $row->detail->system->security,
                ]);
            })
            ->addColumn('victim', function ($row) {
                return view('web::partials.character', ['character' => $row->victim->character->entity_id]) . '<br/>' .
                    view('web::partials.corporation', ['corporation' => $row->victim->corporation->entity_id]) .
                    view('web::partials.alliance', ['alliance' => $row->victim->alliance->entity_id]);
            })
            ->addColumn('killer', function ($row) {
                $killer = $row->attackers->where('final_blow', true)->first();

                if (is_null($killer))
                    return '';

                return view('web::partials.character', ['character' => $killer->character_id]) . '<br/>' .
                    view('web::partials.corporation', ['corporation' => $killer->corporation_id]) . ' ' .
                    view('web::partials.alliance', ['alliance' => $killer->alliance_id]);
            })
            ->filterColumn('ship', function ($query, $keyword) {
                return $query->whereHas('victim.ship', function ($sub_query) use ($keyword) {
                    return $sub_query->whereRaw('typeName LIKE ?', ["%$keyword%"]);
                });
            })
            ->filterColumn('system', function ($query, $keyword) {
                return $query->whereHas('detail.system', function ($sub_query) use ($keyword) {
                    return $sub_query->whereRaw('itemName LIKE ?', ["%$keyword%"]);
                });
            })
            ->filterColumn('victim', function ($query, $keyword) {
                $query->whereHas('victim.character', function ($sub_query) use ($keyword) {
                    return $sub_query->whereRaw('name LIKE ?', ["%$keyword%"]);
                });
                $query->orWhereHas('victim.corporation', function ($sub_query) use ($keyword) {
                    return $sub_query->whereRaw('name LIKE ?', ["%$keyword%"]);
                });
                $query->orWhereHas('victim.alliance', function ($sub_query) use ($keyword) {
                    return $sub_query->whereRaw('name LIKE ?', ["%$keyword%"]);
                });
            })
            ->filterColumn('killer', function ($query, $keyword) {
                $query->whereHas('attackers', function ($sub_query) use ($keyword) {
                    $sub_query->whereHas('character', function ($children_query) use ($keyword) {
                        return $children_query->whereRaw('name LIKE ?', ["%$keyword%"]);
                    });
                    $sub_query->orWhereHas('corporation', function ($children_query) use ($keyword) {
                        return $children_query->whereRaw('name LIKE ?', ["%$keyword%"]);
                    });
                    $sub_query->orWhereHas('alliance', function ($children_query) use ($keyword) {
                        return $children_query->whereRaw('name LIKE ?', ["%$keyword%"]);
                    });
                });
            })
            ->rawColumns(['date', 'ship', 'system', 'victim', 'killer', 'action'])
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
            ['data' => 'date', 'title' => trans('web::kills.date'), 'orderable' => false],
            ['data' => 'ship', 'title' => trans('web::kills.ship'), 'orderable' => false],
            ['data' => 'system', 'title' => trans('web::kills.solar_system'), 'orderable' => false],
            ['data' => 'victim', 'title' => trans('web::kills.victim'), 'orderable' => false],
            ['data' => 'killer', 'title' => trans('web::kills.killer'), 'orderable' => false],
        ];
    }
}
