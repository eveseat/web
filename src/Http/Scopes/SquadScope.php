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

namespace Seat\Web\Http\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Gate;

/**
 * Class SquadScope.
 *
 * @package Seat\Web\Http\Scopes
 */
class SquadScope implements Scope
{
    /**
     * {@inheritdoc}
     */
    public function apply(Builder $builder, Model $model)
    {
        // in order to avoid any null exception - we have to check if there is an active user
        if (! auth()->check())
            return $builder;

        // in case the current user is super user - we do not apply any constraint on the made query
        if (Gate::allows('global.superuser'))
            return $builder;

        // otherwise, we only show user the list of squads :
        //  - which are either of type manual or auto
        //  - from which he is a moderator
        //  - from which he is a member
        return $builder->whereIn('type', ['manual', 'auto'])
            ->orWhereHas('moderators', function (Builder $sub_query) {
                $sub_query->where('id', auth()->user()->id);
            })
            ->orWhereHas('members', function (Builder $sub_query) {
                $sub_query->where('id', auth()->user()->id);
            });
    }
}
