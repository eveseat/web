<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2020 Leon Jacobs
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

namespace Seat\Web\Http\Validation\Custom;

use Cron\CronExpression;
use InvalidArgumentException;

/**
 * Class Cron.
 * @package Seat\Web\Http\Validation\Custom
 */
class Cron
{
    /**
     * Validate if the $value is a valid cron expression.
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @param $validator
     *
     * @return bool
     */
    public function validate($attribute, $value, $parameters, $validator)
    {

        // Try create a new CronExpression factory. If
        // this fails, we can assume the expression
        // itself is invalid/malformed.
        try {

            CronExpression::factory($value);

        } catch (InvalidArgumentException $e) {

            return false;

        }

        return true;
    }
}
