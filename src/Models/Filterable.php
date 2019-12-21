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

namespace Seat\Web\Models;

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
     * @param \Illuminate\Database\Eloquent\Model $member
     * @return bool
     */
    final public function isEligible(Model $member): bool
    {
        // in case no filters exists, bypass check
        if (! property_exists($this->getFilters(), 'and') && ! property_exists($this->getFilters(), 'or'))
            return true;

        // init a new object based on parameter
        $class = get_class($member);

        return (new $class)::where($member->getKeyName(), $member->id)
            ->where(function ($query) {

                // verb will determine what kind of method we have to use (simple andWhere or orWhere)
                $verb = property_exists($this->getFilters(), 'and') ? 'whereHas' : 'orWhereHas';

                // rules will determine all objects and ruleset in the current object root
                $rules = property_exists($this->getFilters(), 'and') ? $this->getFilters()->and : $this->getFilters()->or;

                foreach ($rules as $key => $rule) {

                    // in case the current object contain a filter property, this is a rule object
                    // add a query using proper words
                    if (property_exists($rule, 'name')) {
                        $query->$verb($rule->path, function ($sub_query) use ($rule) {
                            $sub_query->where($rule->field, $rule->operator, $rule->criteria);
                        });
                    }

                    // in case the current object is an array, this is a ruleset object
                    if (property_exists($rule, 'or') || property_exists($rule, 'and')) {

                        // verb will determine what kind of method we have to use (simple andWhere or orWhere)
                        $ruleset_verb = property_exists($this->getFilters(), 'and') ? 'where' : 'orWhere';

                        // add a complex sub_query using the current ruleset rules
                        $query->$ruleset_verb(function ($sub_query) use ($rule) {

                            // verb will determine what kind of method we have to use (simple andWhere or orWhere)
                            $verb = property_exists($rule, 'and') ? 'whereHas' : 'orWhereHas';

                            // rules will determine all objects and ruleset in the current object root
                            $rules = property_exists($rule, 'and') ? $rule->and : $rule->or;

                            foreach ($rules as $ruleset_rule) {
                                $sub_query->$verb($ruleset_rule->path, function ($query) use ($ruleset_rule) {
                                    $query->where($ruleset_rule->field, $ruleset_rule->operator, $ruleset_rule->criteria);
                                });
                            }
                        });
                    }
                }
            })->exists();
    }
}
