<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015, 2016, 2017, 2018  Leon Jacobs
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

use Cache;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use ReflectionClass;
use ReflectionException;
use Seat\Services\AbstractSeatPlugin;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\Validation\SeatSettings;
use stdClass;

/**
 * Class SeatController.
 * @package Seat\Web\Http\Controllers\Configuration
 */
class SeatController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getView()
    {

        $packages = $this->getPluginsMetadataList();

        // Validate SSO Environment settings
        if (is_null(env('EVE_CLIENT_ID')) or
            is_null(env('EVE_CLIENT_SECRET')) or
            is_null(env('EVE_CALLBACK_URL'))
        )
            $warn_sso = true;
        else
            $warn_sso = false;

        return view('web::configuration.settings.view', compact('packages', 'warn_sso'));
    }

    /**
     * @param \Seat\Web\Http\Validation\SeatSettings $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Seat\Services\Exceptions\SettingException
     */
    public function postUpdateSettings(SeatSettings $request)
    {

        setting(['registration', $request->registration], true);
        setting(['admin_contact', $request->admin_contact], true);
        setting(['allow_tracking', $request->allow_tracking], true);
        setting(['cleanup_data', $request->cleanup_data], true);

        // If the queue workers number has changed, kick off the horizon
        // temrinate command to restart the workers.
        if (setting('queue_workers', true) !== $request->queue_workers)
            session()->flash('info', trans('web::seat.horizon_restart'));

        setting(['queue_workers', $request->queue_workers], true);

        return redirect()->back()
            ->with('success', 'SeAT settings updated!');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getApprovedSDE()
    {

        $sde_version = Cache::remember('live_sde_version', 720, function () {

            try {

                $sde_uri = 'https://raw.githubusercontent.com/eveseat/resources/master/sde.json';
                $response = (new Client())->request('GET', $sde_uri);

                // Ensure that the request was successful
                if (! $response->getStatusCode() == 200)
                    return 'Error fetching latest SDE version';

                $json_array = json_decode($response->getBody());

                return str_replace('-', '--', $json_array->version);

            } catch (RequestException $e) {

                return 'Error fetching latest SDE version';
            }

        });

        return response()->json(['version' => $sde_version]);
    }

    /**
     * Determine if a package is or not outdated.
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function postCheckPackage()
    {
        // ensure the request is containing required information (vendor, package, version) in order to build a query
        $this->validate(request(), [
            'vendor' => 'required|string',
            'package' => 'required|string',
            'version' => 'required|string',
        ]);

        // construct the packagist uri to its API
        $packagist_url = sprintf('https://packagist.org/packages/%s/%s.json',
            request()->input('vendor'), request()->input('package'));

        // retrieve package meta-data
        $response = (new Client())->request('GET', $packagist_url);

        if ($response->getStatusCode() !== 200)
            return response()->json([
                'error' => 'An error occurred while attempting to retrieve the package version.',
            ], 500);

        // convert the body into an array
        $json_array = json_decode($response->getBody(), true);

        // in case we miss either versions or package attribute, return an error as those attribute should contains version information
        if (! array_key_exists('package', $json_array) || ! array_key_exists('versions', $json_array['package']))
            return response()->json([
                'error' => 'The returned metadata are miss-structured or does not contains package.versions property',
            ], 500);

        // extract published versions from packagist response
        $versions = $json_array['package']['versions'];

        foreach ($versions as $available_version => $metadata) {
            // ignore any untagged versions
            if (strpos($available_version, 'dev') !== false)
                continue;

            // return outdated on the first package which is greater than installed version
            if (version_compare(request()->input('version'), $metadata['version']) < 0)
                return response()->json([
                    'error' => '',
                    'outdated' => true,
                ]);
        }

        // return up-to-date only once we loop over each available versions
        return response()->json([
            'error' => '',
            'outdated' => false,
        ]);
    }

    /**
     * @return \stdClass
     */
    private function getPluginsMetadataList(): stdClass
    {
        $classes = get_declared_classes();
        $packages = collect();

        try {
            foreach ($classes as $class) {
                $meta = new ReflectionClass($class);
                if ($meta->isSubclassOf(AbstractSeatPlugin::class) && !$meta->isAbstract())
                    $packages->push($class);
            }
        } catch (ReflectionException $e) {

        }

        return (object)[
            'core' => $packages->filter(function ($class) {
                return call_user_func([$class, 'getPackagistVendorName']) === 'eveseat';
            }),
            'plugins' => $packages->filter(function ($class) {
                return call_user_func([$class, 'getPackagistVendorName']) !== 'eveseat';
            }),
        ];
    }
}
