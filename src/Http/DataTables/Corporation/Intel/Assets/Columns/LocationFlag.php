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

namespace Seat\Web\Http\DataTables\Corporation\Intel\Assets\Columns;

use Illuminate\Database\Eloquent\Model;
use Seat\Web\Http\DataTables\Common\AbstractColumn;

/**
 * Class LocationFlag.
 *
 * @package Seat\Web\Http\DataTables\Corporation\Intel\Assets\Columns
 */
class LocationFlag extends AbstractColumn
{
    /**
     * @var \Seat\Web\Http\DataTables\Common\Intel\AbstractAssetDataTable
     */
    private $divisions;

    /**
     * AssetLocationFlagColumn constructor.
     *
     * @param \Seat\Web\Http\DataTables\Corporation\Intel\Assets\DataTable $table
     */
    public function __construct($table)
    {
        parent::__construct($table);

        $this->divisions = $table->getDivisions();
    }

    /**
     * Draw a column cell.
     *
     * @param \Illuminate\Database\Eloquent\Model $row
     * @return string;
     */
    public function draw(Model $row)
    {
        switch ($row->location_flag) {
            case 'CorpSAG1':
                return $this->divisions->where('division', 1)->first()->name ?? sprintf('%s 1', trans_choice('web::assets.division', 1));
            case 'CorpSAG2':
                return $this->divisions->where('division', 2)->first()->name ?? sprintf('%s 2', trans_choice('web::assets.division', 1));
            case 'CorpSAG3':
                return $this->divisions->where('division', 3)->first()->name ?? sprintf('%s 3', trans_choice('web::assets.division', 1));
            case 'CorpSAG4':
                return $this->divisions->where('division', 4)->first()->name ?? sprintf('%s 4', trans_choice('web::assets.division', 1));
            case 'CorpSAG5':
                return $this->divisions->where('division', 5)->first()->name ?? sprintf('%s 5', trans_choice('web::assets.division', 1));
            case 'CorpSAG6':
                return $this->divisions->where('division', 6)->first()->name ?? sprintf('%s 6', trans_choice('web::assets.division', 1));
            case 'CorpSAG7':
                return $this->divisions->where('division', 7)->first()->name ?? sprintf('%s 7', trans_choice('web::assets.division', 1));
            case 'CorpDeliveries':
                return 'Delivery Hangar';
            default:
                return ($row->location_flag == 'AutoFit' && $row->location_type == 'solar_system') ? 'In Space' : '';
        }
    }

    /**
     * Search in a column cell.
     *
     * @param \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder $query
     * @param string $keyword
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    public function search($query, string $keyword)
    {
        $divisions = $this->divisions->filter(function ($division) use ($keyword) {
            return strpos(strtolower($division->name), strtolower($keyword)) !== false;
        })->map(function ($division) {
            return sprintf('CorpSAG%d', $division->division);
        });

        return $query->where(function ($sub_query) use ($divisions, $keyword) {
            $sub_query->whereRaw('location_flag LIKE ?', ["%{$keyword}%"]);
            $sub_query->orWhereIn('location_flag', $divisions->toArray());
        });
    }
}
