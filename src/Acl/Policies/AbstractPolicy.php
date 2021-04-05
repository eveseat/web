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

use Closure;
use Illuminate\Support\Facades\Cache;
use Seat\Web\Models\User;

/**
 * Class AbstractPolicy.
 *
 * @package Seat\Web\Acl\Policies
 */
abstract class AbstractPolicy
{
    // cache active permissions for 10 seconds
    const CACHE_DURATION = 10;

    /**
     * @var string
     */
    protected $ability;

    /**
     * @param \Seat\Web\Models\User $user
     * @param string $ability
     * @return bool|void
     */
    public function before(User $user, $ability)
    {
        $this->ability = $ability;

        if ($user->isAdmin())
            return true;
    }

    /**
     * @param \Seat\Web\Models\User $user
     * @return \Illuminate\Support\Collection
     */
    protected function permissionsFrom(User $user)
    {
        $cache_key = sprintf('users:%d:acl', $user->id);

        return Cache::store('redis')->remember($cache_key, self::CACHE_DURATION, function () use ($user) {
            return $user->roles()->with('permissions')->get()->pluck('permissions')->flatten();
        });
    }

    /**
     * @param \Seat\Web\Models\User $user
     * @param string $permission
     * @param Closure $callback
     * @param int|null $entity_id
     * @return bool
     */
    protected function userHasPermission(User $user, string $permission, Closure $callback, ?int $entity_id = null)
    {
        $cache_key = sprintf('users:%d:acl:permissions:%s:%d', $user->id, $permission, $entity_id ?: 0);

        return Cache::store('redis')->remember($cache_key, self::CACHE_DURATION, $callback) !== false;
    }
}
