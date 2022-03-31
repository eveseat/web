<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2022 Leon Jacobs
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

use Illuminate\Support\Facades\Gate;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Yajra\DataTables\Contracts\DataTableScope;

/**
 * Class KillMailCharacterScope.
 *
 * This is a specific scope for kill-mails in order to avoid collision on character_id field.
 *
 * @package Seat\Web\Http\DataTables\Scopes
 */
class KillMailCharacterScope implements DataTableScope
{
    /**
     * @var int[]
     */
    private $requested_characters;

    /**
     * KillMailCharacterScope constructor.
     *
     * @param  int[]|null  $character_ids
     */
    public function __construct(?array $character_ids = null)
    {
        $this->requested_characters = $character_ids;
    }

    /**
     * Apply a query scope.
     *
     * @param  \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder  $query
     * @return mixed
     */
    public function apply($query)
    {
        if ($this->requested_characters != null) {
            $character_ids = collect($this->requested_characters)->filter(function ($item) {
                return Gate::allows('character.killmail', [$item]);
            });

            return $query->where(function ($sub_query) use ($character_ids) {
                $sub_query->whereHas('attackers', function ($query) use ($character_ids) {
                    return $character_ids->count() == count($this->requested_characters) ?
                        $query->whereIn('killmail_attackers.character_id', $character_ids) :
                        $query->whereIn('killmail_attackers.character_id', []);
                })->orWhereHas('victim', function ($query) use ($character_ids) {
                    return $character_ids->count() == count($this->requested_characters) ?
                        $query->whereIn('killmail_victims.character_id', $character_ids) :
                        $query->whereIn('killmail_victims.character_id', []);
                });
            });
        }

        if (auth()->user()->isAdmin())
            return $query;

        // collect metadata related to required permission
        $permissions = auth()->user()->roles()->with('permissions')->get()
            ->pluck('permissions')
            ->flatten()
            ->filter(function ($permission) {
                return $permission->title == 'character.killmail';
            });

        // in case at least one permission is granted without restrictions, return all
        if ($permissions->filter(function ($permission) { return ! $permission->hasFilters(); })->isNotEmpty())
            return $query;

        // extract entity ids and group by entity type
        $map = $permissions->map(function ($permission) {
            $filters = json_decode($permission->pivot->filters);

            return [
                'characters'   => collect($filters->character ?? [])->pluck('id')->toArray(),
                'corporations' => collect($filters->corporation ?? [])->pluck('id')->toArray(),
                'alliances'    => collect($filters->alliance ?? [])->pluck('id')->toArray(),
            ];
        });

        // collect at least user owned characters
        $owned_range = auth()->user()->associatedCharacterIds();

        $characters_range = $map->pluck('characters')->flatten()->toArray();

        $corporations_range = CharacterInfo::whereHas('affiliation', function ($affiliation) use ($map) {
            $affiliation->whereIn('corporation_id', $map->pluck('corporations')->flatten()->toArray());
        })->select('character_id')->get()->pluck('character_id')->toArray();

        $alliances_range = CharacterInfo::whereHas('affiliation', function ($affiliation) use ($map) {
            $affiliation->whereIn('alliance_id', $map->pluck('alliances')->flatten()->toArray());
        })->select('character_id')->get()->pluck('character_id')->toArray();

        // sharelink
        $sharelink = session()->get('user_sharing') ?: [];

        // merge all collected characters IDs in a single array and apply filter
        $character_ids = array_merge($characters_range, $corporations_range, $alliances_range, $owned_range, $sharelink);

        return $query->where(function ($sub_query) use ($character_ids) {
            $sub_query->whereHas('attackers', function ($query) use ($character_ids) {
                $query->whereIntegerInRaw('killmail_attackers.character_id', $character_ids);
            })->orWhereHas('victim', function ($query) use ($character_ids) {
                $query->whereIntegerInRaw('killmail_victims.character_id', $character_ids);
            });
        });
    }
}
