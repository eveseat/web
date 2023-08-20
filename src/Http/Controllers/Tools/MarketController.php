<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to present Leon Jacobs
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

namespace Seat\Web\Http\Controllers\Tools;

use Seat\Eveapi\Models\Sde\InvType;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\DataTables\Scopes\Filters\SellOrderScope;
use Seat\Web\Http\DataTables\Scopes\Filters\TypeScope;
use Seat\Web\Http\DataTables\Tools\MarketOrderDataTable;

/**
 * Class MarketController.
 *
 * @package Seat\Web\Http\Controllers\Tools
 */
class MarketController extends Controller
{
    const DEFAULT_ITEM = 4358;

    public function browser(MarketOrderDataTable $dataTable) {
        $type_id = intval(request()->input('type_id', self::DEFAULT_ITEM));
        //for some reason the bool is turned into a string
        $sell_orders = request()->input('sell_orders', 'true') == 'true';

        $dataTable->addScope(new TypeScope($type_id));
        $dataTable->addScope(new SellOrderScope($sell_orders));

        $default_item = InvType::find(self::DEFAULT_ITEM);

        return $dataTable->render('web::tools.market.browser', compact('default_item'));
    }
}
