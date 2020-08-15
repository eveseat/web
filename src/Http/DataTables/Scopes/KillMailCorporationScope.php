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

namespace Seat\Web\Http\DataTables\Scopes;

use Yajra\DataTables\Contracts\DataTableScope;

/**
 * Class KillMailCorporationScope.
 *
 * This is a specific scope for kill-mails in order to avoid collision on corporation_id field.
 *
 * @package Seat\Web\Http\DataTables\Scopes
 */
class KillMailCorporationScope implements DataTableScope
{
    /**
     * @var array
     */
    private $corporation_ids = [];

    /**
     * KillMailCorporationScope constructor.
     *
     * @param array $corporation_ids
     */
    public function __construct(array $corporation_ids)
    {
        $this->corporation_ids = $corporation_ids;
    }

    /**
     * Apply a query scope.
     *
     * @param \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $query
     * @return mixed
     */
    public function apply($query)
    {
        return $query->whereHas('attackers', function ($sub_query) {
            $sub_query->whereIn('killmail_attackers.corporation_id', $this->corporation_ids);
        })->orWhereHas('victim', function ($sub_query) {
            $sub_query->whereIn('killmail_victims.corporation_id', $this->corporation_ids);
        });
    }
}
