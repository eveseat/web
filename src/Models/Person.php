<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015, 2016, 2017, 2018  Leon Jacobs
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
 * Class Person.
 * @package Seat\Web\Models
 */
class Person extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'main_character_id', 'main_character_name', ];

    /**
     * Make sure we cleanup on delete.
     *
     * @return bool|null
     * @throws \Exception
     */
    public function delete()
    {

        // Cleanup the person group
        $this->members()->delete();

        return parent::delete();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function members()
    {

        return $this->hasMany(PersonMember::class);
    }
}
