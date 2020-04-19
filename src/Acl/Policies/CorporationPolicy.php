<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2020 Leon Jacobs
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

use Illuminate\Support\Facades\Cache;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Web\Acl\EsiRolesMap;
use Seat\Web\Models\Acl\Permission;
use Seat\Web\Models\User;
use stdClass;

/**
 * Class CorporationPolicy.
 *
 * @package Seat\Web\Acl\Policies
 */
class CorporationPolicy extends AbstractCachedPolicy
{
    /**
     * @param string $method
     * @param array $args
     * @return bool
     */
    public function __call($method, $args)
    {
        if (count($args) < 2)
            return false;

        $user        = $args[0];
        $corporation = $args[1];
        $ability     = sprintf('corporation.%s', $method);

        return $this->userHasPermission($user, $ability, function () use ($user, $corporation, $ability) {

            // in case the user is corporation CEO
            if ($this->isCeo($user, $corporation))
                return true;

            // in case the user is owning a role in-game mapped to this ability
            if ($this->hasDelegatedPermission($user, $corporation, $ability))
                return true;

            // retrieve defined authorization for the requested user
            $acl = $this->permissionsFrom($user);

            // filter out roles without required permission
            $permissions = $acl->filter(function ($permission) use ($corporation, $ability) {

                // exclude all permissions which does not match with the requested permission
                if ($permission->title != $ability)
                    return false;

                // in case no filters is available, return true as the permission is not limited
                if (is_null($permission->pivot->filters))
                    return true;

                // extract filters from the relation
                $filters = json_decode($permission->pivot->filters);

                // return true in case this permission filter match
                return $this->isGrantedByFilters($permission, $filters, $corporation);
            });

            // if we have at least one valid permission - grant access
            if ($permissions->isNotEmpty())
                return true;

            // deny access
            return false;
        }, $corporation->corporation_id);
    }

    /**
     * @param \Seat\Web\Models\User $user
     * @param string $ability
     * @return bool|void
     */
    public function before(User $user, $ability)
    {
        if ($user->hasSuperUser())
            return true;
    }

    /**
     * @param \Seat\Web\Models\User $user
     * @param \Seat\Eveapi\Models\Character\CharacterInfo $character
     * @return bool
     */
    private function isCeo(User $user, CorporationInfo $corporation)
    {
        // if user own the corporation CEO, return true.
        return in_array($corporation->ceo_id, $user->associatedCharacterIds()->toArray());
    }

    /**
     * @param \Seat\Web\Models\Acl\Permission $permission
     * @param \stdClass $filters
     * @param $entity
     * @return bool
     */
    private function isGrantedByFilters(Permission $permission, stdClass $filters, CorporationInfo $corporation): bool
    {
        // determine if the requested entity is related to a corporation or alliance include in the permission filters
        if ($this->isGrantedByFilter($filters, 'corporation', $corporation->corporation_id))
            return true;

        if (! is_null($corporation->alliance_id))
            return $this->isGrantedByFilter($filters, 'alliance', $corporation->alliance_id);

        return false;
    }

    /**
     * Determine if the requested entity is granted by the specified permission filter.
     *
     * @param stdClass $filters
     * @param string $entity_type
     * @param int $entity_id
     * @return bool
     */
    private function isGrantedByFilter(stdClass $filters, string $entity_type, int $entity_id): bool
    {
        if (! property_exists($filters, $entity_type))
            return false;

        return collect($filters->$entity_type)->contains('id', $entity_id);
    }

    /**
     * Return true in case the requested ability is mapped to a role owned by the user inside this corporation.
     *
     * @param \Seat\Web\Models\User $user
     * @param \Seat\Eveapi\Models\Corporation\CorporationInfo $corporation
     * @param string $ability
     * @return bool
     */
    private function hasDelegatedPermission(User $user, CorporationInfo $corporation, string $ability): bool
    {
        $roles = $this->corporationRolesFrom($user, $corporation);

        foreach ($roles as $name) {
            $element = EsiRolesMap::map()->get($name);

            if (! is_null($element) && in_array($ability, $element->permissions()))
                return true;
        }

        return false;
    }

    /**
     * Return a list of all roles owned by user inside the corporation.
     *
     * @param \Seat\Web\Models\User $user
     * @param \Seat\Eveapi\Models\Corporation\CorporationInfo $corporation
     * @return array
     */
    private function corporationRolesFrom(User $user, CorporationInfo $corporation): array
    {
        $cache_key = sprintf('users:%d:acl:corporation_roles:%d', $user->id, $corporation->corporation_id);

        return Cache::store('redis')->remember($cache_key, self::CACHE_DURATION, function () use ($user, $corporation) {
            return $user->characters->filter(function ($character) use ($corporation) {
                return $character->affiliation->corporation_id === $corporation->corporation_id;
            })->pluck('corporation_roles')->flatten()->where('scope', 'roles')->unique('role')->pluck('role')->toArray();
        });
    }
}
