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

namespace Seat\Web\Http\Controllers\Tools;

use Seat\Eveapi\Models\RefreshToken;
use Seat\Web\Http\Controllers\Controller;

/**
 * Class JobController.
 * @package Seat\Web\Http\Controllers
 */
class JobController extends Controller
{
    /**
     * @param int    $character_id
     * @param string $job_name
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getDispatchUpdateJob(int $character_id, string $job_name)
    {

        $job_classes = collect(config('web.jobnames.' . $job_name));

        // If we could not find the jon to dispatch, log this as a
        // security event as someone might be trying something funny.
        if ($job_classes->isEmpty()) {

            $message = 'Failed to find the jobclass for job_name ' . $job_name .
                ' Someone might be trying something strange.';

            event('security.log', [$message, 'jobdispatching']);

            return redirect()->back()->with('warning', trans('web::seat.update_failed'));
        }

        // Find the refresh token for the jobs.
        $refresh_token = RefreshToken::findOrFail($character_id);

        // Dispatch jobs for each jobclass
        $job_classes->each(function ($job) use ($refresh_token, $character_id) {

            (new $job($refresh_token))->dispatch($refresh_token)->onQueue('high');

            logger()->info('Manually dispatched job \'' . $job . '\' for character ' .
                $character_id);
        });

        // Redirect back!
        return redirect()->back()->with('success', trans('web::seat.update_dispatched'));
    }
}
