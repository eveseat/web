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

use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Eveapi\Models\Corporation\CorporationRole;
use Seat\Web\Acl\Response;
use Seat\Web\Models\Acl\Permission;
use Seat\Web\Models\User;

/**
 * Class CharacterPolicy.
 *
 * @package Seat\Web\Acl\Policies
 */
class CharacterPolicy extends AbstractEntityPolicy
{
    /**
     * @param  string  $method
     * @param  array  $args
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function __call($method, $args)
    {
        // we need two arguments to our Gate (User and CharacterInfo)
        if (count($args) < 2)
            return false;

        $user = $args[0];
        $character = $args[1];

        $message = sprintf('Request to %s was denied. The permission required is %s', request()->path(), $this->ability);

        if (is_numeric($character))
            $character = CharacterInfo::find($character);

        return $this->userHasPermission($user, $this->ability, function () use ($user, $character) {

            // in case the user is owning requested character or is the CEO of it
            if ($this->isOwner($user, $character) || $this->isValidSharingSession($character)
                || $this->isCeo($user, $character) || $this->isDirector($user, $character))
                return true;

            // retrieve defined authorization for the requested user
            $acl = $this->permissionsFrom($user);

            // filter out permissions which don't match with required one
            $permissions = $acl->filter(function ($permission) use ($character) {

                // exclude all permissions which does not match with the requested permission
                if ($permission->title !== $this->ability)
                    return false;

                // in case no filters is available, return true as the permission is not limited
                if (! $permission->hasFilters())
                    return true;

                // return true in case this permission filter match
                return $this->isGrantedByFilters($permission, $character);
            });

            // if we have at least one valid permission - grant access
            return $permissions->isNotEmpty();
        }, $character->character_id) ? Response::allow() : Response::deny($message);
    }

    /**
     * @param  \Seat\Web\Models\User  $user
     * @param  \Seat\Eveapi\Models\Character\CharacterInfo  $character
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
        return in_array($corporation->ceo_id, $user->associatedCharacterIds());
    }

    /**
     * @param  \Seat\Web\Models\User  $user
     * @param  \Seat\Eveapi\Models\Character\CharacterInfo  $character
     * @return bool
     */
    private function isDirector(User $user, CharacterInfo $character): bool
    {
        // retrieve corporation to which the character is assigned
        $corporation_id = $character->affiliation->corporation_id ?? null;

        // in case we were not able to find the corporation ID - assume the user is not Director of their corporation
        if (is_null($corporation_id)) {
            return false;
        }

        // attempt to retrieve roles for any associated character related to the corporation
        return CorporationRole::where('corporation_id', $corporation_id)
            ->whereIn('character_id', $user->associatedCharacterIds())
            ->where('role', 'Director')
            ->where('type', 'roles')->exists();
    }

    /**
     * @param  \Seat\Web\Models\User  $user
     * @param  \Seat\Eveapi\Models\Character\CharacterInfo  $character
     * @return bool
     */
    private function isOwner(User $user, CharacterInfo $character)
    {
        return in_array($character->character_id, $user->associatedCharacterIds());
    }

    /**
     * @param  \Seat\Eveapi\Models\Character\CharacterInfo  $character
     * @return bool
     */
    private function isValidSharingSession(CharacterInfo $character)
    {
        return in_array($character->character_id, session()->get('user_sharing', []));
    }

    /**
     * @param  \Seat\Web\Models\Acl\Permission  $permission
     * @param $character
     * @return bool
     */
    private function isGrantedByFilters(Permission $permission, CharacterInfo $character): bool
    {
        $filters = json_decode($permission->pivot->filters);

        // determine if the requested character is include in the permission filters
        if ($this->isGrantedByFilter($filters, 'character', $character->character_id))
            return true;

        // determine if the requested entity is related to a corporation or alliance include in the permission filters
        if ($this->isGrantedByFilter($filters, 'corporation', $character->affiliation->corporation_id))
            return true;

        if (! is_null($character->affiliation->alliance_id))
            return $this->isGrantedByFilter($filters, 'alliance', $character->affiliation->alliance_id);

        return false;
    }
}
