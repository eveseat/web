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

namespace Seat\Web\Models\Squads;

use Illuminate\Database\Eloquent\Model;
use Seat\Web\Models\Filterable;
use Seat\Web\Models\User;
use stdClass;

/**
 * Class Squad.
 *
 * @package Seat\Web\Models\Squads
 */
class Squad extends Model
{
    use Filterable;

    /**
     * @var array
     */
    protected $casts = [
        'filters' => 'object',
    ];

    /**
     * @var bool
     */
    protected $guarded = false;

    /**
     * @return bool
     */
    public function isCandidate(): bool
    {
        return $this->applications->where('user_id', auth()->user()->id)->count() !== 0;
    }

    /**
     * @return bool
     */
    public function isMember(): bool
    {
        return $this->members->where('id', auth()->user()->id)->count() !== 0;
    }

    /**
     * @return bool
     */
    public function isModerator(): bool
    {
        return $this->moderators->where('id', auth()->user()->id)->count() !== 0;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function applications()
    {
        return $this->hasMany(SquadApplication::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function members()
    {
        return $this->belongsToMany(User::class, 'squad_member')
            ->withPivot('created_at');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function moderators()
    {
        return $this->belongsToMany(User::class, 'squad_moderator');
    }

    /**
     * @return \stdClass
     */
    public function getFilters(): stdClass
    {
        return $this->filters;
    }
}
