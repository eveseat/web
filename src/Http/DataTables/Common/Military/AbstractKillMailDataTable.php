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

namespace Seat\Web\Http\DataTables\Common\Military;

use Seat\Eveapi\Models\Killmails\Killmail;
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
     *
     * @throws \Exception
     */
    public function ajax()
    {
        return datatables()
            ->eloquent($this->applyScopes($this->query()))
            ->editColumn('action', function ($row) {
                return view('web::common.killmails.buttons.show', compact('row'))->render() . ' ' .
                    view('web::common.killmails.buttons.zkb', compact('row'))->render();
            })
            ->editColumn('detail.killmail_time', function ($row) {
                return view('web::partials.date', ['datetime' => $row->detail->killmail_time])->render();
            })
            ->editColumn('victim.ship.typeName', function ($row) {
                return view('web::partials.type', [
                    'type_id' => $row->victim->ship->typeID,
                    'type_name' => $row->victim->ship->typeName,
                ])->render();
            })
            ->editColumn('detail.solar_system.name', function ($row) {
                return view('web::partials.system', [
                    'system' => $row->detail->solar_system->name,
                    'security' => $row->detail->solar_system->security,
                ])->render();
            })
            ->editColumn('victim.character.name', function ($row) {
                return view('web::common.killmails.entity', ['entity' => $row->victim])->render();
            })
            ->addColumn('killer', function ($row) {
                $killer = $row->attackers->where('final_blow', true)->first();

                if (is_null($killer))
                    return '';

                return view('web::common.killmails.entity', ['entity' => $killer])->render();
            })
            ->filterColumn('victim.character.name', function ($query, $keyword) {
                $query->whereHas('victim.character', function ($sub_query) use ($keyword) {
                    return $sub_query->whereRaw('name LIKE ?', ["%$keyword%"]);
                });
                $query->orWhereHas('victim.corporation', function ($sub_query) use ($keyword) {
                    return $sub_query->whereRaw('name LIKE ?', ["%$keyword%"]);
                });
                $query->orWhereHas('victim.alliance', function ($sub_query) use ($keyword) {
                    return $sub_query->whereRaw('name LIKE ?', ["%$keyword%"]);
                });
                $query->orWhereHas('victim.faction', function ($sub_query) use ($keyword) {
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
                    $sub_query->orWhereHas('faction', function ($children_query) use ($keyword) {
                        return $children_query->whereRaw('name LIKE ?', ["%$keyword%"]);
                    });
                });
            })
            ->rawColumns([
                'action', 'detail.killmail_time', 'victim.ship.typeName', 'detail.solar_system.name',
                'victim.character.name', 'killer',
            ])
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
            ->addTableClass('table-striped table-hover')
            ->parameters([
                'drawCallback' => 'function() { $("[data-toggle=tooltip]").tooltip(); ids_to_names(); }',
            ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return Killmail::with('detail', 'detail.solar_system',
            'victim', 'victim.character', 'victim.corporation', 'victim.alliance', 'victim.faction', 'victim.ship',
            'attackers', 'attackers.character', 'attackers.corporation', 'attackers.alliance', 'attackers.faction');
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return [
            ['data' => 'detail.killmail_time', 'title' => trans('web::kills.date')],
            ['data' => 'victim.ship.typeName', 'title' => trans('web::kills.ship')],
            ['data' => 'detail.solar_system.name', 'title' => trans('web::kills.solar_system')],
            ['data' => 'victim.character.name', 'title' => trans('web::kills.victim'), 'orderable' => false],
            ['data' => 'killer', 'title' => trans('web::kills.killer'), 'orderable' => false],
        ];
    }
}
