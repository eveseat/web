<?php
/*
This file is part of SeAT

Copyright (C) 2015, 2016  Leon Jacobs

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/

namespace Seat\Web\Http\Controllers\Queue;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Seat\Services\Data\Queue;
use Seat\Services\Repositories\Queue\JobRepository;
use Seat\Web\Validation\Permission;

/**
 * Class QueueController
 * @package Seat\Web\Http\Controllers\Queue
 */
class QueueController extends Controller
{

    use Queue, JobRepository;

    /**
     * @return \Illuminate\View\View
     */
    public function getShortStatus()
    {

        return $this->count_summary();
    }

    /**
     * @return \Illuminate\View\View
     * @throws \Exception
     */
    public function getStatus()
    {

        $totals = $this->count_summary();

        $queued = $this->getJobs('Queued');
        $working = $this->getJobs('Working');
        $done = $this->getJobs('Done', 30);
        $error = $this->getJobs('Error', 30);

        return view('web::queue.status',
            compact('totals', 'queued', 'working', 'done', 'error'));
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getQueuedJobs()
    {
        return response()->json($this->getJobs('Queued'));
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWorkingJobs()
    {
        return response()->json($this->getJobs('Working'));
    }

    /**
     * @param $command_name
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getSubmitJob($command_name)
    {

        $accepted_commands = [
            'eve:queue-keys',
            'eve:update-api-call-list',
            'eve:update-eve',
            'eve:update-map',
            'eve:update-server-status'
        ];

        if (!in_array($command_name, $accepted_commands))
            abort(401);

        Artisan::call($command_name);

        return redirect()->back()
            ->with('success', 'The command ' . $command_name . ' has been run.');

    }

    /**
     * @return \Illuminate\View\View
     * @throws \Exception
     */
    public function getErrors()
    {

        $job_errors = $this->getJobs('Error');

        return view('web::queue.errors', compact('job_errors'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getClearErrors()
    {

        $this->clearErroredJobHistory();

        return redirect()->route('queue.status')
            ->with('success', 'All errored jobs have been deleted.');
    }

    /**
     * @param $job_id
     *
     * @return \Illuminate\View\View
     */
    public function getErrorDetail($job_id)
    {

        $job = $this->getJobById($job_id);

        return view('web::queue.error_detail', compact('job'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function getHistory()
    {

        $history = $this->getAllPaginatedJobs();

        return view('web::queue.history', compact('history'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getClearHistory()
    {

        $this->clearAllJobHistory();

        return redirect()->route('queue.status')
            ->with('success', 'All job history cleared.');
    }

}
