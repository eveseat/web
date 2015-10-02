<?php
/*
This file is part of SeAT

Copyright (C) 2015  Leon Jacobs

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

/**
 * Class Clipboard
 * @package Seat\Web\Acl
 */
trait Clipboard
{

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
     * The affiliation map maps character / corporation
     * ID's to the permissions that they have. This allows
     * us to simply lookup a the existance of a permission
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

        $map = [
            'char' => [],
            'corp' => []
        ];

        // User Accounts inherit the character and
        // corporation ID's on the keys that they are
        // the owner for. They also automatically
        // inherit all permissions for these keys.
        foreach ($this->keys as $key) {

            foreach ($key->characters as $character) {

                if ($key->info->type === 'Corporation')
                    $map['corp'][$character->corporationID] = ['*'];

                $map['char'][$character->characterID] = ['*'];
            }

        }

        // Next we move through the roles the user has
        // and populate the permissions that the affiliations
        // offer us.
        foreach ($this->roles as $role) {

            $role_permissions = $role
                ->permissions
                ->lists('title')
                ->toArray();

            // Add the permissions to the affiliations
            // map for each respective affiliation
            foreach ($role->affiliations as $affilition) {

                isset($map[$affilition->type][$affilition->affiliation]) ?
                    $map[$affilition->type][$affilition->affiliation] += $role_permissions :
                    $map[$affilition->type][$affilition->affiliation] = $role_permissions;

            }

        }

        return $map;
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
}
