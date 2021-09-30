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

use Illuminate\Http\Request;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\Validation\CustomSignin;
use Seat\Web\Http\Validation\SsoScopes;

/**
 * Class SsoController.
 *
 * @package Seat\Web\Http\Controllers\Configuration
 */
class SsoController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getConfigurationHome(Request $request)
    {

        $sso_scopes = collect(setting('sso_scopes', true));

        $selected_profile = $sso_scopes->first(function ($item) {
            return $item->default == true;
        });

        if(! is_null($request->input('profile', null))) {
            $selected_profile = $sso_scopes->first(function ($item) use ($request) {
                return $item->id == $request->input('profile');
            });
        }

        $custom_signin_message = setting('custom_signin_message', true);

        return view('web::configuration.sso.view', compact('selected_profile', 'custom_signin_message'));
    }

    /**
     * @param  \Seat\Web\Http\Validation\SsoScopes  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Seat\Services\Exceptions\SettingException
     */
    public function postUpdateScopes(SsoScopes $request)
    {

        $scopes = collect(setting('sso_scopes', true));

        $scopes->transform(function ($item, $key) use ($request) {
            if($item->id == $request->input('profile_id')) {
                $item->name = $request->input('profile_name');
                $item->scopes = $request->input('scopes', ['publicData']);
            }

            return $item;
        });

        setting(['sso_scopes', $scopes], true);

        return redirect()->back()
            ->with('success',
                sprintf('SSO Profile %s has been successfully updated.', $request->input('profile_name')));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Seat\Services\Exceptions\SettingException
     */
    public function getSetDefaultProfile(int $id)
    {

        $scopes = collect(setting('sso_scopes', true));

        // Given id must exist
        $exists = $scopes->search(function ($item) use ($id) {
            return $item->id == $id;
        });

        if($exists === false)
            return redirect()->back()->with('error', 'Given id does not exist.');

        $scopes->transform(function ($item, $key) use ($id) {
            if($item->id == $id) {
                $item->default = true;
            } else {
                $item->default = false;
            }

            return $item;
        });

        setting(['sso_scopes', $scopes], true);

        return redirect()->back()->with('success', trans('web::seat.updated'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Seat\Services\Exceptions\SettingException
     */
    public function getAddProfile()
    {

        // Get new id
        $scopes = collect(setting('sso_scopes', true));
        $newid = $scopes->pluck('id')->max() + 1;

        $scopes->push((object) [
            'id' => $newid,
            'name' => 'new-profile-' . $newid,
            'scopes' => [],
            'default' => false,
        ]);

        setting(['sso_scopes', $scopes], true);

        return redirect()->back()->with('success', trans('web::seat.updated'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Seat\Services\Exceptions\SettingException
     */
    public function getDeleteProfile(int $id)
    {

        $scopes = collect(setting('sso_scopes', true));

        // default profile cannot be removed
        $default_profile = $scopes->first(function ($item) {
            return $item->default == true;
        });

        if($default_profile->id == $id)
            return redirect()->back()->with('error', 'Cannot remove default profile.');

        $deleteKey = $scopes->search(function ($item, $key) use ($id) {
            return $item->id == $id;
        });

        $scopes = $scopes->except($deleteKey);

        setting(['sso_scopes', $scopes], true);

        return redirect()->back()->with('success', trans('web::seat.updated'));
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Seat\Services\Exceptions\SettingException
     */
    public function postUpdateCustomSignin(CustomSignin $request)
    {

        $custom_signin_message = $request->input('message', '');

        setting(['custom_signin_message', $custom_signin_message], true);

        return redirect()->back()->with('success', trans('web::seat.updated'));
    }
}
