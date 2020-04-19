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

namespace Seat\Web\Acl;

class EsiRolesMap
{
    /**
     * @var \Seat\Web\Acl\EsiRolesMap
     */
    private static $instance;

    /**
     * @var \Illuminate\Support\Collection
     */
    private $map;

    /**
     * @var array
     */
    const DEFAULT_VALUES = [
        'Accountant'        => [
            'corporation.summary',
            'corporation.journal',
            'corporation.transaction',
        ],
        'Account_Take_1'    => [
            'corporation.wallet_first_division',
        ],
        'Account_Take_2'    => [
            'corporation.wallet_second_division',
        ],
        'Account_Take_3'    => [
            'corporation.wallet_third_division',
        ],
        'Account_Take_4'    => [
            'corporation.wallet_fourth_division',
        ],
        'Account_Take_5'    => [
            'corporation.wallet_fifth_division',
        ],
        'Account_Take_6'    => [
            'corporation.wallet_sixth_division',
        ],
        'Account_Take_7'    => [
            'corporation.wallet_seventh_division',
        ],
        'Auditor'           => [
            'corporation.summary',
        ],
        'Container_Take_1'  => [
            'asset_first_division',
        ],
        'Container_Take_2'  => [
            'asset_first_division',
        ],
        'Container_Take_3'  => [
            'asset_third_division',
        ],
        'Container_Take_4'  => [
            'asset_fourth_division',
        ],
        'Container_Take_5'  => [
            'asset_fifth_division',
        ],
        'Container_Take_6'  => [
            'asset_sixth_division',
        ],
        'Container_Take_7'  => [
            'asset_seventh_division',
        ],
        'Contract_Manager'  => [
            'corporation.summary',
            'corporation.contracts',
        ],
        'Diplomat'          => [
            'corporation.summary',
            'corporation.tracking',
        ],
        'Director'          => [
            'corporation.*', // All roles for you!
        ],
        'Junior_Accountant' => [
            'corporation.summary',
        ],
        'Security_Officer'  => [
            'corporation.summary',
            'corporation.security',
        ],
        'Trader'            => [
            'corporation.summary',
            'corporation.market',
        ],
    ];

    /**
     * EsiRolesMap constructor.
     */
    private function __construct()
    {
        $this->map = collect();

        foreach (self::DEFAULT_VALUES as $role => $permissions) {
            $element = new EsiRoleElement($role, $permissions);
            $this->add($element);
        }
    }

    /**
     * @return \Seat\Web\Acl\EsiRolesMap
     */
    public static function map(): EsiRolesMap {
        if (is_null(self::$instance))
            self::$instance = new EsiRolesMap();

        return self::$instance;
    }

    /**
     * @param \Seat\Web\Acl\EsiRoleElement $element
     */
    public function add(EsiRoleElement $element)
    {
        $this->map->put($element->role(), $element);
    }

    /**
     * @param \Seat\Web\Acl\EsiRoleElement $element
     */
    public function delete(EsiRoleElement $element)
    {
        $this->map->pull($element->role());
    }

    /**
     * @param string $esi_role
     * @return \Seat\Web\Acl\EsiRoleElement|null
     */
    public function get(string $esi_role): ?EsiRoleElement
    {
        return $this->map->get($esi_role);
    }
}
