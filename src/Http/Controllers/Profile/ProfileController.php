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
use Seat\Services\Repositories\Character\CharacterRepository;
use Seat\Services\Repositories\Configuration\UserRespository;
use Seat\Services\Settings\Profile;
use Seat\Services\Settings\UserSettings;
use Seat\Web\Validation\PasswordUpdate;
use Seat\Web\Validation\EmailUpdate;
use Seat\Web\Validation\ProfileSettings;

/**
 * Class ProfileController
 * @package Seat\Web\Http\Controllers\Profile
 */
class ProfileController extends Controller
{

    use UserRespository, CharacterRepository;

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getView()
    {

        $user = $this->getFullUser(auth()->user()->id);
        $history = auth()->user()->login_history
            ->take(50)->sortByDesc('created_at');

        // Settings value possibilities
        $characters = $this->getUserCharacters(auth()->user()->id);
        $skins = Profile::$options['skins'];
        $languages = config('web.config.languages');
        $sidebar = Profile::$options['sidebar'];

        $thousand = Profile::$options['thousand_seperator'];
        $decimal = Profile::$options['decimal_seperator'];

        return view('web::profile.view',
            compact('user', 'history', 'characters', 'skins', 'languages',
                'sidebar', 'thousand', 'decimal'));
    }

    /**
     * @param \Seat\Web\Validation\ProfileSettings $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getUpdateUserSettings(ProfileSettings $request)
    {

        // If the multifactor authentication is being disabled,
        // clear out the token we have on record too.
        if ($request->require_mfa == "no" && Profile::get('require_mfa') == "yes") {

            auth()->user()->update(['mfa_token' => null]);
        }

        // Update the settings
        Profile::set('main_character_id', $request->main_character_id);
        Profile::set('main_character_name', $this->getCharacterNameById(
            $request->main_character_id));
        Profile::set('skin', $request->skin);
        Profile::set('language', $request->language);
        Profile::set('sidebar', $request->sidebar);

        Profile::set('thousand_seperator', $request->thousand_seperator);
        Profile::set('decimal_seperator', $request->decimal_seperator);

        Profile::set('email_notifications', $request->email_notifications);

        Profile::set('require_mfa', $request->require_mfa);

        return redirect()->back()
            ->with('success', 'Profile settings updated!');

    }

    /**
     * @param \Seat\Web\Validation\PasswordUpdate $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postUpdatePassword(PasswordUpdate $request)
    {

        if (!auth()->validate([
            'name'     => auth()->user()->name,
            'password' => $request->current_password])
        )
            return redirect()->back()
                ->with('error',
                    'Your current password is incorrect, please try again.');

        $user = auth()->user();
        $user->password = bcrypt($request->new_password);
        $user->save();

        return redirect()->back()
            ->with('success', 'Password updated!');
    }
    
    public function postUpdateEmail(EmailUpdate $request)
    {
        $user = auth()->user();
        $user->email = $request->new_email;
        $user->save();
        
        return redirect()->back()
            ->with('success', 'Email updated!');
    }

}
