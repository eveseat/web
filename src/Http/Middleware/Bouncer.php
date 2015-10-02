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

namespace Seat\Web\Http\Middleware;

use Closure;
use Seat\Web\Acl\Clipboard;

class Bouncer
{

    use Clipboard;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @param null                      $permission
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $permission = null)
    {

        // Get the currently logged in user
        $user = auth()->user();

        // Set the request char / corp ID
        $user->setCharacterId($request->character_id);
        $user->setCorporationId($request->corporation_id);

        // By adding |false to the acl parameters the
        // permission will pass regardless of any
        // affiliation requirements. We need to split
        // and (bool) the value to check.
        $permission = explode('|', $permission);

        // The first exploded value is the name, the
        // second is the bool indicating the importance
        // of an affiliation requirement too.
        $permission_name = $permission[0];
        $require_affiliation = isset($permission[1]) ? (bool)$permission[1] : true;

        // Check on the clipboard if this permission
        // should be granted.
        if ($user->has($permission_name, $require_affiliation))
            return $next($request);

        // TODO: Log this when the global security log is up.
//        dd('dont have ' . $permission_name . '|charID: ' .
//            $user->getCharacterID() . '|corpID: ' .
//            $user->getCorporationId());

        return view('web::auth.unauthorized');

    }
}
