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

namespace Seat\Web\Acl\Policies;

use Seat\Eveapi\Models\Alliances\Alliance;
use Seat\Web\Acl\Response;
use Seat\Web\Models\Acl\Permission;

/**
 * Class AlliancePolicy.
 *
 * @package Seat\Web\Acl\Policies
 */
class AlliancePolicy extends AbstractEntityPolicy
{
    /**
     * @param  string  $method
     * @param  array  $args
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function __call($method, $args)
    {
        if (count($args) < 2)
            return false;

        $user = $args[0];
        $alliance = $args[1];

        $message = sprintf('Request to %s was denied. The permission required is %s', request()->path(), $this->ability);

        if (is_numeric($alliance))
            $alliance = Alliance::find($alliance);

        return $this->userHasPermission($user, $this->ability, function () use ($user, $alliance) {

            // retrieve defined authorization for the requested user
            $acl = $this->permissionsFrom($user);

            // filter out permissions which don't match with required one
            $permissions = $acl->filter(function ($permission) use ($alliance) {

                // exclude all permissions which does not match with the requested permission
                if ($permission->title != $this->ability)
                    return false;

                // in case no filters is available, return true as the permission is not limited
                if (! $permission->hasFilters())
                    return true;

                // return true in case this permission filter match
                return $this->isGrantedByFilters($permission, $alliance);
            });

            // if we have at least one valid permission - grant access
            return $permissions->isNotEmpty();
        }, $alliance->alliance_id) ? Response::allow() : Response::deny($message);
    }

    /**
     * @param  \Seat\Web\Models\Acl\Permission  $permission
     * @param $entity
     * @return bool
     */
    private function isGrantedByFilters(Permission $permission, Alliance $alliance): bool
    {
        $filters = json_decode($permission->pivot->filters);

        // determine if the requested entity is related to a corporation or alliance include in the permission filters
        if ($this->isGrantedByFilter($filters, 'alliance', $alliance->alliance_id))
            return true;

        return false;
    }
}
