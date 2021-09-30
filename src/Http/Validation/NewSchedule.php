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

namespace Seat\Web\Http\Validation;

use Artisan;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class NewSchedule.
 *
 * @package Seat\Web\Http\Validation
 */
class NewSchedule extends FormRequest
{
    /**
     * Authorize the request by default.
     *
     * @return bool
     */
    public function authorize()
    {

        return true;
    }

    /**
     * The error messages.
     * TODO: Make locale aware.
     *
     * @return array
     */
    public function messages()
    {

        return [
            'cron' => 'Invalid cron expression.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * I have to admit, the Laravel docs is super shit when it
     * comes to the unique condition with a where clause.
     * After reading a number of crap posts online, I think I
     * finally figured it out.
     * Lies! **I have no idea how it  works**, but for sanity
     * sake, here is the query that gets generated for the
     * 'expression' unique with where validation:
     *
     * select count(*) as aggregate from `schedules` where
     *  `expression` = "cron_expression" and `command`
     *  = "command-name"
     *
     * Lolwut...
     *
     * @return array
     */
    public function rules()
    {

        $available_commands = implode(',', array_keys(Artisan::all()));

        return [

            'command'    => 'required|in:' . $available_commands,
            'expression' => 'required|cron|unique:schedules,expression,NULL,NULL,command,' .
                $this->request->get('command'),
        ];
    }
}
