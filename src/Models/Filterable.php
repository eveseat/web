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

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use stdClass;

/**
 * Class Filterable.
 *
 * @package Seat\Web\Models
 */
trait Filterable
{
    /**
     * @return \stdClass
     */
    abstract public function getFilters(): stdClass;

    /**
     * @param  \Illuminate\Database\Eloquent\Model  $member
     * @return bool
     */
    final public function isEligible(Model $member): bool
    {
        // in case no filters exists, bypass check and return not eligible
        if (!property_exists($this->getFilters(), 'and') && !property_exists($this->getFilters(), 'or'))
            return false;

        $query = $member->newQuery();

        // make sure we only allow results of the entity we are checking
        $query->where($member->getKeyName(),$member->getKey());
        // wrap this in an inner query to ensure we have the correct entity and the filter applies
        $query->where(function ($inner_query){
            $this->applyGroup($inner_query, $this->getFilters());
        });

        //return dd($query->toRawSql(), $query->exists());
        return $query->exists();
    }

    private function applyGroup(Builder $query, object $group): void
    {
        $query_group = new QueryGroupBuilder($query, property_exists($group, 'and'));

        $rules = $query_group->isAndGroup() ? $group->and : $group->or;

        foreach ($rules as $rule){
            if(property_exists($rule,'path')){
                $this->applyRule($query_group, $rule);
            } else {
                // this is a nested group
                $query->where(function ($group_query) use ($group) {
                    $this->applyGroup($group_query, $group);
                });
            }
        }
    }

    private function applyRule(QueryGroupBuilder $query, object $rule): void {
        // 'is' operator
        if($rule->operator === '='){
            $query->whereHas($rule->path, function ($inner_query) use ($rule) {
                $inner_query->where($rule->field,$rule->operator, $rule->criteria);
            });
        } else if ($rule->operator === '<>') {
            $query->where(function (Builder $inner_query) use ($rule) {
               $inner_query->whereDoesntHave($rule->path, function ($final_query) use ($rule) {
                   $final_query->where($rule->field, $rule->criteria);
               });
            });
        } else {
            throw new \Exception('not implemented');
        }
    }
}
