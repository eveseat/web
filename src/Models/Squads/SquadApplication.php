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

use Illuminate\Database\Eloquent\Model;
use Seat\Web\Models\User;

/**
 * Class SquadApplication.
 *
 * @package Seat\Web\Models\Squads
 */
class SquadApplication extends Model
{
    /**
     * @var string
     */
    protected $primaryKey = 'application_id';

    /**
     * @var array
     */
    protected $fillable = ['message'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function squad()
    {
        return $this->belongsTo(Squad::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class)
            ->withDefault([
                'name' => trans('web::seat.unknown'),
            ]);
    }
}
