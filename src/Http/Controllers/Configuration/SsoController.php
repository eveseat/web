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

use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\Validation\SsoScopes;

/**
 * Class SsoController.
 * @package Seat\Web\Http\Controllers\Configuration
 */
class SsoController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getConfigurationHome()
    {

        return view('web::configuration.sso.view');
    }

    /**
     * @param \Seat\Web\Http\Validation\SsoScopes $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Seat\Services\Exceptions\SettingException
     */
    public function postUpdateScopes(SsoScopes $request)
    {

        setting(['sso_scopes', $request->input('scopes')], true);

        return redirect()->back()->with('success', trans('web::seat.updated'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Seat\Services\Exceptions\SettingException
     */
    public function getEnableAll()
    {

        setting(['sso_scopes', config('eveapi.scopes')], true);

        return redirect()->back()->with('success', trans('web::seat.updated'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Seat\Services\Exceptions\SettingException
     */
    public function getRemoveAll()
    {

        setting(['sso_scopes', []], true);

        return redirect()->back()->with('success', trans('web::seat.updated'));
    }
}
