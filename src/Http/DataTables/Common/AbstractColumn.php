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

namespace Seat\Web\Http\DataTables\Common;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

/**
 * Class AbstractColumn.
 *
 * @package Seat\Web\Http\DataTables\Common
 */
abstract class AbstractColumn implements IColumn
{
    /**
     * @var \Yajra\DataTables\Services\DataTable
     */
    private $table;

    /**
     * AbstractColumn constructor.
     *
     * @param \Yajra\DataTables\Services\DataTable $table
     */
    public function __construct($table)
    {
        $this->table = $table;
    }

    /**
     * Handle a column business flow.
     *
     * @param \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder $row
     * @param string $keyword
     * @return string|void
     */
    public function __invoke($row, $keyword = '')
    {
        if (is_a($row, EloquentBuilder::class) || is_a($row, QueryBuilder::class))
            return $this->search($row, $keyword);

        return $this->draw($row);
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
        return $query;
    }
}
