<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2022 Leon Jacobs
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

namespace Seat\Web\Http\Controllers\Auth;

use Seat\Web\Http\Controllers\Controller;
use Seat\Web\Models\User;
use Seat\Web\Models\UserSharelink;

/**
 * Class SharelinkController.
 *
 * @package Seat\Web\Http\Controllers\Auth
 */
class SharelinkController extends Controller
{
    /**
     * @param  string  $token
     * @return \Illuminate\Http\RedirectResponse
     */
    public function checkLoginToken(string $token)
    {
        // Fetch token from DB, if we can.
        $token = UserSharelink::find($token);

        // Is this a valid sharing link?
        if(! $token)
            abort(403);

        // Is token still valid?
        if($token->expires_on->lessThan(now()))
            return redirect()->back()
                ->with('error', 'Token has expired.');

        // Log the sharelink activation
        $message = sprintf('Share link activated for user %s to %s by %s',
            $token->user->name, $token->character_id == 0 ? 'all characters' : $token->character->name, auth()->user()->name);

        event('security.log', [$message, 'sharelink']);

        // Add this as user_sharing to the current users session.
        if ($token->character_id === 0) {
            // Fetch the users characters from DB
            $user = User::find($token->user_id);

            if(! $user)
                return redirect()->back()
                    ->with('error', 'Invalid token, user not found.');

            foreach($user->characters as $character) {
                session()->push('user_sharing', $character->character_id);
            }
        } else {
            session()->push('user_sharing', $token->character_id);
        }

        return redirect()->back()
            ->with('success', 'You have now been granted access to the requested users characters.');
    }
}
