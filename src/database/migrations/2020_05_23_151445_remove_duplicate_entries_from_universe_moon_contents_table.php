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

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Class RemoveDuplicateEntriesFromUniverseMoonContentsTable.
 */
class RemoveDuplicateEntriesFromUniverseMoonContentsTable extends Migration
{
    public function up()
    {
        // remove any duplicated entry base on moon_id and type_id
        DB::statement('DELETE a FROM universe_moon_contents a INNER JOIN universe_moon_contents b WHERE a.id < b.id AND a.moon_id = b.moon_id AND a.type_id = b.type_id');
    }

    public function down()
    {

    }
}
