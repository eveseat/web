<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to present Leon Jacobs
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

namespace Seat\Web\Http\Composers;

use Illuminate\View\View;

class CharacterFilter
{
    public function compose(View $view)
    {
        $rules = config('web.characterfilter');

        // work with raw arrays since the filter code requires an array of objects, and laravel collections don't like to give us that
        $newrules = [];
        foreach ($rules as $rule) {
            // convert route names to urls, but keep arrays with hardcoded options
            if(is_string($rule['src'])){
                $rule['src'] = route($rule['src']);
            }
            $newrules[] = (object) $rule;
        }

        $view->with('characterFilterRules', $newrules);
    }
}
