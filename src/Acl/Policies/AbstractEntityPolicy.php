<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2022 Leon Jacobs
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

namespace Seat\Web\Acl\Policies;

use stdClass;

/**
 * Class AbstractEntityPolicy.
 *
 * @package Seat\Web\Acl\Policies
 */
abstract class AbstractEntityPolicy extends AbstractPolicy
{
    /**
     * Determine if the requested entity is granted by the specified permission filter.
     *
     * @param  stdClass  $filters
     * @param  string  $entity_type
     * @param  int  $entity_id
     * @return bool
     */
    protected function isGrantedByFilter(stdClass $filters, string $entity_type, ?int $entity_id): bool
    {
        if (! property_exists($filters, $entity_type))
            return false;

        if (is_null($entity_id))
            return false;

        return collect($filters->$entity_type)->contains('id', $entity_id);
    }
}
