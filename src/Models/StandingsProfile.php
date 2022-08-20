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

namespace Seat\Web\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class StandingsProfile.
 *
 * @package Seat\Web\Models
 */
class StandingsProfile extends Model
{
    /**
     * @var string
     */
    protected $table = 'standings_profiles';

    /**
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Make sure we cleanup on delete.
     *
     * @return bool|null
     *
     * @throws \Exception
     */
    public function delete()
    {

        foreach ($this->entities as $entity) {
            $entity->delete();
        }

        return parent::delete();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function entities()
    {

        return $this->hasMany(StandingsProfileStanding::class, 'standings_profile_id');
    }
}
