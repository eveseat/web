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

namespace Seat\Web\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Seat\Services\Search\Search;

/**
 * Class SearchController
 * @package Seat\Web\Http\Controllers\Support
 */
class SearchController extends Controller
{

    use Search;

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function searchAll(Request $request)
    {

        $query = $request->q;

        $characters = $this->doSearchCharacters($query);
        $corporations = $this->doSearchCorporations($query);
        $mail = $this->doSearchCharacterMail($query);

        return view('web::search.result', compact(
            'query', 'characters', 'corporations', 'mail'));
    }

}
