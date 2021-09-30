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

                // sort rules by path
                $sorted_rules = $this->sortFiltersByRelations($rules);

                // TODO: find a way to handle this using recursive loop and determine common patterns
                $sorted_rules->each(function ($rules_group, $path) use ($query, $verb) {

                    if (is_int($path)) {

                        $parent_verb = $verb == 'whereHas' ? 'where' : 'orWhere';

                        $query->$parent_verb(function ($q2) use ($rules_group, $parent_verb) {

                            // all pairs will be group in distinct collection due to previous group by
                            // as a result, we have to iterate over each members
                            $rules_group->each(function ($rules) use ($parent_verb, $q2) {

                                // determine the match kind for the current pair
                                // sort all rules from this pair in order to ensure relationship consistency
                                $group_verb = property_exists($rules, 'and') ? 'whereHas' : 'orWhereHas';
                                $rules_group = $this->sortFiltersByRelations(property_exists($rules, 'and') ?
                                    $rules->and : $rules->or);

                                $rules_group->each(function ($rules, $path) use ($parent_verb, $group_verb, $q2) {

                                    // prepare query from current pair group
                                    $q2->$parent_verb(function ($q3) use ($rules, $path, $group_verb) {
                                        $q3->$group_verb($path, function ($q4) use ($rules, $group_verb) {

                                            // prevent dummy query by encapsulating rules outside relations
                                            $q4->where(function ($q5) use ($rules, $group_verb) {
                                                $this->applyRules($q5, $group_verb, $rules);
                                            });
                                        });
                                    });
                                });
                            });
                        });

                    } else {

                        $rules = $rules_group;

                        // using group by, we've pair all relationships by their top level relation
                        // $query->whereHas('characters(.*)', function ($sub_query) { ... }
                        $query->$verb($path, function ($q2) use ($rules, $verb) {

                            // override the complete rule group with a global where.
                            // doing it so will prevent SQL query like
                            // (users.id = tokens.user_id OR character_id = ? OR character_id = ?)
                            // when multiple rules are applied on same path.
                            $q2->where(function ($q3) use ($rules, $verb) {
                                $this->applyRules($q3, $verb, $rules);
                            });
                        });
                    }
                });
            })->exists();
    }

    /**
     * @param  array  $rules
     * @return \Illuminate\Support\Collection
     */
    private function sortFiltersByRelations(array $rules)
    {
        return collect($rules)->sortBy('path')->groupBy(function ($rule) {
            if (! property_exists($rule, 'path'))
                return false;

            $relation_members = explode('.', $rule->path);

            return $relation_members[0];
        });
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $group_verb
     * @param  array|\Illuminate\Support\Collection  $rules
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function applyRules(Builder $query, string $group_verb, $rules)
    {
        $query_operator = $group_verb == 'whereHas' ? 'where' : 'orWhere';

        if (is_array($rules))
            $rules = collect($rules);

        $rules->sortBy('path')->groupBy('path')->each(function ($relation_rules, $relation) use ($query, $query_operator) {
            if (strpos($relation, '.') !== false) {
                $relation = substr($relation, strpos($relation, '.') + 1);

                $query->whereHas($relation, function ($q2) use ($query_operator, $relation_rules) {
                    $q2->where(function ($q3) use ($relation_rules, $query_operator) {
                        foreach ($relation_rules as $index => $rule) {
                            if ($rule->operator == 'contains') {
                                $json_operator = $query_operator == 'where' ? 'whereJsonContains' : 'orWhereJsonContains';
                                $q3->$json_operator($rule->field, $rule->criteria);
                            } else {
                                $q3->$query_operator($rule->field, $rule->operator, $rule->criteria);
                            }
                        }
                    });
                });
            } else {
                $query->where(function ($q2) use ($relation_rules, $query_operator) {
                    foreach ($relation_rules as $index => $rule) {
                        if ($rule->operator == 'contains') {
                            $json_operator = $query_operator == 'where' ? 'whereJsonContains' : 'orWhereJsonContains';
                            $q2->$json_operator($rule->field, $rule->criteria);
                        } else {
                            $q2->$query_operator($rule->field, $rule->operator, $rule->criteria);
                        }
                    }
                });
            }
        });

        return $query;
    }
}
