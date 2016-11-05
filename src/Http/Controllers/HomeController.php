<?php
/*
This file is part of SeAT

Copyright (C) 2015, 2016  Leon Jacobs

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

namespace Seat\Web\Http\Controllers;

use Seat\Services\Repositories\Eve\EveRepository;
use Seat\Services\Settings\Seat;

/**
 * Class HomeController
 * @package Seat\Web\Http\Controllers
 */
class HomeController extends Controller
{

    use EveRepository;

    /**
     *
     * @return \Illuminate\View\View
     */
    public function getHome()
    {

        // Warn if the admin contact has not been set yet.
        if (auth()->user()->hasSuperUser())
            if (Seat::get('admin_contact') === 'seatadmin@localhost.local')
                session()->flash('warning', trans('web::seat.admin_contact_warning'));

        // Check for the default EVE SSO generated email.
        if(str_contains(auth()->user()->email, '@seat.local'))
            session()->flash('warning', trans('web::seat.sso_email_warning'));

        $server_status = $this->getEveLastServerStatus();

        return view('web::home', compact('server_status'));
    }

}
