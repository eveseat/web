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

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\RefreshToken;
use Seat\Services\Models\UserSetting;
use Seat\Web\Acl\AccessChecker;
use Seat\Web\Models\Acl\Affiliation;
use Seat\Web\Models\Acl\Role;

/**
 * Class User.
 * @package Seat\Web\Models
 */
class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword, AccessChecker, Notifiable;

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'character_owner_hash',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * Make sure we cleanup on delete.
     *
     * @return bool|null
     * @throws \Exception
     */
    public function delete()
    {

        // Cleanup the user
        $this->login_history()->delete();
        $this->roles()->detach();
        $this->affiliations()->detach();

        return parent::delete();
    }

    /**
     * Users have a login history.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function login_history()
    {

        return $this->hasMany(UserLoginHistory::class);
    }

    /**
     * This user may have certain roles assigned.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {

        return $this->belongsToMany(Role::class);
    }

    /**
     * This user may be affiliated manually to
     * other characterID's and or corporations.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function affiliations()
    {

        return $this->belongsToMany(Affiliation::class)
            ->withPivot('not');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function character()
    {

        return $this->belongsTo(CharacterInfo::class, 'id', 'character_id');
    }

    /**
     * An alias attribute for the character_id.
     *
     * @return mixed
     */
    public function getCharacterIdAttribute()
    {

        return $this->id;
    }

    /**
     * Return the character ID's this user is associated with as a
     * result of common group memberships.
     *
     * These are basically the characters the same account has logged
     * in with using the "link another" button.
     *
     * @return \Illuminate\Support\Collection
     */
    public function associatedCharacterIds()
    {

        return $this->groups()->get()->map(function ($group) {

            return $group->users->pluck('id');

        })->flatten();
    }

    /**
     * Get the group the current user belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {

        return $this->belongsToMany(Group::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function refresh_token()
    {

        return $this->hasOne(RefreshToken::class, 'character_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function settings()
    {

        return $this->hasMany(UserSetting::class);
    }
}
