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

namespace Seat\Web\Http\Controllers\Configuration;

use Artisan;
use Illuminate\Http\Request;
use Seat\Eveapi\Models\RefreshToken;
use Seat\Services\Models\Schedule;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\Validation\NewSchedule;
use Seat\Web\Models\CharacterSchedulingRule;

/**
 * Class ScheduleController.
 *
 * @package Seat\Web\Http\Controllers\Configuration
 */
class ScheduleController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function listSchedule()
    {

        $schedule = Schedule::all();
        $commands = Artisan::all();
        $expressions = [
            'hourly' => '0 * * * *',
            'daily' => '0 0 * * *',
            'weekly' => '0 0 * * 0',
            'monthly' => '0 0 1 * *',
            'yearly' => '0 0 1 1 *',
            'every minute' => '* * * * *',
            'every five minutes' => '*/5 * * * *',
            'every ten minutes' => '*/10 * * * *',
            'every thirty minutes' => '*/30 * * * *',
        ];

        $scheduling_rules = CharacterSchedulingRule::all();

        return view('web::configuration.schedule.view',
            compact('schedule', 'commands', 'expressions', 'scheduling_rules'));
    }

    /**
     * @param  \Seat\Web\Http\Validation\NewSchedule  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function newSchedule(NewSchedule $request)
    {

        Schedule::create($request->all());

        return redirect()->back()
            ->with('success', 'New Schedule has been added!');

    }

    /**
     * @param  int  $schedule_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteSchedule(int $schedule_id)
    {

        Schedule::findOrFail($schedule_id)->delete();

        return redirect()->back()
            ->with('success', 'Schedule entry deleted!');
    }

    public function createSchedulingRule(Request $request)
    {
        $request->validate([
            'filters' => 'required|json',
            'name' => 'required|string',
            'time' => 'required|numeric',
            'timeunit' => 'required|in:hour,day,week',
        ]);

        // $time_modifier: conversion factor from timeunit to seconds
        if($request->timeunit === 'hour') {
            $time_modifier = 60 * 60;
        } elseif ($request->timeunit === 'day') {
            $time_modifier = 60 * 60 * 24;
        } elseif ($request->timeunit === 'week') {
            $time_modifier = 60 * 60 * 24 * 7;
        }
        $time = $request->time * $time_modifier;

        $rule = CharacterSchedulingRule::where('name', $request->name)->first();
        if($rule === null) {
            $rule = new CharacterSchedulingRule();
            $rule->name = $request->name;
        }
        $rule->interval = $time;
        $rule->filter = $request->filters;
        $rule->save();

        // chunk this to avoid out-of-memory issues on large installs
        RefreshToken::chunk(200, function ($tokens) {
            foreach ($tokens as $token) {
                CharacterSchedulingRule::updateRefreshTokenSchedule($token);
            }
        });

        return redirect()->back()
            ->with('success', 'Character Scheduling Rule added!');
    }

    public function deleteSchedulingRule(Request $request)
    {
        $request->validate([
            'rule_id' => 'required|numeric',
        ]);

        CharacterSchedulingRule::destroy($request->rule_id);

        // chunk this to avoid out-of-memory issues on large installs
        RefreshToken::chunk(200, function ($tokens) {
            foreach ($tokens as $token) {
                CharacterSchedulingRule::updateRefreshTokenSchedule($token);
            }
        });

        return redirect()->back()->with('success', 'Successfully removed character scheduling rule!');
    }
}
