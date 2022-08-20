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

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\RefreshToken;
use Seat\Services\Models\UserSetting;
use Seat\Services\Settings\Profile;
use Seat\Web\Models\Acl\Role;
use Seat\Web\Models\Squads\Squad;
use Seat\Web\Models\Squads\SquadMember;

/**
 * Class User.
 *
 * @package Seat\Web\Models
 */
class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, Notifiable;

    /**
     * @var bool
     */
    public $incrementing = true;

    /**
     * @var array
     */
    protected $casts = [
        'active' => 'boolean',
        'admin'  => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'main_character_id', 'active', 'admin',
    ];

    /**
     * The accessors to hide from the model's array form.
     *
     * @var array
     */
    protected $hidden = ['admin', 'remember_token'];

    /**
     * The attributes included in model's JSON form.
     *
     * @var array
     */
    protected $appends = ['email'];

    /**
     * Make sure we cleanup on delete.
     *
     * @return bool|null
     *
     * @throws \Exception
     */
    public function delete()
    {
        // Cleanup the user
        $this->login_history()->delete();
        $this->refresh_tokens->each(function ($token) {
            event('security.log', [
                sprintf('Token tied to character %d has been permanently removed due to user %s deletion.',
                    $token->character_id,
                    $this->name),
                'authentication',
            ]);

            $token->forceDelete();
        });
        $this->settings()->delete();

        return parent::delete();
    }

    /**
     * Return the email address for this user based on the
     * email address setting.
     *
     * @return mixed
     *
     * @throws \Seat\Services\Exceptions\SettingException
     */
    public function getEmailAttribute()
    {

        return Profile::get('email_address', $this->id) ?: '';
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function refresh_tokens()
    {
        return $this->hasMany(RefreshToken::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function settings()
    {

        return $this->hasMany(UserSetting::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function characters()
    {

        return $this->hasManyThrough(CharacterInfo::class, RefreshToken::class, 'user_id', 'character_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\
     */
    public function all_characters()
    {

        return CharacterInfo::whereIn('character_id', RefreshToken::withTrashed()->where('user_id', $this->id)->pluck('character_id'))->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function main_character()
    {
        return $this->hasOne(CharacterInfo::class, 'character_id', 'main_character_id')
            ->withDefault();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function moderators()
    {
        return $this->belongsToMany(Squad::class, 'squad_moderator');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function squads()
    {
        return $this->belongsToMany(Squad::class, 'squad_member')
            ->using(SquadMember::class)
            ->withPivot('created_at');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sharelinks()
    {
        return $this->hasMany(UserSharelink::class);
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStandard($query)
    {
        return $query->where('name', '<>', 'admin');
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSystem($query)
    {
        return $query->where('name', 'admin');
    }

    /**
     * Return the character ID's this user is associated with as a
     * result of common group memberships.
     *
     * These are basically the characters the same account has logged
     * in with using the "link another" button.
     *
     * @return array
     */
    public function associatedCharacterIds(): array
    {
        return $this->characters->pluck('character_id')->values()->toArray();
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->admin === true;
    }
}
