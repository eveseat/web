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

namespace Seat\Web\Http\DataTables\Scopes;

use Illuminate\Support\Arr;
use Yajra\DataTables\Contracts\DataTableScope;

/**
 * Class BookmarkCharacterScope.
 *
 * This is a specific scope for bookmark in order to avoid collision on character_id field.
 *
 * @package Seat\Web\Http\DataTables\Scopes
 */
class BookmarkCharacterScope implements DataTableScope
{
    /**
     * @var string
     */
    private $permission;

    /**
     * @var array
     */
    private $character_ids = [];

    /**
     * CharacterScope constructor.
     *
     * @param array $character_ids
     */
    public function __construct(string $permission, int $character_id, ?array $character_ids)
    {
        $this->permission = $permission;

        if (is_null($character_ids))
            $character_ids = [];

        $this->character_ids = array_merge([$character_id], $character_ids);
    }

    /**
     * Apply a query scope.
     *
     * @param \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $query
     * @return mixed
     */
    public function apply($query)
    {
        $character_ids = [];
        $map = Arr::get(auth()->user()->getAffiliationMap(), 'char');

        // in case user is super, apply filter over all requested characters
        if (auth()->user()->hasSuperUser()) {
            $character_ids = $this->character_ids;
        }

        // otherwise, determine to which character the user has access and include them in applied filters
        // reject each other
        if (empty($character_ids)) {
            foreach ($this->character_ids as $character_id) {
                if (in_array(Arr::has($map, (int) $character_id), ['character.*', $this->permission]))
                    $character_ids[] = (int) $character_id;
            }
        }

        return $query->whereIn('character_bookmarks.character_id', $this->character_ids);
    }
}
