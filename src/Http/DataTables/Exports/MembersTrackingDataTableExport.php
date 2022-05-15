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

namespace Seat\Web\Http\DataTables\Exports;

use Seat\Eveapi\Models\Corporation\CorporationMemberTracking;
use Yajra\DataTables\Exports\DataTablesCollectionExport;

class MembersTrackingDataTableExport extends DataTablesCollectionExport
{
    public function collection()
    {
        return $this->collection->map(function ($row) {

            $character = CorporationMemberTracking::with('character', 'refresh_token', 'ship')->find($row['id']);

            return [
                'token' => self::isValidToken($character->refresh_token),
                'name' => $character->character->name,
                'location' => $character->location->name,
                'ship'=>$character->ship->typeName,
                'joined' => $character->start_date,
                'last_login' => $character->logon_date,
            ];
        });
    }

    public function headings(): array {
        return [
            'token',
            'name',
            'location',
            'ship',
            'joined',
            'last_login',
        ];
    }

    private static function isValidToken($token) {
        return ($token == null) ? 'invalid' : 'valid';
    }
}
