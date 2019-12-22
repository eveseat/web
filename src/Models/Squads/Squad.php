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
use Intervention\Image\Facades\Image;
use Seat\Web\Models\Acl\Role;
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
    protected static $unguarded = true;

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
     * Return the logo url-encoded.
     *
     * @param $value
     * @return string
     */
    public function getLogoAttribute($value): string
    {
        if (is_null($value) || empty($value))
            $picture = $this->generateEmptyImage();
        else
            $picture = Image::make($value);

        return (string) $picture->encode('data-url');
    }

    /**
     * Store the file into blob attribute using url-encoding.
     *
     * @param $value
     */
    public function setLogoAttribute($value)
    {
        if (is_null($value) || empty($value)) {
            $this->attributes['logo'] = null;

            return;
        }

        $picture = Image::make($value)->encode('data-url');
        $this->attributes['logo'] = $picture;
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
            ->using(SquadMember::class)
            ->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function moderators()
    {
        return $this->belongsToMany(User::class, 'squad_moderator');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'squad_role')
            ->using(SquadRole::class);
    }

    /**
     * @return \stdClass
     */
    public function getFilters(): stdClass
    {
        return $this->filters;
    }

    /**
     * Generating an empty image canvas.
     *
     * @return \Intervention\Image\Image
     */
    private function generateEmptyImage()
    {
        $picture = Image::canvas(128, 128, '#eee');

        $picture->line(1, 1, 128, 128, function ($draw) {
            $draw->color('#e7e7e7');
        });

        $picture->line(1, 128, 128, 1, function ($draw) {
            $draw->color('#e7e7e7');
        });

        $picture->text('128 x 128', 64, 64, function ($font) {
            $font->file(3);
            $font->color('#bbb');
            $font->align('center');
            $font->valign('middle');
        });

        return $picture;
    }
}
