<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2021 Leon Jacobs
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

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use LasseRafn\InitialAvatarGenerator\InitialAvatar;
use Seat\Web\Http\Scopes\SquadScope;
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
    protected $appends = [
        'summary', 'is_candidate', 'is_member', 'is_moderated', 'is_moderator', 'link',
        'applications_count', 'members_count', 'moderators_count',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'is_classified' => 'boolean',
    ];

    /**
     * @var array
     */
    protected $hidden = [
        'applications', 'members',
    ];

    /**
     * @var bool
     */
    protected static $unguarded = true;

    /**
     * {@inheritdoc}
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new SquadScope());

        self::updated(function ($model) {

            // apply updates only if filters or type has been altered
            if (! array_key_exists('filters', $model->getChanges()) &&
                ! array_key_exists('type', $model->getChanges()))
                return;

            // kick members which are non longer eligible according to new filters
            $model->members->each(function ($user) use ($model) {
                if (! $model->isEligible($user))
                    $model->members()->detach($user->id);
            });

            // invite members which are eligible according to new filters (only for auto squads)
            if ($model->type == 'auto') {
                $users = User::standard()
                    ->whereDoesntHave('squads', function (Builder $query) use ($model) {
                        $query->where('id', $model->id);
                    })->get();

                $users->each(function ($user) use ($model) {
                    if ($model->isEligible($user))
                        $model->members()->save($user);
                });
            }
        });
    }

    /**
     * @return bool
     */
    public function getIsCandidateAttribute(): bool
    {
        return $this->applications->where('user_id', auth()->user()->id)->count() !== 0;
    }

    /**
     * @return bool
     */
    public function getIsMemberAttribute(): bool
    {
        return $this->members->where('id', auth()->user()->id)->count() !== 0;
    }

    /**
     * @return bool
     */
    public function getIsModeratedAttribute(): bool
    {
        return $this->moderators->isNotEmpty();
    }

    /**
     * @return bool
     */
    public function getIsModeratorAttribute(): bool
    {
        return $this->moderators->where('id', auth()->user()->id)->count() !== 0;
    }

    /**
     * @return string
     */
    public function getSummaryAttribute(): string
    {
        if (is_null($this->description) || empty($this->description))
            return '';

        return Str::limit(strip_tags($this->description));
    }

    /**
     * @return int
     */
    public function getApplicationsCountAttribute(): int
    {
        return auth()->user()->can('squads.manage_candidates', $this) ? $this->applications->count() : 0;
    }

    /**
     * @return int
     */
    public function getMembersCountAttribute(): int
    {
        return auth()->user()->can('squads.show_members', $this) ? $this->members->count() : 0;
    }

    /**
     * @return int
     */
    public function getModeratorsCountAttribute(): int
    {
        return $this->moderators->count();
    }

    /**
     * @return string
     */
    public function getLinkAttribute(): string
    {
        return route('squads.show', $this->id);
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
            $picture = (is_null($this->name) || empty($this->name)) ? $this->generateEmptyImage() : $this->generateNameImage();
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
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCandidate($query)
    {
        return $query->whereHas('applications', function ($sub_query) {
            $sub_query->where('user_id', auth()->user()->id);
        });
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMember($query)
    {
        return $query->whereHas('members', function ($sub_query) {
            $sub_query->where('user_id', auth()->user()->id);
        });
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeModerator($query)
    {
        return $query->whereHas('moderators', function ($sub_query) {
            $sub_query->where('user_id', auth()->user()->id);
        });
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeModerated($query)
    {
        return $query->has('moderators');
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $types
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType($query, $types)
    {
        return $query->whereIn('type', $types);
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
        return json_decode($this->filters);
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

    /**
     * @return \Intervention\Image\Image
     */
    private function generateNameImage()
    {
        $picture = new InitialAvatar();

        return $picture->name($this->name)
            ->size(256)
            ->color('#c2c7d0')
            ->background('#343a40')
            ->generate();
    }
}
