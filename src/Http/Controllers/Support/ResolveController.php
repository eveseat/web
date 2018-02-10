<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015, 2016, 2017  Leon Jacobs
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

namespace Seat\Web\Http\Controllers\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Seat\Web\Http\Controllers\Controller;

/**
 * Class ResolveController.
 * @package Seat\Web\Http\Controllers\Support
 */
class ResolveController extends Controller
{
    /**
     * The prefix used for name_ids in the cache.
     *
     * @var string
     */
    protected $prefix = 'name_id';

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function resolveIdsToNames(Request $request)
    {

        $ids = array_unique(explode(',', $request->ids));
        $ids = collect($ids)->filter(function($value) {
            return is_numeric($value);
        })->toArray();

        // Init the initial return array
        $response = [];

        // Populate any entries from the cache
        foreach ($ids as $id) {

            if (Cache::has($this->prefix . $id)) {

                $response[$id] = Cache::get($this->prefix . $id);
                unset($ids[$id]);
            }
        }

        // Call the EVE API for any outstanding ids that need
        // resolution
        if (! empty($ids)) {

            $eseye = app('esi-client')->get();

            foreach (array_chunk($ids, 30) as $id_chunk) {

                $eseye->setVersion('v2');
                $eseye->setBody($id_chunk);
                $names = $eseye->invoke('post', '/universe/names/');

                foreach ($names as $result) {

                    Cache::forever(
                        $this->prefix . $result->id, $result->name);
                    $response[$result->id] = $result->name;
                }

            }
        }

        return response()->json($response);
    }
}
