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

use Illuminate\Http\Request;
use Seat\Eveapi\Jobs\AbstractAuthCharacterJob;
use Seat\Eveapi\Models\RefreshToken;
use Seat\Web\Http\Controllers\Controller;

/**
 * Class JobController.
 * @package Seat\Web\Http\Controllers
 */
class JobController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDispatchUpdateJob(Request $request)
    {
        $request->validate([
            'character_id' => 'integer|required',
            'job_name' => sprintf('in:character.%s|required', implode(',character.', array_keys(config('web.jobnames.character')))),
        ]);

        $job_classes = collect(config('web.jobnames.' . $request->input('job_name')));

        // If we could not find the job to dispatch, log this as a
        // security event as someone might be trying something funny.
        if ($job_classes->isEmpty()) {

            $message = 'Failed to find the class for job_name ' . $request->input('job_name') .
                ' Someone might be trying something strange.';

            event('security.log', [$message, 'jobdispatching']);

            return response()->json([
                'message' => trans('web::seat.update_failed'),
            ], 400);
        }

        // Find the refresh token for the jobs.
        $refresh_token = RefreshToken::find($request->input('character_id'));

        // Dispatch jobs for each jobclass
        $job_classes->each(function ($job) use ($refresh_token, $request) {

            if (is_subclass_of($job, AbstractAuthCharacterJob::class)) {
                if (is_null($refresh_token)) {
                    return response()->json([
                        'message' => trans('web::seat.update_failed'),
                    ], 400);
                }

                (new $job($refresh_token))->dispatch($refresh_token)->onQueue('high');
            } else {
                (new $job($request->input('character_id')))->dispatch($request->input('character_id'))->onQueue('high');
            }

            logger()->info(sprintf('Manually dispatched job "%s" for character "%d"', $job,
                $request->input('character_id')));
        });

        // Redirect back!
        return response()->json([], 200);
    }
}
