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
use Seat\Eveapi\Models\RefreshToken;
use Seat\Services\Settings\Profile;
use Seat\Web\Models\Acl\Role;

/**
 * Class Group.
 * @package Seat\Web\Models
 */
class Group extends Model
{

    /**
     * @var array
     */
    protected $fillable = ['main_character_id'];

    /**
     * Return the ID of main character tied to this group.
     *
     * @throws \Seat\Services\Exceptions\SettingException
     */
    public function getMainCharacterIdAttribute()
    {
        return Profile::get('main_character_id', $this->id);
    }

    /**
     * Return the main character tied to this group.
     *
     * @return null|User
     */
    public function getMainCharacterAttribute() : ?User
    {
        return User::find($this->main_character_id);
    }

    /**
     * Return the email address for this user based on the
     * email address setting.
     *
     * @return mixed
     * @throws \Seat\Services\Exceptions\SettingException
     */
    public function getEmailAttribute()
    {

        return Profile::get('email_address', $this->id);
    }

    /**
     * Return the Users that are in this group.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {

        return $this->hasMany(User::class);
    }

    /**
     * Return all refresh tokens tied to the group.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function refresh_tokens()
    {

        return $this->hasManyThrough(RefreshToken::class, User::class, 'group_id', 'character_id', 'id', 'id');
    }

    /**
     * This group may have certain roles assigned.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {

        return $this->belongsToMany(Role::class);
    }
}
