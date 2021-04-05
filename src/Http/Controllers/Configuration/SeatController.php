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

namespace Seat\Web\Http\Controllers\Configuration;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Parsedown;
use Seat\Services\Traits\VersionsManagementTrait;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\Validation\Customlink;
use Seat\Web\Http\Validation\PackageChangelog;
use Seat\Web\Http\Validation\PackageVersionCheck;
use Seat\Web\Http\Validation\SeatSettings;

/**
 * Class SeatController.
 * @package Seat\Web\Http\Controllers\Configuration
 */
class SeatController extends Controller
{
    use VersionsManagementTrait;

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getView()
    {

        $packages = $this->getPluginsMetadataList();

        // Validate SSO Environment settings
        if (is_null(config('esi.eseye_client_id')) or
            is_null(config('esi.eseye_client_secret')) or
            is_null(config('esi.eseye_client_callback'))
        )
            $warn_sso = true;
        else
            $warn_sso = false;

        // Retrieve custom links
        $custom_links = setting('customlinks', true) ?: collect();

        return view('web::configuration.settings.view', compact('packages', 'warn_sso', 'custom_links'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAbout()
    {
        $discord_widget = $this->getDiscordWidget();
        $documentation_widget = $this->getDocumentationWidget();
        $github_widget = $this->getGithubWidget();

        return view('web::about.about', compact('discord_widget', 'documentation_widget', 'github_widget'));
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
        setting(['market_prices_region_id', $request->market_prices_region], true);
        setting(['allow_user_character_unlink', $request->allow_user_character_unlink], true);

        return redirect()->back()
            ->with('success', 'SeAT settings updated!');
    }

    /**
     * @param \Seat\Web\Http\Validation\Customlink $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Seat\Services\Exceptions\SettingException
     */
    public function postUpdateCustomLinks(Customlink $request)
    {

        // Retrieve the form data.
        $names = $request->input('customlink-name', []);
        $urls = $request->input('customlink-url', []);
        $icons = $request->input('customlink-icon', []);
        $new_tabs = $request->input('customlink-newtab', []);

        // Process the form data.
        $custom_links = collect();

        foreach($names as $key => $name) {
            $custom_links->push((object) [
                'name'    => $name,
                'url'     => $urls[$key],
                'icon'    => $icons[$key],
                'new_tab' => (bool) $new_tabs[$key],
            ]);
        }

        // Update the setting
        setting(['customlinks', $custom_links], true);

        return redirect()->back()
            ->with('success', 'Menu links updated!');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getApprovedSDE()
    {

        $sde_version = cache()->remember('live_sde_version', 720, function () {

            try {

                $sde_uri = 'https://raw.githubusercontent.com/eveseat/resources/master/sde.json';
                $response = (new Client())->request('GET', $sde_uri);

                // Ensure that the request was successful
                if (! $response->getStatusCode() == 200)
                    return 'Error fetching latest SDE version';

                $json_array = json_decode($response->getBody());

                return $json_array->version;

            } catch (RequestException $e) {

                return 'Error fetching latest SDE version';
            }

        });

        return response()->json(['version' => $sde_version]);
    }

    /**
     * Determine if a package is or not outdated.
     *
     * @param \Seat\Web\Http\Validation\PackageVersionCheck $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function postPackagesCheck(PackageVersionCheck $request)
    {
        $latest_version = $this->getPackageLatestVersion($request->input('vendor'), $request->input('package'));

        return response()->json([
            'error' => '',
            'outdated' => version_compare($request->input('version'), $latest_version) < 0,
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
     * @return object
     */
    private function getDiscordWidget()
    {
        $discord_widget = cache()->remember('discord_support_widget', 720, function () {

            $discord_widget = [
                'id' => '0',
                'name' => 'SeAT',
                'instant_invite' => '#',
                'presence_count' => 0,
            ];

            try {

                $discord_uri = 'https://discord.com/api/guilds/821361165791133716/widget.json';
                $response = (new Client())->request('GET', $discord_uri);

                // Ensure that the request was successful
                if ($response->getStatusCode() == 200) {
                    $json = json_decode($response->getBody());

                    $discord_widget['id'] = $json->id ?: 0;
                    $discord_widget['name'] = $json->name ?: 'SeAT';
                    $discord_widget['instant_invite'] = $json->instant_invite ?: '#';
                    $discord_widget['presence_count'] = $json->presence_count ?: 0;
                }

                return $discord_widget;

            } catch (Exception $e) {

                logger()->error($e->getMessage(), $e->getTrace());

                return $discord_widget;
            }

        });

        return (object) $discord_widget;
    }

    /**
     * @return object
     */
    private function getDocumentationWidget()
    {
        $docs_widget = cache()->remember('docs_seat_widget', 720, function () {

            $docs_widget = [
                'id' => '0',
                'name' => 'SeAT',
                'url' => 'https://docs.eveseat.net',
                'updated_at' => carbon('now'),
            ];

            try {

                $github_uri = 'https://api.github.com/repos/eveseat/docs';
                $response = (new Client())->request('GET', $github_uri);

                // Ensure that the request was successful
                if ($response->getStatusCode() == 200) {
                    $json = json_decode($response->getBody());

                    $docs_widget['id'] = $json->id ?: 0;
                    $docs_widget['name'] = $json->name ?: 'SeAT';
                    $docs_widget['updated_at'] = carbon($json->pushed_at ?: 'now');
                }

                return $docs_widget;

            } catch (Exception $e) {

                logger()->error($e->getMessage(), $e->getTrace());

                return $docs_widget;
            }

        });

        return (object) $docs_widget;
    }

    /**
     * @return object
     */
    private function getGithubWidget()
    {
        $github_widget = cache()->remember('github_seat_widget', 720, function () {

            $github_widget = [
                'id' => '0',
                'name' => 'SeAT',
                'url' => '#',
                'open_issues' => 0,
            ];

            try {

                $github_uri = 'https://api.github.com/repos/eveseat/seat';
                $response = (new Client())->request('GET', $github_uri);

                // Ensure that the request was successful
                if ($response->getStatusCode() == 200) {
                    $json = json_decode($response->getBody());

                    $github_widget['id'] = $json->id ?: 0;
                    $github_widget['name'] = $json->name ?: 'SeAT';
                    $github_widget['url'] = $json->html_url ?: '#';
                    $github_widget['open_issues'] = $json->open_issues_count ?: 0;
                }

                return $github_widget;

            } catch (Exception $e) {

                logger()->error($e->getMessage(), $e->getTrace());

                return $github_widget;
            }

        });

        return (object) $github_widget;
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
