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

namespace Seat\Web\Http\Controllers\Configuration;

use Cache;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Parsedown;
use Seat\Services\AbstractSeatPlugin;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\Validation\PackageChangelog;
use Seat\Web\Http\Validation\PackageVersionCheck;
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
        if (is_null(config('eveapi.config.eseye_client_id')) or
            is_null(config('eveapi.config.eseye_client_secret')) or
            is_null(config('eveapi.config.eseye_client_callback'))
        )
            $warn_sso = true;
        else
            $warn_sso = false;

        return view('web::configuration.settings.view', compact('packages', 'warn_sso'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAbout()
    {
        return view('web::about');
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
    public function postPackagesCheck(PackageVersionCheck $request)
    {
        // construct the packagist uri to its API
        $packagist_url = sprintf('https://packagist.org/packages/%s/%s.json',
            $request->input('vendor'), $request->input('package'));

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
                'error' => 'The returned metadata was not properly structured or does not contain the package.versions property',
            ], 500);

        // extract published versions from packagist response
        $versions = $json_array['package']['versions'];

        foreach ($versions as $available_version => $metadata) {
            // ignore any untagged versions
            if (strpos($available_version, 'dev') !== false)
                continue;

            // return outdated on the first package which is greater than installed version
            if (version_compare($request->input('version'), $metadata['version']) < 0)
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
     * Return the changelog based on provided parameters.
     *
     * @return mixed|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function postPackagesChangelog(PackageChangelog $request)
    {
        $changelog_uri = $request->input('uri');
        $changelog_body = $request->input('body');
        $changelog_tag = $request->input('tag');

        if (! is_null($changelog_body) && ! is_null($changelog_tag))
            return $this->getChangelogFromApi($changelog_uri, $changelog_body, $changelog_tag);

        return $this->getChangelogFromFile($changelog_uri);
    }

    /**
     * Compute a list of provider class which are implementing SeAT package structure.
     *
     * @return \stdClass
     */
    private function getPluginsMetadataList(): stdClass
    {
        app()->loadDeferredProviders();
        $providers = array_keys(app()->getLoadedProviders());

        $packages = (object) [
            'core' => collect(),
            'plugins' => collect(),
        ];

        foreach ($providers as $class) {
            // attempt to retrieve the class from booted app
            $provider = app()->getProvider($class);

            if (is_null($provider))
                continue;

            // ensure the provider is a valid SeAT package
            if (! is_a($provider, AbstractSeatPlugin::class))
                continue;

            // seed proper collection according to package vendor
            $provider->getPackagistVendorName() === 'eveseat' ?
                $packages->core->push($provider) : $packages->plugins->push($provider);
        }

        return $packages;
    }

    /**
     * Return a rendered changelog based on the provided release API endpoint.
     *
     * @param string $uri
     * @param string $body_attribute
     * @param string $tag_attribute
     * @return string
     */
    private function getChangelogFromApi(string $uri, string $body_attribute, string $tag_attribute): string
    {
        try {
            return cache()->remember($this->getChangelogCacheKey($uri), 30, function () use ($uri, $body_attribute, $tag_attribute) {
                $changelog = '';

                // retrieve releases from provided API endpoint
                $client = new Client();
                $response = $client->request('GET', $uri);

                // decode the response
                $json_object = json_decode($response->getBody());

                // spawn a new Markdown parser
                $parser = new Parsedown();
                $parser->setSafeMode(true);

                // iterate over each release and build proper view
                foreach ($json_object as $release) {
                    $changelog .= view('web::configuration.settings.partials.packages.changelog.header', [
                        'version' => $release->{$tag_attribute},
                    ]);

                    $changelog .= view('web::configuration.settings.partials.packages.changelog.body', [
                        'body' => $parser->parse($release->{$body_attribute}),
                    ]);
                }

                // return a rendered release list
                return $changelog;
            });
        } catch (Exception $e) {
            logger()->error('An error occurred while fetching changelog from API.', [
                'code'       => $e->getCode(),
                'error'      => $e->getMessage(),
                'trace'      => $e->getTrace(),
                'uri'        => $uri,
                'attributes' => [
                    'body' => $body_attribute,
                    'tag'  => $tag_attribute,
                ],
            ]);
        }

        return '';
    }

    /**
     * Return parsed markdown from the file located at the provided URI.
     *
     * @param string $uri
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getChangelogFromFile(string $uri)
    {
        try {
            return cache()->remember($this->getChangelogCacheKey($uri), 30, function () use ($uri) {
                // retrieve changelog from provided uri
                $client = new Client();
                $response = $client->request('GET', $uri);

                // spawn a new Markdown parser
                $parser = new Parsedown();
                $parser->setSafeMode(true);

                // return the parsed changelog
                return $parser->parse($response->getBody());
            });
        } catch (Exception $e) {
            logger()->error('An error occurred while fetching changelog from file.', [
                'code'       => $e->getCode(),
                'error'      => $e->getMessage(),
                'trace'      => $e->getTrace(),
                'uri'        => $uri,
            ]);
        }

        return '';
    }

    /**
     * Determine a valid cache key for the provided URI.
     *
     * @param string $uri
     * @return string
     */
    private function getChangelogCacheKey(string $uri)
    {
        return sprintf('changelog.%s', str_replace('=', '', base64_encode($uri)));
    }
}
