<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015, 2016, 2017, 2018, 2019  Leon Jacobs
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

use Illuminate\Support\Arr;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Services\Repositories\Character\Character;
use Seat\Services\Repositories\Corporation\Corporation;
use Seat\Web\Exceptions\BouncerException;
use Seat\Web\Models\Acl\Permission;
use stdClass;

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
     * @param string|array $permission
     * @param bool $need_affiliation
     *
     * @return bool
     * @throws \Seat\Web\Exceptions\BouncerException
     */
    public function has($permission, $need_affiliation = true)
    {

        if ($this->hasSuperUser())
            return true;

        if ($need_affiliation)
            return $this->hasAffiliationAndPermission($permission);

        return $this->hasPermissions($permission);
    }

    /**
     * Determine of the current user has the
     * superuser permission.
     */
    public function hasSuperUser()
    {

        return in_array('global.superuser', $this->getAllPermissions());
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

        $roles = $this->roles()->with('permissions')->get();

        // Go through every role...
        foreach ($roles as $role) {

            $new_permissions = $role->permissions()
                ->wherePivot('not', '=', false)
                ->wherePivot('filters', '=', null)
                ->pluck('title')
                ->toArray();

            $permissions = array_merge($permissions, $new_permissions);

        }

        return $permissions;
    }

    /**
     * Check if a user has the permission, ignoring
     * affiliation completely.
     *
     * @param string|array $permission
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
     * @param string|array $requested_permission
     * @return bool
     * @throws \Seat\Web\Exceptions\BouncerException
     */
    public function hasAffiliationAndPermission($requested_permission)
    {

        // TODO: An annoying change in the 3x migration introduced
        // and array based permission, which is stupid. Remove that
        // or plan better for it in 3.1

        $array_permission = $requested_permission;

        if (! is_array($requested_permission))
            $array_permission = (array) $requested_permission;

        // if the currently authenticated user is either ceo or owner of the requested entity, grant him access
        if ($this->isCeo() || $this->isOwner())
            return true;

        $character = is_null(request()->character_id) ? null : CharacterInfo::find(request()->character_id);

        $corporation = is_null(request()->corporation_id) ? null : CorporationInfo::find(request()->corporation_id);

        // retrieve only roles which contain the requested permission
        $roles = $this->roles()->whereHas('permissions', function ($query) use ($array_permission) {
            $query->whereIn('title', $array_permission);
        })->get();

        // loop over each roles assigned to the currently authenticated user
        if (! $roles->isEmpty()) {
            foreach ($roles as $role) {

                // pull permissions which are matching to the requested permissions
                $permissions = $role->permissions->whereIn('title', $array_permission)->filter(function ($permission) {
                    return ! $permission->isGlobalScope();
                });

                foreach ($permissions as $role_permission) {

                    // in case the permission does not have any filters, grant access
                    if (is_null($role_permission->pivot->filters))
                        return true;

                    // decode the filters into an object
                    $filters = json_decode($role_permission->pivot->filters);

                    if ($this->isGrantedByFilters($role_permission, $filters, $character ?: $corporation))
                        return true;
                }
            }
        }

        if (! is_null($corporation)) {
            $corporation_permissions = Arr::get($this->getPermissionsFromCorporationRoles(), request()->corporation_id, []);

            if (in_array('corporation.*', $corporation_permissions))
                return true;

            return in_array($requested_permission, $corporation_permissions);
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

        // retrieve all corporations for which the current user is CEO
        $corporations = CorporationInfo::whereIn('ceo_id', $user_character_ids)->get();

        foreach ($corporations as $corporation) {

            // for each of them, grant access to all corporation members
            $characters = CharacterInfo::whereHas('affiliation', function ($query) use ($corporation) {
                $query->where('corporation_id', $corporation->id);
            })
            ->with(['affiliation' => function ($query) use ($corporation) {
                $query->where('corporation_id', $corporation->id);
            }])->get();

            foreach ($characters as $character) {

                $map['char'][$character->character_id] = ['character.*'];

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

                // in case we have a filters, decode it and update the mapping
                if (! is_null($permission->pivot->filters)) {

                    $filters = json_decode($permission->pivot->filters);

                    foreach ($filters as $type => $entities) {

                        switch ($type) {
                            case 'character':
                                $map_type = 'char';
                                break;
                            case 'corporation':
                                $map_type = 'corp';
                                break;
                            default:
                                $map_type = $type;
                        }

                        foreach ($entities as $entity) {

                            $entities_id = collect([$entity->id]);

                            if ($map_type == 'alliance') {

                                if ($permission->isCharacterScope()) {
                                    $map_type = 'char';
                                    $entities_id = $this->getAllCharacters()
                                        ->where('alliance_id', $entity->id)
                                        ->pluck('character_id');
                                }

                                if ($permission->isCorporationScope()) {
                                    $map_type = 'corp';
                                    $entities_id = $this->getAllCorporations()
                                        ->where('alliance_id', $entity->id)
                                        ->pluck('corporation_id');
                                }
                            }

                            if ($map_type == 'corp') {

                                if ($permission->isCharacterScope()) {
                                    $map_type = 'char';
                                    $entities_id = $this->getAllCharacters()
                                        ->where('corporation_id', $entity->id)
                                        ->pluck('character_id');
                                }

                            }

                            foreach ($entities_id as $entity_id) {

                                // in case the type does not exist yet in the mapping array, create an empty one
                                if (! array_key_exists($map_type, $map))
                                    $map[$map_type] = [];

                                // in case the entry does not exist yet in the mapping array, create an empty one
                                if (! array_key_exists($entity_id, $map[$map_type]))
                                    $map[$map_type][$entity_id] = [];

                                // in case the map entry is already containing the permission, continue to the next entity
                                if (in_array($permission->title, $map[$map_type][$entity_id]))
                                    continue;

                                // push the permission into the entity map entry
                                array_push($map[$map_type][$entity_id], $permission->title);
                            }
                        }

                    }

                } else {

                    $map_type = '';
                    $entities_id = collect();

                    // in case the permission is character scope, get all characters and push them into the map with
                    // the related permission
                    if ($permission->isCharacterScope()) {
                        $map_type = 'char';
                        $entities_id = $this->getAllCharacters()->pluck('character_id');
                    }

                    // in case the permission is corporation scope, get all corporations and push them into the map with
                    // the related permission
                    if ($permission->isCorporationScope()) {
                        $map_type = 'corp';
                        $entities_id = $this->getAllCorporations()->pluck('corporation_id');
                    }

                    foreach ($entities_id as $entity_id) {

                        if (! array_key_exists($entity_id, $map[$map_type]))
                            $map[$map_type][$entity_id] = [];

                        if (in_array($permission->title, $map[$map_type][$entity_id]))
                            continue;

                        array_push($map[$map_type][$entity_id], $permission->title);
                    }
                }
            }
        }

        // ESI Related corporation role <-> SeAT role mapping.
        // This is to allow characters that have in game roles
        // such as director or other wallet related roles to view
        // corporation information.
        // TODO: This is going to need a major revamp in 3.1!

        // Check if there are corporation roles we can add. If so, add 'em.
        $corp_role_permissions_map = $this->getPermissionsFromCorporationRoles();

        foreach ($corp_role_permissions_map as $corporation_id => $permissions) {
            if (! array_key_exists($corporation_id, $map['corp']))
                $map['corp'][$corporation_id] = [];

            $map['corp'][$corporation_id] = array_unique(array_merge($map['corp'][$corporation_id], $permissions));
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
     * @param string $role_name
     *
     * @return bool
     */
    public function hasRole(string $role_name)
    {

        if ($this->hasSuperUser())
            return true;

        foreach ($this->roles as $role)
            if ($role->title == $role_name)
                return true;

        return false;

    }

    /**
     * Determine if the currently authenticated user is the character owner.
     *
     * @return bool
     */
    private function isOwner(): bool
    {
        if (! request()->character_id)
            return false;

        return in_array(request()->character_id, $this->associatedCharacterIds()->toArray());
    }

    /**
     * Determine if the currently authenticated user is the corporation CEO.
     *
     * @return bool
     */
    private function isCeo(): bool
    {
        $corporation_id = null;

        if (request()->corporation_id)
            $corporation_id = request()->corporation_id;

        if (request()->character_id) {
            $character = CharacterInfo::find(request()->character_id);
            $corporation_id = $character ? $character->affiliation->corporation_id : null;
        }

        if (is_null($corporation_id))
            return false;

        $corporation = CorporationInfo::find($corporation_id);

        if (is_null($corporation))
            return false;

        return in_array($corporation->ceo_id, $this->associatedCharacterIds()->toArray());
    }

    /**
     * Determine if the requested entity is granted by a permission filter.
     *
     * @param \Seat\Web\Models\Acl\Permission $permission
     * @param stdClass $filters
     * @param mixed $entity
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
        return $this->isGrantedByFilter($filters, 'corporation', $entity->affiliation->corporation_id ?? $entity->corporation_id) ||
            $this->isGrantedByFilter($filters, 'alliance', $entity->affiliation->alliance_id ?? $entity->alliance_id);
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
     * Return corporation permission map based on character roles
     * according to esi map.
     *
     * @return array
     */
    private function getPermissionsFromCorporationRoles(): array
    {
        $permissions = [];

        if (is_null($this->character))
            return $permissions;

        // Extract only the roles names and cast to an array for lookups.
        $current_corp_roles = $this->character->corporation_roles->pluck('role')->toArray();

        foreach ($current_corp_roles as $role_name) {
            $element = EsiRolesMap::map()->get($role_name);

            if (! is_null($element)) {
                $permissions = array_merge($permissions, $element->permissions());
            }
        }

        return [
            $this->character->affiliation->corporation_id => $permissions,
        ];
    }
}
