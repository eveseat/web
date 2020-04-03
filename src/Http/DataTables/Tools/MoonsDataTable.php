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

namespace Seat\Web\Http\DataTables\Tools;

use Seat\Eveapi\Models\Sde\MapDenormalize;
use Yajra\DataTables\Services\DataTable;

/**
 * Class MoonsDataTable.
 *
 * @package Seat\Web\Http\DataTables\Tools
 */
class MoonsDataTable extends DataTable
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return datatables()
            ->eloquent($this->applyScopes($this->query()))
            ->editColumn('region', function ($row) {
                return $row->region->itemName;
            })
            ->editColumn('constellation', function ($row) {
                return $row->constellation->itemName;
            })
            ->editColumn('system', function ($row) {
                return $row->system->itemName;
            })
            ->editColumn('planet', function ($row) {
                return $row->planet->itemName;
            })
            ->editColumn('sovereignty', function ($row) {
                switch (true) {
                    case ! is_null($row->system->sovereignty->faction_id):
                        return view('web::partials.faction', ['faction' => $row->system->sovereignty->faction]);
                    case ! is_null($row->system->sovereignty->alliance_id):
                        return view('web::partials.alliance', ['alliance' => $row->system->sovereignty->alliance]);
                    case ! is_null($row->system->sovereignty->corporation_id):
                        return view('web::partials.corporation', ['corporation' => $row->system->sovereignty->corporation]);
                    default:
                        return '';
                }
            })
            ->editColumn('indicators', function ($row) {
                return view('web::tools.moons.partials.indicators', compact('row'));
            })
            ->editColumn('action', function ($row) {
                return view('web::tools.moons.buttons.show', compact('row'));
            })
            ->filterColumn('region', function ($query, $keyword) {
                return $query->whereHas('region', function ($sub_query) use ($keyword) {
                    return $sub_query->whereRaw('itemName LIKE ?', ["%{$keyword}%"]);
                });
            })
            ->filterColumn('constellation', function ($query, $keyword) {
                return $query->whereHas('constellation', function ($sub_query) use ($keyword) {
                    return $sub_query->whereRaw('itemName LIKE ?', ["%{$keyword}%"]);
                });
            })
            ->filterColumn('system', function ($query, $keyword) {
                return $query->whereHas('system', function ($sub_query) use ($keyword) {
                    return $sub_query->whereRaw('itemName LIKE ?', ["%{$keyword}%"]);
                });
            })
            ->filterColumn('planet', function ($query, $keyword) {
                return $query->whereHas('planet', function ($sub_query) use ($keyword) {
                    return $sub_query->whereRaw('itemName LIKE ?', ["%{$keyword}%"]);
                });
            })
            ->filterColumn('sovereignty', function ($query, $keyword) {
                return $query->whereHas('system.sovereignty', function ($sub_query) use ($keyword) {
                    return $sub_query->whereHas('faction', function ($query) use ($keyword) {
                        return $query->whereRaw('name LIKE ?', ["%{$keyword}%"]);
                    })->orWhereHas('alliance', function ($query) use ($keyword) {
                        return $query->whereRaw('name LIKE ?', ["%{$keyword}%"]);
                    })->orWhereHas('corporation', function ($query) use ($keyword) {
                        return $query->whereRaw('name LIKE ?', ["%{$keyword}%"]);
                    });
                });
            })
            ->filterColumn('indicators', function ($query, $keyword) {
                // search in raw moon materials
                return $query->whereHas('moon_contents.type', function ($sub_query) use ($keyword) {
                    return $sub_query->whereRaw('typeName LIKE ?', ["%{$keyword}%"]);
                })
                // search in reprocessed materials
                ->orWhereHas('moon_contents.type.materials.type', function ($sub_query) use ($keyword) {
                    return $sub_query->whereRaw('typeName LIKE ?', ["%{$keyword}%"]);
                })
                // search in reactions
                ->orWhereHas('moon_contents.type.materials.type.reactions', function ($sub_query) use ($keyword) {
                    return $sub_query->whereRaw('typeName LIKE ?', ["%{$keyword}%"]);
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
            ->addColumn([
                'data'  => 'region',
                'title' => trans_choice('web::moons.region', 1),
            ])
            ->addColumn([
                'data'  => 'constellation',
                'title' => trans_choice('web::moons.constellation', 1),
            ])
            ->addColumn([
                'data'  => 'system',
                'title' => trans_choice('web::moons.system', 1),
            ])
            ->addColumn([
                'data'  => 'planet',
                'title' => trans_choice('web::moons.planet', 1),
            ])
            ->addColumn([
                'data'  => 'sovereignty',
                'title' => trans_choice('web::moons.sovereignty', 1),
            ])
            ->addColumn([
                'data'  => 'indicators',
                'title' => trans_choice('web::moons.indicator', 0),
            ])
            ->addAction()
            ->parameters([
                'drawCallback' => 'function() { ids_to_names(); }',
            ]);
    }

    /**
     * @return mixed
     */
    public function query()
    {
        return MapDenormalize::has('moon_contents')
            ->with('planet', 'system', 'constellation', 'region', 'sovereignty', 'sovereignty.faction',
                   'sovereignty.alliance', 'sovereignty.corporation', 'moon_contents', 'moon_contents.type');
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return [
            ['data' => 'itemName', 'title' => trans_choice('web::moons.moon', 1)],
        ];
    }
}
