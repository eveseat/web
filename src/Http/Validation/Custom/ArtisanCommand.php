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

namespace Seat\Web\Http\Validation\Custom;

use Artisan;
use Symfony\Component\Console\Input\ArgvInput;

/**
 * Class ArtisanCommand.
 *
 * @package Seat\Web\Http\Validation\Custom
 */
class ArtisanCommand
{
    /**
     * Validate if the $value is a valid artisan command.
     *
     * @param  $attribute
     * @param  $value
     * @param  $parameters
     * @param  $validator
     * @return bool
     */
    public static function validate($attribute, $value, $parameters, $validator)
    {
        //get all commands
        $allCommands = Artisan::all();

        //split arguments
        $argv = explode(' ', $value);

        //check if a command name is included and get it
        if(count($argv) === 0) return false;
        $commandName = array_shift($argv);

        //check if the command exists and get it
        if(! array_key_exists($commandName, $allCommands)) return false;
        $command = $allCommands[$commandName];

        //extract the argument definition off the command
        $argumentDefinition = $command->getDefinition();

        //validate our arguments
        try {
            //create an argument parser and supply it with the provided arguments.
            // Also supply the argument definition to check if it complies with the command
            // ArgvInput ignores the first element of the array(usually the executable name when calling it from the console), so just supply an empty string
            $input = new ArgvInput(['', ...$argv], $argumentDefinition);
            //I guess this validates the input?
            $input->validate();
        }
        // instead of returning false in ->validate(), it throws an exception
        catch (\RuntimeException $e){
            return false;
        }

        //all checks have passed, we can allow it
        return true;
    }
}
