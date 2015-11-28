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

namespace Seat\Web\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Seat\Services\Repositories\Configuration\UserRespository;
use Seat\Services\Settings\Profile;
use Seat\Services\Settings\UserSettings;
use Seat\Web\Validation\ProfileSettings;

/**
 * Class ProfileController
 * @package Seat\Web\Http\Controllers\Profile
 */
class ProfileController extends Controller
{

    use UserRespository;

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getView()
    {

        $user = $this->getFullUser(auth()->user()->id);
        $history = auth()->user()->login_history->take(50);

        return view('web::profile.view', compact('user', 'history'));
    }

    /**
     * @param \Seat\Web\Validation\ProfileSettings $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getUpdateUserSettings(ProfileSettings $request)
    {

        // Update the settings
        Profile::set('skin', $request->skin);

        return redirect()->back()
            ->with('success', 'Profile settings updated!');

    }

}
