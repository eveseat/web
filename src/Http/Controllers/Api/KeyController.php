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

namespace Seat\Web\Http\Controllers\Api;

use Illuminate\Http\Request;
use Pheal\Pheal;
use Seat\Eveapi\Helpers\JobPayloadContainer;
use Seat\Eveapi\Jobs\CheckAndQueueKey;
use Seat\Eveapi\Models\Eve\ApiKey as ApiKeyModel;
use Seat\Eveapi\Models\JobTracking;
use Seat\Eveapi\Traits\JobManager;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Models\User;
use Seat\Web\Validation\ApiKey;
use Seat\Web\Validation\Permission;
use Yajra\Datatables\Datatables;

/**
 * Class KeyController
 * @package Seat\Web\Http\Controllers\Api
 */
class KeyController extends Controller
{

    use JobManager;

    /**
     * @return \Illuminate\View\View
     */
    public function getAdd()
    {

        return view('web::api.add');
    }

    /**
     * @param \Seat\Web\Validation\ApiKey $request
     *
     * @return \Illuminate\View\View|string
     */
    public function checkKey(ApiKey $request)
    {

        $key_id = $request->input('key_id');
        $v_code = $request->input('v_code');

        try {

            // Pheal does not have getters/setters for
            // the keyid/vcode, sadly. So, we cant DI it.
            $result = (new Pheal($key_id, $v_code))
                ->accountScope->APIKeyInfo();

            $key_type = $result->key->type;
            $access_mask = $result->key->accessMask;
            $characters = $result->key->characters;

        } catch (\Exception $e) {

            return 'Bang! ' . $e->getMessage();

        }

        $access_map = ($key_type == 'Corporation' ?
            config('eveapi.access_bits.corp') : config('eveapi.access_bits.char'));

        return view('web::api.ajax.result',
            compact('key_type', 'access_mask', 'characters',
                'access_map', 'key_id', 'v_code'));
    }

    /**
     * @param \Seat\Web\Validation\ApiKey              $request
     * @param \Seat\Eveapi\Helpers\JobPayloadContainer $job
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addKey(ApiKey $request, JobPayloadContainer $job)
    {

        // Get or create the API Key
        $api_key = ApiKeyModel::firstOrNew([
            'key_id' => $request->input('key_id'),
        ]);

        // Set the current user as the owner of the key
        // and enable it.
        $api_key->fill([
            'v_code'  => $request->input('v_code'),
            'user_id' => auth()->user()->id,
            'enabled' => true,
        ]);

        $api_key->save();

        // For *some* reason, key_id is 0 here after the
        // fill() and save(). So, set it again so that
        // the update job wont fail to give Pheal a
        // key_id from the model.
        $api_key->key_id = $request->input('key_id');

        // Prepare the JobPayloadContainer for the update job
        $job->scope = 'Key';
        $job->api = 'Scheduler';
        $job->owner_id = $api_key->key_id;
        $job->eve_api_key = $api_key;

        // Queue the update Job
        $job_id = $this->addUniqueJob(
            CheckAndQueueKey::class, $job);

        return redirect()->route('api.key')
            ->with('success', trans('web::seat.add_success',
                ['jobid' => $job_id]));

    }

    /**
     * @return \Illuminate\View\View
     */
    public function listAll()
    {

        return view('web::api.list');
    }

    /**
     * @return mixed
     */
    public function listAllData()
    {

        $keys = ApiKeyModel::with('info');

        if (!auth()->user()->has('apikey.list', false))
            $keys = $keys
                ->where('user_id', auth()->user()->id);

        // Return data that datatables can understand
        return Datatables::of($keys)
            ->editColumn('info.expired', function ($column) {

                // Format dates for expired for sorting reasons
                return carbon($column->expires)->format('d/m/y');
            })
            ->addColumn('characters', function ($row) {

                // Include a view to show characters on a key
                return view('web::api.partial.character', compact('row'))
                    ->render();
            })
            ->addColumn('actions', function ($row) {

                // Detail & Delete buttons
                return view('web::api.partial.actions', compact('row'))
                    ->render();
            })
            ->filter(function ($query) {

                // Define the filter() method so on fields
                // where it makes sense. Unfortunately this had
                // to be done because of the way the characters
                // are incuded on a key.

                $query->whereHas('characters', function ($filter) {

                    $filter->where(
                        'characterName', 'like', '%' . request()->input('search')['value'] . '%');

                })->orWhereHas('info', function ($filter) {

                    $filter->where(
                        'type', 'like', '%' . request()->input('search')['value'] . '%');

                });
            })
            ->setRowClass(function ($row) {

                // Make disabled keys red.
                if (!$row->enabled)
                    return 'danger';
            })
            ->removeColumn('v_code')
            ->make(true);

    }

    /**
     * @param $key_id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getDelete($key_id)
    {

        ApiKeyModel::where('key_id', $key_id)
            ->delete();

        return redirect()->back()
            ->with('success', 'Key Successfully deleted');

    }

    /**
     * @param $key_id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getEnable($key_id)
    {

        $key = ApiKeyModel::findOrFail($key_id);

        $key->enabled = 1;
        $key->save();

        return redirect()->back()
            ->with('success', 'Key re-enabled');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getEnableAll()
    {

        $keys = ApiKeyModel::where('enabled', '<>', 1);

        if (!auth()->user()->has('apikey.list', false))
            $keys = $keys
                ->where('user_id', auth()->user()->id);

        // Enable the keys and clear the last error
        $keys->update([
            'enabled'    => 1,
            'last_error' => null,
        ]);

        return redirect()->back()
            ->with('success', 'Keys re-enabled');
    }

    /**
     * @param $api_key
     *
     * @return \Illuminate\View\View
     */
    public function getDetail($api_key)
    {

        $key = ApiKeyModel::with('info', 'characters', 'status')
            ->where('key_id', $api_key)
            ->firstOrFail();

        $access_map = null;

        if ($key->info)
            $access_map = ($key->info->type == 'Corporation' ?
                config('eveapi.access_bits.corp') : config('eveapi.access_bits.char'));

        $jobs = JobTracking::where('owner_id', $api_key)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('web::api.detail',
            compact('key', 'access_map', 'jobs'));
    }

    /**
     * @param \Seat\Eveapi\Helpers\JobPayloadContainer $job
     * @param                                          $api_key
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function queueUpdateJob(JobPayloadContainer $job, $api_key)
    {

        $key = ApiKeyModel::findOrFail($api_key);

        $job->scope = 'Key';
        $job->api = 'Scheduler';
        $job->owner_id = $key->key_id;
        $job->eve_api_key = $key;

        $job_id = $this->addUniqueJob(
            'Seat\Eveapi\Jobs\CheckAndQueueKey', $job);

        return redirect()->back()
            ->with('success', 'Update job ' . $job_id . ' Queued');
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param                          $key_id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function transfer(Request $request, $key_id)
    {

        $key = ApiKeyModel::findOrFail($key_id);
        $user = User::findOrFail($request->user_id);
        $key->user_id = $user->id;
        $key->save();

        return redirect()->back()
            ->with('success', 'Key successfully transferred to ' . $user->name);

    }

}
