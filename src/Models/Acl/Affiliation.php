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

namespace Seat\Web\Models\Acl;

use Illuminate\Database\Eloquent\Model;
use Seat\Web\Models\User;

/**
 * Class Affiliation.
 * @package Seat\Web\Models\Acl
 */
class Affiliation extends Model
{
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['affiliation', 'type'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {

        return $this->belongsToMany(User::class)
            ->withPivot('not');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {

        return $this->belongsToMany(Role::class)
            ->withPivot('not');
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeChar($query)
    {

        return $query->where('type', 'char');
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeCorp($query)
    {

        return $query->where('type', 'corp');
    }
}
