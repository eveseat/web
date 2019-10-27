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

namespace Seat\Web\Http\DataTables\Character\Financial;

use Seat\Eveapi\Models\Market\CharacterOrder;
use Seat\Web\Http\DataTables\Common\Financial\AbstractMarketDataTable;

/**
 * Class MarketDataTable.
 *
 * @package Seat\Web\Http\DataTables\Character\Financial
 */
class MarketDataTable extends AbstractMarketDataTable
{
    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return CharacterOrder::with('type');
    }

    /**
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return parent::html()
            ->ajax([
                'data' => 'function(d) { d.characters = $("#dt-character-selector").val(); d.filters = {}; $("[data-filter-field].dt-filters.active").each(function (i, e) { var a = $(e); var field = a.data("filter-field"); var value = a.data("filter-value"); if (! d.filters[field]) { d.filters[field] = []; } d.filters[field].push(value); }); }',
            ]);
    }
}
