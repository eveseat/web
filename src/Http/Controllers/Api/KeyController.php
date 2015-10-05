<?php
/*
This file is part of SeAT

Copyright (C) 2015  Leon Jacobs

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

use App\Http\Controllers\Controller;
use Pheal\Pheal;
use Seat\Eveapi\Models\Eve\ApiKey as ApiKeyModel;
use Seat\Web\Validation\ApiKey;
use Seat\Web\Validation\Permission;

/**
 * Class KeyController
 * @package Seat\Web\Http\Controllers\Api
 */
class KeyController extends Controller
{

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
     * @param \Seat\Web\Validation\ApiKey $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addKey(ApiKey $request)
    {

        ApiKeyModel::create([
            'key_id'  => $request->input('key_id'),
            'v_code'  => $request->input('v_code'),
            'user_id' => auth()->user()->id,
            'enabled' => true,
        ]);

        return redirect()->route('api.key')
            ->with('success', trans('web::api.add_success'));

    }

    /**
     * @return \Illuminate\View\View
     */
    public function listAll()
    {

        $keys = ApiKeyModel::with('info', 'characters');

        if (!auth()->user()->hasSuperUser())
            $keys = $keys
                ->where('user_id', auth()->user()->id);

        $keys = $keys->get();

        return view('web::api.list', compact('keys'));
    }

    /**
     * @param $key_id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($key_id)
    {

        if (auth()->user()->hasSuperUser() || auth()->user()->has('api_key_delete', false))
            ApiKeyModel::where('key_id', $key_id)
                ->delete();
        else
            ApiKeyModel::where('user_id', auth()->user()->id)
                ->where('key_id', $key_id)
                ->delete();

        return redirect()->back()
            ->with('success', 'Key Successfully deleted');

    }

}
