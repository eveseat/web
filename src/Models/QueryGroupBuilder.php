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

namespace Seat\Web\Models;

use Closure;
use Illuminate\Database\Eloquent\Builder;

/**
 * Helper to build query where clauses that are either connected by AND or by OR.
 */
class QueryGroupBuilder
{
    /**
     * @var bool Whether the where clauses should be linked by AND
     */
    protected bool $is_and_group;

    /**
     * @var Builder The query builder to add the where clauses to
     */
    protected Builder $query;

    /**
     * @param  Builder  $query  The query builder to add the where clauses to
     * @param  bool  $is_and_group  Whether the where clauses should be linked by AND
     */
    public function __construct(Builder $query, bool $is_and_group) {
        $this->query = $query;
        $this->is_and_group = $is_and_group;
    }

    /**
     * @return bool Returns true when the where clauses are linked by AND
     */
    public function isAndGroup(): bool {
        return $this->is_and_group;
    }

    /**
     * @return Builder The underlying query builder used for this group
     */
    public function getUnderlyingQuery(): Builder
    {
        return $this->query;
    }

    /**
     * Either adds a 'where' or 'orWhere' to the query, depending on if it is an AND linked group or not.
     *
     * @param  Closure  $callback  a callback to add constraints
     * @return $this
     *
     * @see Builder::where
     * @see Builder::orWhere
     */
    public function where(Closure $callback): QueryGroupBuilder {
        if($this->is_and_group){
            $this->query->where($callback);
        } else {
           $this->query->orWhere($callback);
        }

        return $this;
    }

    /**
     * Either adds a 'whereHas' or 'orWhereHas' to the query, depending on if it is an AND linked group or not.
     *
     * @param  string  $relation  the relation to check for existence
     * @param  Closure  $callback  a callback to add more constraints
     * @return $this
     *
     * @see Builder::whereHas
     * @see Builder::orWhereHas
     */
    public function whereHas(string $relation, Closure $callback): QueryGroupBuilder {
        if($this->is_and_group){
            if($relation !== '') {
                $this->query->whereHas($relation, $callback);
            } else {
                $this->query->where($callback);
            }
        } else {
            if($relation !== '') {
                $this->query->orWhereHas($relation, $callback);
            } else {
                $this->query->orWhere($callback);
            }
        }

        return $this;
    }

    /**
     * Either adds a 'whereDoesntHave' or 'orWhereDoesntHave' to the query, depending on if it is an AND linked group or not.
     *
     * @param  string  $relation  the relation to check for absence
     * @param  Closure  $callback  a callback to add more constraints
     * @return $this
     *
     * @see Builder::whereDoesntHave
     * @see Builder::orWhereDoesntHave
     */
    public function whereDoesntHave(string $relation, Closure $callback): QueryGroupBuilder {
        if($this->is_and_group){
            if($relation !== '') {
                $this->query->whereDoesntHave($relation, $callback);
            } else {
                $this->query->whereNot($callback);
            }
        } else {
            if($relation !== '') {
                $this->query->orWhereDoesntHave($relation, $callback);
            } else {
                $this->query->orWhereNot($callback);
            }
        }

        return $this;
    }
}
