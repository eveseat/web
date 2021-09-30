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

namespace Seat\Web\Models;

use Illuminate\Database\Eloquent\Model;
use Seat\Eveapi\Models\Sde\InvType;
use Seat\Eveapi\Models\Sde\Moon;

/**
 * Class UniverseMoonReport.
 *
 * @package Seat\Web\Models
 */
class UniverseMoonReport extends Model
{
    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var string[]
     */
    protected $fillable = ['moon_id'];

    /**
     * @var string
     */
    protected $primaryKey = 'moon_id';

    /**
     * @var string
     */
    protected $table = 'universe_moon_reports';

    /**
     * @var \stdClass
     */
    private $moon_indicators;

    /**
     * @return \stdClass
     */
    public function getMoonIndicatorsAttribute()
    {
        if (is_null($this->moon_indicators)) {
            $this->moon_indicators = (object) [
                'ubiquitous' => $this->content->filter(function ($type) {
                    return $type->marketGroupID == Moon::UBIQUITOUS;
                })->count(),
                'common' => $this->content->filter(function ($type) {
                    return $type->marketGroupID == Moon::COMMON;
                })->count(),
                'uncommon' => $this->content->filter(function ($type) {
                    return $type->marketGroupID == Moon::UNCOMMON;
                })->count(),
                'rare' => $this->content->filter(function ($type) {
                    return $type->marketGroupID == Moon::RARE;
                })->count(),
                'exceptional' => $this->content->filter(function ($type) {
                    return $type->marketGroupID == Moon::EXCEPTIONAL;
                })->count(),
                'standard' => $this->content->filter(function ($type) {
                    return ! in_array($type->marketGroupID, [Moon::UBIQUITOUS, Moon::COMMON, Moon::UNCOMMON, Moon::RARE, Moon::EXCEPTIONAL]);
                })->count(),
            ];
        }

        return $this->moon_indicators ?: (object) [
            'ubiquitous' => 0,
            'common' => 0,
            'uncommon' => 0,
            'rare' => 0,
            'exceptional' => 0,
            'standard' => 0,
        ];
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUbiquitous($query)
    {
        return $query->whereHas('content', function ($sub_query) {
            $sub_query->where('marketGroupID', Moon::UBIQUITOUS);
        });
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCommon($query)
    {
        return $query->whereHas('content', function ($sub_query) {
            $sub_query->where('marketGroupID', Moon::COMMON);
        });
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUncommon($query)
    {
        return $query->whereHas('content', function ($sub_query) {
            $sub_query->where('marketGroupID', Moon::UNCOMMON);
        });
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRare($query)
    {
        return $query->whereHas('content', function ($sub_query) {
            $sub_query->where('marketGroupID', Moon::RARE);
        });
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExceptional($query)
    {
        return $query->whereHas('content', function ($sub_query) {
            $sub_query->where('marketGroupID', Moon::EXCEPTIONAL);
        });
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStandard($query)
    {
        return $query->whereHas('content', function ($sub_query) {
            $sub_query->whereNotIn('marketGroupID', [Moon::UBIQUITOUS, Moon::COMMON, Moon::UNCOMMON, Moon::RARE, Moon::EXCEPTIONAL]);
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function content()
    {
        return $this->belongsToMany(InvType::class, 'universe_moon_contents', 'moon_id', 'type_id')
            ->withPivot('rate');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function moon()
    {
        return $this->belongsTo(Moon::class, 'moon_id', 'moon_id')
            ->withDefault();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')
            ->withDefault();
    }
}
