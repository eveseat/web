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
use Seat\Web\Exceptions\InvalidFilterException;
use stdClass;

/**
 * Class Filterable.
 *
 * @package Seat\Web\Models
 */
trait Filterable
{
    /**
     * The filters to use.
     *
     * @return \stdClass
     */
    abstract public function getFilters(): stdClass;

    /**
     * Check if an entity is eligible.
     *
     * @param  Model  $member  The entity to check
     * @return bool Whether the entity is eligible
     *
     * @throws InvalidFilterException If a invalid filter configuration is used
     */
    final public function isEligible(Model $member): bool
    {
        // in case no filters exists, everyone should be allowed
        // this is the case with manual squads
        if (! property_exists($this->getFilters(), 'and') && ! property_exists($this->getFilters(), 'or'))
            return true;

        $query = new QueryGroupBuilder($member->newQuery(), true);

        // make sure we only allow results of the entity we are checking count
        $query->where(function (Builder $inner_query) use ($member) {
            $inner_query->where($member->getKeyName(), $member->getKey());
        });

        // wrap this in an inner query to ensure it is '(correct_entity_check) AND (rule1 AND/OR rule2)'
        $query->where(function ($inner_query) {
            $this->applyGroup($inner_query, $this->getFilters());
        });

        return $query->getUnderlyingQuery()->exists();
    }

    /**
     * Print the sql query that will be generated to check for user eligibility.
     * This is for developmental testing and tinkering.
     *
     * @param  Model  $member  The entity to check
     * @return string The sql query that checks eligibility
     *
     * @throws InvalidFilterException If a invalid filter configuration is used
     */
    final public function printUnderlyingQuery(Model $member): string
    {
        $query = new QueryGroupBuilder($member->newQuery(), true);

        // make sure we only allow results of the entity we are checking count
        $query->where(function (Builder $inner_query) use ($member) {
            $inner_query->where($member->getKeyName(), $member->getKey());
        });

        // wrap this in an inner query to ensure it is '(correct_entity_check) AND (rule1 AND/OR rule2)'
        $query->where(function ($inner_query) {
            $this->applyGroup($inner_query, $this->getFilters());
        });

        return $query->getUnderlyingQuery()->toSql();
    }

    /**
     * Applies a filter group to $query.
     *
     * @param  Builder  $query  the query to add the filter group to
     * @param  stdClass  $group  the filter group configuration
     *
     * @throws InvalidFilterException
     */
    private function applyGroup(Builder $query, stdClass $group): void
    {
        $query_group = new QueryGroupBuilder($query, property_exists($group, 'and'));
        $rules = $query_group->isAndGroup() ? $group->and : $group->or;

        foreach ($rules as $rule) {
            // check if this is a nested group or not
            if (property_exists($rule, 'path')) {
                if ($rule->name == 'skill_level') {
                    // Now get all the skill rules in this group
                    foreach ($rules as $skrule) {
                        // if it is a skill rule, special case it
                        if (property_exists($skrule, 'path') && $skrule->name == 'skill') {
                            $this->applySkillLevelRule($query_group, $rule, $skrule);
                        }
                    }
                } else {
                    $this->applyRule($query_group, $rule);
                }
            } else {
                // this is a nested group
                $query_group->where(function ($group_query) use ($rule) {
                    $this->applyGroup($group_query, $rule);
                });
            }
        }
    }

    /**
     * Applies a rule to a query group.
     *
     * @param  QueryGroupBuilder  $query  the query to add the rule to
     * @param  stdClass  $rule  the rule configuration
     *
     * @throws InvalidFilterException
     */
    private function applyRule(QueryGroupBuilder $query, stdClass $rule): void
    {
        // 'is' operator
        if ($rule->operator === '=' || $rule->operator === '<' || $rule->operator === '>') {
            // normal comparison operations need to relation to exist
            $query->whereHas($rule->path, function (Builder $inner_query) use ($rule) {
                $inner_query->where($rule->field, $rule->operator, $rule->criteria);
            });
        } elseif ($rule->operator === '<>' || $rule->operator === '!=') {
            // not equal is special cased since a missing relation is the same as not equal
            $query->whereDoesntHave($rule->path, function (Builder $inner_query) use ($rule) {
                $inner_query->where($rule->field, $rule->criteria);
            });
        } elseif ($rule->operator === 'contains') {
            // contains is maybe a misleading name, since it actually checks if json contains a value
            $query->whereHas($rule->path, function (Builder $inner_query) use ($rule) {
                $inner_query->whereJsonContains($rule->field, $rule->criteria);
            });
        } else {
            throw new InvalidFilterException(sprintf('Unknown rule operator: \'%s\'', $rule->operator));
        }
    }

    /**
     * Applies a skill level rule to a query group.
     *
     * @param  QueryGroupBuilder  $query  the query to add the rule to
     * @param  stdClass  $rule  the rule configuration
     *
     * @throws InvalidFilterException
     */
    private function applySkillLevelRule(QueryGroupBuilder $query, stdClass $rule, stdClass $skrule): void
    {
        // 'is' operator
        if ($rule->operator === '=' || $rule->operator === '<' || $rule->operator === '>') {
            // normal comparison operations need to relation to exist
            $query->whereHas($rule->path, function (Builder $inner_query) use ($rule, $skrule) {
                $inner_query->where(function ($q) use ($rule, $skrule) {
                    $q->where($rule->field, $rule->operator, $rule->criteria)
                        ->where($skrule->field, $skrule->operator, $skrule->criteria);
                });
            });
        } elseif ($rule->operator === '<>' || $rule->operator === '!=') {
            // not equal is special cased since a missing relation is the same as not equal
            $query->whereDoesntHave($rule->path, function (Builder $inner_query) use ($rule, $skrule) {
                $inner_query->where($rule->field, $rule->criteria)
                    ->where($skrule->field, $skrule->operator, $skrule->criteria);
                //TODO TEST THIS PATH
            });
        } elseif ($rule->operator === 'contains') {
            // contains is maybe a misleading name, since it actually checks if json contains a value
            $query->whereHas($rule->path, function (Builder $inner_query) use ($rule) {
                $inner_query->whereJsonContains($rule->field, $rule->criteria);
                // TODO HANDLE THIS CRITERIA ((even though I dont think it is valid))
            });
        } else {
            throw new InvalidFilterException(sprintf('Unknown rule operator: \'%s\'', $rule->operator));
        }
    }
}
