<?php
/*
This file is part of SeAT

Copyright (C) 2015, 2016  Leon Jacobs

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/

namespace Seat\Web\Acl;

use Seat\Services\Repositories\Character\Character;
use Seat\Services\Repositories\Corporation\Corporation;

/**
 * Class AccessChecker
 * @package Seat\Web\Acl
 */
trait AccessChecker
{

    // Use repositories to get character & corp info
    use Character, Corporation;

    /**
     * The CharacterID from the request
     *
     * @var
     */
    protected $character_id;

    /**
     * The CorporationID from the request
     *
     * @var
     */
    protected $corporation_id;

    /**
     * Checks if the user has any of the required
     * permissions
     *
     * @param array      $permissions
     * @param bool|false $need_affiliation
     *
     * @return bool
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
     */
    public function has($permission, $need_affiliation = true)
    {

        if ($this->hasSuperUser())
            return true;

        if (!$need_affiliation) {

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
     * superuser permission
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

        $roles = $this
            ->roles()
            ->with('permissions')
            ->get();

        // Go through every role...
        foreach ($roles as $role) {

            // ... in every defined permission
            foreach ($role->permissions as $permission)
                array_push($permissions, $permission->title);

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

        if (in_array($permission, $permissions))
            return true;

        return false;
    }

    /**
     * Check if the user is correctly affiliated
     * *and* has the requested permission on that
     * affiliation.
     *
     * @param $permission
     *
     * @return bool
     */
    public function hasAffiliationAndPermission($permission)
    {

        $map = $this->getAffiliationMap();

        // Owning a key grants you '*' permissions. In this
        // context, '*' acts as a wildard for *all* permissions
        // for this character / corporation ID
        foreach ($map['char'] as $char => $permissions)
            if ($char == $this->getCharacterId() && (
                    in_array($permission, $permissions) || in_array('*', $permissions))
            )
                return true;

        foreach ($map['corp'] as $corp => $permissions)
            if ($corp == $this->getCorporationId() && (
                    in_array($permission, $permissions) || in_array('*', $permissions))
            )
                return true;

        return false;
    }

    /**
     * The affiliation map maps character / corporation
     * ID's to the permissions that they have. This allows
     * us to simply lookup the existance of a permission
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
                'corp' => []
            ]
        ];

        // User Accounts inherit the character and
        // corporation ID's on the keys that they are
        // the owner for. They also automatically
        // inherit all permissions for these keys.
        foreach ($this->keys as $key) {

            foreach ($key->characters as $character) {

                // We only grant corporation related permission
                if ($key->info->type === 'Corporation') {
                    // clone permissions from configuration
                    $permissions = config('web.permissions.corporation');

                    // prefix all permissions by corporation
                    array_walk($permissions, function (&$value) {
                        $value = 'corporation.' . $value;
                    });

                    // assign permission
                    $map['corp'][$character->corporationID] = $permissions;
                }

                // clone permissions from configuration
                $permissions = config('web.permissions.character');

                // prefix all permissions by character
                array_walk($permissions, function(&$value){
                    $value = 'character.' . $value;
                });

                // We only grant character related permission
                $map['char'][$character->characterID] = $permissions;
            }

        }

        // Next we move through the roles the user has
        // and populate the permissions that the affiliations
        // offer us.
        foreach ($this->roles as $role) {

            // A blank array for the permissions granted to
            // this role.
            $role_permissions = [];

            // Add the permissions, ignoring those that
            // should be inverted.
            foreach ($role->permissions as $permission) {

                // If a specific permision shoul be be inverted,
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
            foreach ($role->affiliations as $affilition) {

                if ($affilition->pivot->not) {

                    array_push(
                        $map['inverted_affiliations'][$affilition->type],
                        $affilition->affiliation);

                    continue;
                }

                // It is possible to 'wildcard' users and corporations. This
                // is signified by the char / corp id of 0. If we encounter
                // this id, then we need to all of the possible corp / char
                // in the system to the affiliation map.
                if ($affilition->affiliation === 0) {

                    if ($affilition->type == 'char') {

                        // Process all of the characters
                        foreach ($this->getAllCharacters()->pluck('characterID') as $characterID) {

                            if (isset($map['char'][$characterID]))
                                $map['char'][$characterID] += $role_permissions;

                            else
                                $map['char'][$characterID] = $role_permissions;

                        }
                    }

                    if ($affilition->type == 'corp') {

                        // Process all of the corporations
                        foreach ($this->getAllCorporations()->pluck('corporationID') as $corporationID) {

                            if (isset($map['corp'][$corporationID]))
                                $map['corp'][$corporationID] += $role_permissions;

                            else
                                $map['corp'][$corporationID] = $role_permissions;

                        }
                    }

                } else {

                    // Add the single affiliation to the map. As we will run this operation
                    // multiple times when multiple roles are involved, we need to check if
                    // affiliations already exist. Not using a ternary of coalesce operator
                    // here as it makes reading this really hard.
                    if (isset($map[$affilition->type][$affilition->affiliation]))
                        $map[$affilition->type][$affilition->affiliation] += $role_permissions;

                    else
                        $map[$affilition->type][$affilition->affiliation] = $role_permissions;
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

        // Finally, return the calculated map!
        return $map;

    }

    /**
     * @return mixed
     */
    public function getCharacterId()
    {

        return $this->character_id;
    }

    /**
     * @param mixed $character_id
     */
    public function setCharacterId($character_id)
    {

        $this->character_id = $character_id;
    }

    /**
     * @return mixed
     */
    public function getCorporationId()
    {

        return $this->corporation_id;
    }

    /**
     * @param mixed $corporation_id
     */
    public function setCorporationId($corporation_id)
    {

        $this->corporation_id = $corporation_id;
    }

    /**
     * Determine if the current user has a specific
     * role
     *
     * @param $role_name
     *
     * @return bool
     */
    public function hasRole($role_name)
    {

        if ($this->hasSuperUser())
            return true;

        foreach ($this->roles() as $role)
            if ($role->title == $role_name)
                return true;

        return false;

    }
}
