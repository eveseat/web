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

namespace Seat\Web\Acl;

use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Services\Repositories\Character\Character;
use Seat\Services\Repositories\Corporation\Corporation;
use Seat\Web\Exceptions\BouncerException;

/**
 * Class AccessChecker.
 * @package Seat\Web\Acl
 */
trait AccessChecker
{

    // Use repositories to get character & corp info
    use Character, Corporation;

    /**
     * Checks if the user has any of the required
     * permissions.
     *
     * @param array      $permissions
     * @param bool|false $need_affiliation
     *
     * @return bool
     * @throws \Seat\Web\Exceptions\BouncerException
     */
    public function hasAny(array $permissions, bool $need_affiliation = true): bool
    {

        foreach ($permissions as $permission)
            if ($this->has($permission, $need_affiliation))
                return true;

        return false;
    }

    /**
     * Has.
     *
     * This is probably *the* most important function in
     * the ACL logic. It's sole purpose is to ensure that
     * a logged in user has a specific permission.
     *
     * @param      $permission
     * @param bool $need_affiliation
     *
     * @return bool
     * @throws \Seat\Web\Exceptions\BouncerException
     */
    public function has($permission, $need_affiliation = true)
    {

        if ($this->hasSuperUser())
            return true;

        if (! $need_affiliation) {

            if ($this->hasPermissions($permission))
                return true;

        } else {

            if ($this->hasAffiliationAndPermission($permission))
                return true;

        }

        return false;

    }

    /**
     * Determine of the current user has the
     * superuser permission.
     */
    public function hasSuperUser()
    {

        $permissions = $this->getAllPermissions();

        foreach ($permissions as $permission)
            if ($permission === 'superuser') return true;

        return false;
    }

    /**
     * Return an array of all the of the permissions
     * that the user has.
     *
     * @return array
     */
    public function getAllPermissions()
    {

        $permissions = [];

        $roles = $this->group->roles()->with('permissions')->get();

        // Go through every role...
        foreach ($roles as $role) {

            // ... in every defined permission
            foreach ($role->permissions as $permission) {

                // only add permissions if it is not an inverse
                if (! $permission->pivot->not)
                    array_push($permissions, $permission->title);
            }

        }

        return $permissions;
    }

    /**
     * Check if a user has the permission, ignoring
     * affiliation completely.
     *
     * @param $permission
     *
     * @return bool
     */
    public function hasPermissions($permission)
    {

        $permissions = $this->getAllPermissions();

        return in_array($permission, $permissions);
    }

    /**
     * Check if the user is correctly affiliated
     * *and* has the requested permission on that
     * affiliation.
     *
     * @param $permission
     *
     * @return bool
     * @throws \Seat\Web\Exceptions\BouncerException
     */
    public function hasAffiliationAndPermission($permission)
    {

        // TODO: An annoying change in the 3x migration introduced
        // and array based permission, which is stupid. Remove that
        // or plan better for it in 3.1

        $array_permission = $permission;

        if (! is_array($permission))
            $array_permission = (array) $permission;

        // Process entries for character
        if (array_filter($array_permission, [$this, 'permissionCharacterLookup']))
            return $this->hasCharacterPermission($array_permission);

        // Process entries for corporations
        if (array_filter($array_permission, [$this, 'permissionCorporationLookup']))
            return $this->hasCorporationPermission($array_permission);

        return false;
    }

    /**
     * @param array $permission
     *
     * @return bool
     * @throws \Seat\Web\Exceptions\BouncerException
     */
    private function hasCharacterPermission(array $permission)
    {

        $map = $this->getAffiliationMap();

        // Owning a character grants you '*' permissions to the owned object. In this
        // context, '*' acts as a wildcard for *all* permissions
        foreach ($map['char'] as $char => $permissions) {

            // in case the permissions array is not related to the character for which we're requesting access
            // skip
            if ($char != $this->getCharacterId())
                continue;

            // in case a wildcard access has been assigned (user is owner), grant access
            if (in_array('character.*', $permissions))
                return true;

            // yeah, this is dumb. So if we have an array permission, we need to
            // loop and check each in there.
            foreach ($permission as $sub_permission) {

                // check only character wildcard and specific permission
                if (in_array($sub_permission, $permissions))
                    return true;
            }
        }

        return false;
    }

    /**
     * The affiliation map maps character / corporation
     * ID's to the permissions that they have. This allows
     * us to simply lookup the existence of a permission
     * in the context of an ID. All roles are considered
     * but strict adherence to *which* affiliation ID's are
     * applicable is kept.
     *
     * Keys that a character own automatically grants them
     * all permissions to that corp/char keeping in mind
     * of course that the key type needs to match too.
     *
     * @return array
     */
    public function getAffiliationMap()
    {

        // Prepare a skeleton of the final affiliation map that
        // will be returned.
        $map = [
            'char'                  => [],
            'corp'                  => [],

            // These keys keep record of the affiliations and
            // permissions that are marked for inversion.
            'inverted_permissions'  => [],
            'inverted_affiliations' => [
                'char' => [],
                'corp' => [],
            ],
        ];

        // User Accounts inherit the character and
        // corporation ID's on the keys that they are
        // the owner for. They also automatically
        // inherit all permissions for these keys.

        // TODO: Refactor this in 3.1.
        // For now we get the character_ids this user has
        // in the character_groups they belong to, then
        // assign the wildcard permission for that character.
        $user_character_ids = $this->associatedCharacterIds()->toArray();

        foreach ($user_character_ids as $user_character_id) {

            $map['char'][$user_character_id] = ['character.*'];
        }

        // Next we move through the roles the user has
        // and populate the permissions that the affiliations
        // offer us.
        foreach ($this->group->roles as $role) {

            // A blank array for the permissions granted to
            // this role.
            $role_permissions = [];

            // Add the permissions, ignoring those that
            // should be inverted.
            foreach ($role->permissions as $permission) {

                // If a specific permission should be be inverted,
                // update the global array with the permission name.
                //
                // Also make sure the permission does not exist in
                // any of the corp/char arrays.
                if ($permission->pivot->not) {

                    array_push($map['inverted_permissions'], $permission->title);

                    continue;
                }

                // Add the permission to the existing array
                array_push($role_permissions, $permission->title);
            }

            // Add the permissions to the affiliations
            // map for each respective affiliation. We will also keep in
            // mind here that affiliations can have inversions too.
            foreach ($role->affiliations as $affiliation) {

                if ($affiliation->pivot->not && ! in_array($affiliation->affiliation, $user_character_ids)) {

                    array_push(
                        $map['inverted_affiliations'][$affiliation->type],
                        $affiliation->affiliation);

                    continue;
                }

                // It is possible to 'wildcard' users and corporations. This
                // is signified by the char / corp id of 0. If we encounter
                // this id, then we need to all of the possible corp / char
                // in the system to the affiliation map.
                if ($affiliation->affiliation === 0) {

                    if ($affiliation->type == 'char') {

                        // Process all of the characters
                        foreach ($this->getAllCharacters()->pluck('character_id') as $characterID) {

                            if (isset($map['char'][$characterID]))
                                $map['char'][$characterID] += $role_permissions;

                            else
                                $map['char'][$characterID] = $role_permissions;

                        }
                    }

                    if ($affiliation->type == 'corp') {

                        // Process all of the corporations
                        foreach ($this->getAllCorporations()->pluck('corporation_id') as $corporationID) {

                            if (isset($map['corp'][$corporationID]))
                                $map['corp'][$corporationID] += $role_permissions;

                            else
                                $map['corp'][$corporationID] = $role_permissions;

                        }
                    }

                } else {

                    // in case we have an affiliation of corp kind
                    // check if it's containing any character permission and append all character from this corporation
                    if ($affiliation->type == 'corp') {

                        $characters = CharacterInfo::where('corporation_id', $affiliation->affiliation)->get();

                        foreach ($role_permissions as $permission) {
                            if (strpos($permission, 'character.') !== false) {

                                $characters->each(function ($character) use (&$map, $permission) {

                                    if (! isset($map['char'][$character->character_id]))
                                        $map['char'][$character->character_id] = [];

                                    array_push($map['char'][$character->character_id], $permission);

                                });

                            }
                        }

                    }

                    // Add the single affiliation to the map. As we will run this operation
                    // multiple times when multiple roles are involved, we need to check if
                    // affiliations already exist. Not using a ternary of coalesce operator
                    // here as it makes reading this really hard.
                    if (isset($map[$affiliation->type][$affiliation->affiliation]))
                        $map[$affiliation->type][$affiliation->affiliation] += $role_permissions;

                    else
                        $map[$affiliation->type][$affiliation->affiliation] = $role_permissions;
                }

            }

        }

        // Cleanup any inverted affiliations themselves for characters..
        foreach ($map['inverted_affiliations']['char'] as $inverted_affiliation)
            unset($map['char'][$inverted_affiliation]);

        // And corporations
        foreach ($map['inverted_affiliations']['corp'] as $inverted_affiliation)
            unset($map['corp'][$inverted_affiliation]);

        // Cleanup the inverted affiliations' permissions from
        // each of the affiliations.
        //
        // We start by processing characters
        foreach ($map['char'] as $char => $permissions)
            $map['char'][$char] = array_diff($map['char'][$char], $map['inverted_permissions']);

        // And corporations
        foreach ($map['corp'] as $corp => $permissions)
            $map['corp'][$corp] = array_diff($map['corp'][$corp], $map['inverted_permissions']);

        // ESI Related corporation role <-> SeAT role mapping.
        // This is to allow characters that have in game roles
        // such as director or other wallet related roles to view
        // corporation information.
        // TODO: This is going to need a major revamp in 3.1!
        $esi_role_map = [
            'Accountant'        => [
                'corporation.summary',
                'corporation.journal',
                'corporation.transactions',
            ],
            'Auditor'           => [
                'corporation.summary',
            ],
            'Contract_Manager'  => [
                'corporation.summary',
                'corporation.contracts',
            ],
            'Diplomat'          => [
                'corporation.summary',
                'corporation.tracking',
            ],
            'Director'          => ['corporation.*'],   // All roles for you!
            'Junior_Accountant' => [
                'corporation.summary',
                'corporation.journal',
                'corporation.transactions',
            ],
            'Security_Officer'  => [
                'corporation.summary',
                'corporation.security',
            ],
            'Trader'            => [
                'corporation.summary',
                'corporation.market',
            ],
        ];

        // Check if there are corporation roles we can add. If so, add 'em.
        if ($current_corp_roles = optional($this->character)->corporation_roles) {

            // Extract only the roles names and cast to an array for lookups.
            $current_corp_roles = $current_corp_roles->pluck('role')->toArray();

            foreach ($esi_role_map as $ingame_role => $seat_roles) {

                if (in_array($ingame_role, $current_corp_roles)) {

                    if (! isset($map['corp'][$this->character->corporation_id]))
                        $map['corp'][$this->character->corporation_id] = [];

                    foreach ($seat_roles as $seat_role)
                        array_push($map['corp'][$this->character->corporation_id], $seat_role);
                }
            }
        }

        // Finally, return the calculated map!
        return $map;

    }

    /**
     * Return the character_id in question for the current request.
     *
     * @return int
     * @throws \Seat\Web\Exceptions\BouncerException
     */
    public function getCharacterId(): int
    {

        if (! request()->character_id)
            throw new BouncerException(
                __CLASS__ . ' was unable to determine a character_id');

        return request()->character_id;
    }

    /**
     * @param array $permission
     *
     * @return bool
     * @throws \Seat\Web\Exceptions\BouncerException
     */
    private function hasCorporationPermission(array $permission)
    {

        $map = $this->getAffiliationMap();

        foreach ($map['corp'] as $corp => $permissions) {

            // in case the permissions array is not related to the corporation for which we're checking access
            // skip
            if ($corp != $this->getCorporationId())
                continue;

            // in case a wildcard access is in the permissions array, grant access
            if (in_array('corporation.*', $permissions))
                return true;

            foreach ($permission as $sub_permission) {

                if (in_array($sub_permission, $permissions))
                    return true;
            }
        }

        return false;
    }

    /**
     * Return the corporation_id in question for the current request.
     *
     * @return int
     * @throws \Seat\Web\Exceptions\BouncerException
     */
    public function getCorporationId(): int
    {

        if (! request()->corporation_id)
            throw new BouncerException(
                __CLASS__ . ' was unable to determine a corporation_id');

        return request()->corporation_id;
    }

    /**
     * Determine if the current user has a specific
     * role.
     *
     * @param $role_name
     *
     * @return bool
     */
    public function hasRole($role_name)
    {

        if ($this->hasSuperUser())
            return true;

        foreach ($this->group->roles as $role)
            if ($role->title == $role_name)
                return true;

        return false;

    }

    /**
     * @param string $permission
     *
     * @return bool
     */
    private function permissionCharacterLookup(string $permission)
    {

        return strpos($permission, 'character.') !== false;
    }

    /**
     * @param string $permission
     *
     * @return bool
     */
    private function permissionCorporationLookup(string $permission)
    {

        return strpos($permission, 'corporation.') !== false;
    }
}
