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

namespace Seat\Web\Http\Controllers\Tools;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Seat\Eveapi\Jobs\AbstractAuthCharacterJob;
use Seat\Eveapi\Jobs\AbstractAuthCorporationJob;
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
     * @deprecated will be replaced by postDispatchJob
     */
    public function getDispatchUpdateJob(Request $request)
    {
        return $this->postDispatchJob($request);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function postDispatchJob(Request $request)
    {
        $request->validate([
            'type' => 'in:character,corporation|required',
            'entity_id' => 'integer|required',
            'job_name' => sprintf('in:%s.%s|required',
                $request->input('type'),
                implode(',' . $request->input('type') . '.', array_keys(config('web.jobnames.' . $request->input('type'), [])))
            ),
        ]);

        $job_classes = collect(config(sprintf('web.jobnames.%s', $request->input('job_name'))));

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

        // Dispatch jobs for each class
        return $this->handleJobDispatch($job_classes, $request);
    }

    /**
     * @param \Illuminate\Support\Collection $job_classes
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private function handleJobDispatch(Collection $job_classes, Request $request)
    {
        $job_classes->each(function ($class) use ($request) {

            switch (true) {
                // process character job
                case is_subclass_of($class, AbstractAuthCharacterJob::class):
                    if (! $this->handleCharacterJobsDispatch($class, $request->input('entity_id')))
                        return response()->json([
                            'message' => 'Unable to retrieve a suitable token.',
                        ], 400);
                    break;
                // process corporation job
                case is_subclass_of($class, AbstractAuthCorporationJob::class):
                    if (! $this->handleCorporationJobsDispatch($class, $request->input('entity_id')))
                        return response()->json([
                            'message' => 'Unable to retrieve a suitable token.',
                        ], 400);
                    break;
                // process public job
                default:
                    $class::dispatch($request->input('entity_id'))->onQueue('high');
            }

            logger()->info(sprintf('Manually dispatched job "%s" for %s "%d"', $class,
                $request->input('type'), $request->input('entity_id')));
        });

        // Redirect back!
        return response()->json([], 200);
    }

    /**
     * @param string $class
     * @param int $character_id
     *
     * @return bool
     */
    private function handleCharacterJobsDispatch(string $class, int $character_id)
    {
        $refresh_token = RefreshToken::find($character_id);

        if (! $refresh_token)
            return false;

        $class::dispatch($refresh_token)->onQueue('high');

        return true;
    }

    /**
     * @param string $class
     * @param int $corporation_id
     *
     * @return false
     */
    private function handleCorporationJobsDispatch(string $class, int $corporation_id)
    {
        // generate a dummy job - so we can pickup the required roles
        $dummy_job = new $class(0, new RefreshToken());

        $refresh_token = RefreshToken::whereHas('character.affiliation', function ($query) use ($corporation_id) {
            $query->where('corporation_id', $corporation_id);
        })->when(! empty($dummy_job->getRoles()), function ($sub_query) use ($dummy_job) {
            $sub_query->whereHas('character.corporation_roles', function ($query) use ($dummy_job) {
                $query->where('scope', 'roles');
                $query->whereIn('role', $dummy_job->getRoles());
            });
        })->first();

        if (! $refresh_token)
            return false;

        $class::dispatch($refresh_token->affiliation->corporation_id, $refresh_token)->onQueue('high');

        return true;
    }
}
