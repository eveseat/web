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
use Illuminate\Http\Request;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Services\Models\UserSetting;
use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Http\DataTables\Configuration\UsersDataTable;
use Seat\Web\Http\Validation\EditUser;
use Seat\Web\Http\Validation\ReassignCharacter;
use Seat\Web\Models\User;

/**
 * Class UserController.
 *
 * @package Seat\Web\Http\Controllers\Configuration
 */
class UserController extends Controller
{
    /**
     * @param  \Seat\Web\Http\DataTables\Configuration\UsersDataTable  $dataTable
     * @return mixed
     */
    public function index(UsersDataTable $dataTable)
    {
        return $dataTable->render('web::configuration.users.list');
    }

    /**
     * @param  int  $user_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(int $user_id)
    {

        $user = User::with('refresh_tokens', 'characters', 'roles.permissions')
            ->find($user_id);

        $login_history = $user->login_history()->orderBy('created_at', 'desc')->take(15)
            ->get();

        return view('web::configuration.users.edit',
            compact('user', 'login_history'));
    }

    /**
     * @param  \Seat\Web\Http\Validation\ReassignCharacter  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reassign(ReassignCharacter $request)
    {
        $character = CharacterInfo::with('user')->find($request->input('character'));
        $current_user = $character->user;
        $target_user = User::find($request->input('user'));

        $token = $character->refresh_token;

        // erase existing link between the character and current user
        $token->user_id = $target_user->id;

        // spawn a new link between the character and target user
        $character->refresh_token()->save($token);

        $message = sprintf('Character %s has been transferred from account %s to %s by %s',
            $character->name, $current_user->name, $target_user->name, auth()->user()->name);

        event('security.log', [$message, 'transfer']);

        return redirect()->back()
            ->with('success', sprintf('Character %s has been successfully transferred to account %s.',
                $character->name, $target_user->name));
    }

    /**
     * @param  \Seat\Web\Http\Validation\EditUser  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(EditUser $request)
    {

        // retrieve the group to which the edited user is attached.
        $user = User::find($request->input('user_id'));
        $user->admin = $request->input('admin', false);
        $user->save();

        // determine if the new e-mail address is already in use.
        $email_exists = UserSetting::where('name', 'email_address')
            ->where('value', sprintf('"%s"', $request->input('email')))
            ->where('user_id', '<>', $user->id)
            ->count() > 0;

        if ($email_exists)
            return redirect()->back()
                ->with('error', trans('email_in_use', ['mail' => $request->input('email')]));

        try {
            setting(['email_address', $request->input('email'), $user->id], false);
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }

        return redirect()->back()
            ->with('success', trans('web::seat.user_updated'));
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $user_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request, int $user_id)
    {

        if ($request->user()->id == $user_id)
            return redirect()->back()
                ->with('warning', trans('web::seat.self_delete_warning'));

        // Delete the user.
        User::findOrFail($user_id)->delete();

        return redirect()->back()
            ->with('success', trans('web::seat.user_deleted'));
    }

    /**
     * @param  int  $user_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editUserAccountStatus(int $user_id)
    {
        $user = User::findOrFail($user_id);

        $user->active = $user->active == false ? true : false;
        $user->save();

        return redirect()->back()
            ->with('success', trans('web::seat.account_status_change'));
    }

    /**
     * @param  int  $user_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function impersonate(int $user_id)
    {

        // Store the original user in the session
        session(['impersonation_origin' => auth()->user()]);

        // Get the user
        $user = User::findOrFail($user_id);

        // Log the impersonation event.
        event('security.log', [
            'Impersonating ' . $user->name, 'authentication',
        ]);

        // Login as the new user.
        auth()->login($user);

        return redirect()->route('home')
            ->with('success',
                trans('web::seat.impersonating', ['user' => $user->name]));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getStopImpersonate()
    {

        // If there is no user set in the session, abort!
        if (! session()->has('impersonation_origin'))
            abort(404);

        // Log the impersonation revert event.
        event('security.log', [
            'Reverting impersonation back to ' . session()->get('impersonation_origin')->name,
            'authentication',
        ]);

        // Login
        auth()->login(session('impersonation_origin'));

        // Clear the session value
        session()->forget('impersonation_origin');

        return redirect()->route('home')
            ->with('success', trans('web::seat.revert_impersonation'));

    }
}
