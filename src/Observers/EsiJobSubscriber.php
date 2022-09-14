<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2022 Leon Jacobs
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

namespace Seat\Web\Observers;

use Seat\Eveapi\Events\EsiJobCompleted;
use Seat\Eveapi\Events\EsiJobFailed;
use Seat\Eveapi\Events\EsiJobQueued;
use Seat\Eveapi\Events\ProcessingEsiJob;
use Seat\Web\Models\JobsTracking;

/**
 * Class EsiJobSubscriber.
 * @package Seat\Web\Observers
 */
class EsiJobSubscriber
{
    /**
     * @param $event
     * @return void
     */
    public function handleEsiJobQueued($event): void
    {
        $tracking = JobsTracking::firstOrNew(
            ['entity_id' => $event->entity_id, 'job_class' => $event->job_class],
            ['job_scope' => $event->scope]
        );

        $tracking->job_display_name = $event->job_display_name;
        $tracking->status = 'queued';
        $tracking->save();
    }

    /**
     * @param $event
     * @return void
     */
    public function handleProcessingEsiJob($event): void
    {
        $tracking = JobsTracking::firstOrNew(
            ['entity_id' => $event->entity_id, 'job_class' => $event->job_class],
            ['job_scope' => $event->scope]
        );

        $tracking->job_display_name = $event->job_display_name;
        $tracking->status = 'running';
        $tracking->save();
    }

    /**
     * @param $event
     * @return void
     */
    public function handleEsiJobCompleted($event): void
    {
        $tracking = JobsTracking::firstOrNew(
            ['entity_id' => $event->entity_id, 'job_class' => $event->job_class],
            ['job_scope' => $event->scope]
        );

        $tracking->job_display_name = $event->job_display_name;
        $tracking->status = 'completed';
        $tracking->save();
    }

    /**
     * @param $event
     * @return void
     */
    public function handleEsiJobFailed($event): void
    {
        $tracking = JobsTracking::firstOrNew(
            ['entity_id' => $event->entity_id, 'job_class' => $event->job_class],
            ['job_scope' => $event->scope]
        );

        $tracking->job_display_name = $event->job_display_name;
        $tracking->status = 'failed';
        $tracking->save();
    }

    /**
     * @param $events
     * @return string[]
     */
    public function subscribe($events): array
    {
        return [
            EsiJobQueued::class => 'handleEsiJobQueued',
            ProcessingEsiJob::class => 'handleProcessingEsiJob',
            EsiJobCompleted::class => 'handleEsiJobCompleted',
            EsiJobFailed::class => 'handleEsiJobFailed',
        ];
    }
}
