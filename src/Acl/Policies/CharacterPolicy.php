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

use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Web\Models\Acl\Permission;
use Seat\Web\Models\User;
use stdClass;

/**
 * Class CharacterPolicy
 *
 * @package Seat\Web\Acl\Policies
 */
class CharacterPolicy extends AbstractCachedPolicy
{
    /**
     * @param \Seat\Web\Models\User $user
     * @param \Seat\Eveapi\Models\Character\CharacterInfo $character
     * @return bool
     */
    public function __call($method, $args)
    {
        // we need two arguments to our Gate (User and CharacterInfo)
        if (count($args) < 2)
            return false;

        $user       = $args[0];
        $character  = $args[1];
        $permission = sprintf('character.%s', $method);

        return $this->userHasPermission($user, $permission, function () use ($user, $character, $permission) {

            // in case the user is owning requested character or is the CEO of it
            if ($this->isOwner($user, $character) || $this->isCeo($user, $character))
                return true;

            // TODO : sharing session

            // retrieve defined authorization for the requested user
            $acl = $this->permissionsFrom($user);

            // filter out roles without required permission
            $permissions = $acl->pluck('permissions')->flatten()->filter(function ($item) use ($character, $permission) {

                // exclude all permissions which does not match with the requested permission
                if ($item->title !== $permission)
                    return false;

                // in case no filters is available, return true as the permission is not limited
                if (is_null($item->pivot->filters))
                    return true;

                // extract filters from the relation
                $filters = json_decode($item->pivot->filters);

                // return true in case this permission filter match
                return $this->isGrantedByFilters($item, $filters, $character);
            });

            // if we have at least one valid permission - grant access
            if ($permissions->isNotEmpty())
                return true;

            // deny access
            return false;
        }, $character->character_id);
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
    private function isCeo(User $user, CharacterInfo $character)
    {
        // retrieve corporation to which the character is assigned
        $corporation_id = $character->affiliation->corporation_id;

        // in case we were not able to find the corporation ID - assume the user is not CEO of this character
        if (is_null($corporation_id))
            return false;

        // attempt to retrieve information related to the corporation
        $corporation = CorporationInfo::find($corporation_id);

        // in case we were not able to find information from this corporation
        // assume the user is not CEO of this character
        if (is_null($corporation))
            return false;

        // if user own the corporation CEO, return true.
        return in_array($corporation->ceo_id, $user->associatedCharacterIds()->toArray());
    }

    /**
     * @param \Seat\Web\Models\User $user
     * @param \Seat\Eveapi\Models\Character\CharacterInfo $character
     * @return bool
     */
    private function isOwner(User $user, CharacterInfo $character)
    {
        return in_array($character->character_id, $user->associatedCharacterIds()->toArray());
    }

    /**
     * @param \Seat\Web\Models\Acl\Permission $permission
     * @param \stdClass $filters
     * @param $entity
     * @return bool
     */
    private function isGrantedByFilters(Permission $permission, stdClass $filters, $entity): bool
    {
        // if the permission is using a character scope, check for character filters
        if ($permission->isCharacterScope() && is_a($entity, CharacterInfo::class)) {

            // determine if the requested character is include in the permission filters
            if ($this->isGrantedByFilter($filters, 'character', $entity->character_id))
                return true;
        }

        // determine if the requested entity is related to a corporation or alliance include in the permission filters
        if ($this->isGrantedByFilter($filters, 'corporation', $entity->affiliation->corporation_id ?? $entity->corporation_id))
            return true;

        if (! is_null($entity->affiliation->alliance_id ?? $entity->alliance_id))
            return $this->isGrantedByFilter($filters, 'alliance', $entity->affiliation->alliance_id ?? $entity->alliance_id);

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
}
