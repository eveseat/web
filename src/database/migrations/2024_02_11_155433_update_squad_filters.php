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

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Seat\Services\Facades\DeferredMigration;
use Seat\Web\Models\Squads\Squad;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('squads')->select('id', 'filters')->chunkById(50, function ($squads) {
            foreach ($squads as $squad) {
                $this->updateSquad($squad, function ($path) {
                    // refresh tokens are a special case, since they don't go over character.
                    // However, they also work over character when a plural s is removed
                    if($path === 'refresh_tokens') return 'refresh_token';

                    $parts = explode('.', $path);

                    if($parts[0] !== 'characters') {
                        throw new Exception('Cannot migrate squad filter: filter path doesn\'t go over character');
                    }

                    return implode('.', array_slice($parts, 1));
                });
            }
        });

        // since the squad filter change with this migration, we have to recompute the eligibility of everyone
        DeferredMigration::schedule(function () {
            Squad::recomputeAllSquadMemberships();
        });
    }

    private function updateSquad($squad, $callback) {
        $filter = json_decode($squad->filters);
        $this->updateFilter($filter, $callback);
        DB::table('squads')
            ->where('id', $squad->id)
            ->update([
                'filters' => json_encode($filter),
            ]);
    }

    private function updateFilter($filter, $callback) {
        if(property_exists($filter, 'and')){
            foreach ($filter->and as $rule) {
                $this->updateFilter($rule, $callback);
            }
        } elseif (property_exists($filter, 'or')){
            foreach ($filter->or as $rule) {
                $this->updateFilter($rule, $callback);
            }
        } elseif (property_exists($filter, 'path')){
            $filter->path = $callback($filter->path);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('squads')->select('id', 'filters')->chunkById(50, function ($squads) {
            foreach ($squads as $squad) {
                $this->updateSquad($squad, function ($path) {
                    if($path === '') return 'characters';

                    // we deliberately ignore the refresh_tokens special case since keeping it over character fixes a security issue
                    return sprintf('characters.%s', $path);
                });
            }
        });
    }
};
