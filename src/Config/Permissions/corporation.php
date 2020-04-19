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

return [
    'summary' => [
        'label'       => 'web::permissions.corporation_summary_label',
        'description' => 'web::permissions.corporation_summary_description',
        'gate'        => 'Seat\Web\Acl\Policies\CorporationPolicy@summary',
    ],
    'asset' => [
        'label'       => 'web::permissions.corporation_asset_label',
        'description' => 'web::permissions.corporation_asset_description',
        'gate'        => 'Seat\Web\Acl\Policies\CorporationPolicy@asset',
        'division'    => 'assets',
    ],
    'asset_first_division' => [
        'label'       => 'web::permissions.corporation_asset_first_division_label',
        'description' => 'web::permissions.corporation_asset_first_division_description',
        'gate'        => 'Seat\Web\Acl\Policies\CorporationPolicy@asset_first_division',
        'division'    => 'assets',
    ],
    'asset_second_division' => [
        'label'       => 'web::permissions.corporation_asset_second_division_label',
        'description' => 'web::permissions.corporation_asset_second_division_description',
        'gate'        => 'Seat\Web\Acl\Policies\CorporationPolicy@asset_second_division',
        'division'    => 'assets',
    ],
    'asset_third_division' => [
        'label'       => 'web::permissions.corporation_asset_third_division_label',
        'description' => 'web::permissions.corporation_asset_third_division_description',
        'gate'        => 'Seat\Web\Acl\Policies\CorporationPolicy@asset_third_division',
        'division'    => 'assets',
    ],
    'asset_fourth_division' => [
        'label'       => 'web::permissions.corporation_asset_fourth_division_label',
        'description' => 'web::permissions.corporation_asset_fourth_division_description',
        'gate'        => 'Seat\Web\Acl\Policies\CorporationPolicy@asset_fourth_division',
        'division'    => 'assets',
    ],
    'asset_fifth_division' => [
        'label'       => 'web::permissions.corporation_asset_fifth_division_label',
        'description' => 'web::permissions.corporation_asset_fifth_division_description',
        'gate'        => 'Seat\Web\Acl\Policies\CorporationPolicy@asset_fifth_division',
        'division'    => 'assets',
    ],
    'asset_sixth_division' => [
        'label'       => 'web::permissions.corporation_asset_sixth_division_label',
        'description' => 'web::permissions.corporation_asset_sixth_division_description',
        'gate'        => 'Seat\Web\Acl\Policies\CorporationPolicy@asset_sixth_division',
        'division'    => 'assets',
    ],
    'asset_seventh_division' => [
        'label'       => 'web::permissions.corporation_asset_seventh_division_label',
        'description' => 'web::permissions.corporation_asset_seventh_division_description',
        'gate'        => 'Seat\Web\Acl\Policies\CorporationPolicy@asset_seventh_division',
        'division'    => 'assets',
    ],
    'customs_office' => [
        'label'       => 'web::permissions.corporation_customs-office_label',
        'description' => 'web::permissions.corporation_customs-office_description',
        'gate'        => 'Seat\Web\Acl\Policies\CorporationPolicy@customs_office',
        'division'    => 'assets',
    ],
    'starbase' => [
        'label'       => 'web::permissions.corporation_starbase_label',
        'description' => 'web::permissions.corporation_starbase_description',
        'gate'        => 'Seat\Web\Acl\Policies\CorporationPolicy@starbase',
        'division'    => 'assets',
    ],
    'structure' => [
        'label'       => 'web::permissions.corporation_structure_label',
        'description' => 'web::permissions.corporation_structure_description',
        'gate'        => 'Seat\Web\Acl\Policies\CorporationPolicy@structure',
        'division'    => 'assets',
    ],
    'mining' => [
        'label'       => 'web::permissions.corporation_mining_label',
        'description' => 'web::permissions.corporation_mining_description',
        'gate'        => 'Seat\Web\Acl\Policies\CorporationPolicy@mining',
        'division'    => 'industrial',
    ],
    'extraction' => [
        'label'       => 'web::permissions.corporation_extraction_label',
        'description' => 'web::permissions.corporation_extraction_description',
        'gate'        => 'Seat\Web\Acl\Policies\CorporationPolicy@extraction',
        'division'    => 'industrial',
    ],
    'industry' => [
        'label'       => 'web::permissions.corporation_industry_label',
        'description' => 'web::permissions.corporation_industry_description',
        'gate'        => 'Seat\Web\Acl\Policies\CorporationPolicy@industry',
        'division'    => 'industrial',
    ],
    'blueprint' => [
        'label'       => 'web::permissions.corporation_blueprint_label',
        'description' => 'web::permissions.corporation_blueprint_description',
        'gate'        => 'Seat\Web\Acl\Policies\CorporationPolicy@blueprint',
        'division'    => 'industrial',
    ],
    'contract' => [
        'label'       => 'web::permissions.corporation_contract_label',
        'description' => 'web::permissions.corporation_contract_description',
        'gate'        => 'Seat\Web\Acl\Policies\CorporationPolicy@contract',
        'division'    => 'financial',
    ],
    'market' => [
        'label'       => 'web::permissions.corporation_market_label',
        'description' => 'web::permissions.corporation_market_description',
        'gate'        => 'Seat\Web\Acl\Policies\CorporationPolicy@market',
        'division'    => 'financial',
    ],
    'ledger' => [
        'label'       => 'web::permissions.corporation_ledger_label',
        'description' => 'web::permissions.corporation_ledger_description',
        'gate'        => 'Seat\Web\Acl\Policies\CorporationPolicy@ledger',
        'division'    => 'financial',
    ],
    'journal' => [
        'label'       => 'web::permissions.corporation_journal_label',
        'description' => 'web::permissions.corporation_journal_description',
        'gate'        => 'Seat\Web\Acl\Policies\CorporationPolicy@journal',
        'division'    => 'financial',
    ],
    'transaction' => [
        'label'       => 'web::permissions.corporation_transaction_label',
        'description' => 'web::permissions.corporation_transaction_description',
        'gate'        => 'Seat\Web\Acl\Policies\CorporationPolicy@transaction',
        'division'    => 'financial',
    ],
    'wallet_first_division' => [
        'label'       => 'web::permissions.corporation_wallet_first_division_label',
        'description' => 'web::permissions.corporation_wallet_first_division_description',
        'gate'        => 'Seat\Web\Acl\Policies\CorporationPolicy@wallet_first_division',
        'division'    => 'financial',
    ],
    'wallet_second_division' => [
        'label'       => 'web::permissions.corporation_wallet_second_division_label',
        'description' => 'web::permissions.corporation_wallet_second_division_description',
        'gate'        => 'Seat\Web\Acl\Policies\CorporationPolicy@wallet_second_division',
        'division'    => 'financial',
    ],
    'wallet_third_division' => [
        'label'       => 'web::permissions.corporation_wallet_third_division_label',
        'description' => 'web::permissions.corporation_wallet_third_division_description',
        'gate'        => 'Seat\Web\Acl\Policies\CorporationPolicy@wallet_third_division',
        'division'    => 'financial',
    ],
    'wallet_fourth_division' => [
        'label'       => 'web::permissions.corporation_wallet_fourth_division_label',
        'description' => 'web::permissions.corporation_wallet_fourth_division_description',
        'gate'        => 'Seat\Web\Acl\Policies\CorporationPolicy@wallet_fourth_division',
        'division'    => 'financial',
    ],
    'wallet_fifth_division' => [
        'label'       => 'web::permissions.corporation_wallet_fifth_division_label',
        'description' => 'web::permissions.corporation_wallet_fifth_division_description',
        'gate'        => 'Seat\Web\Acl\Policies\CorporationPolicy@wallet_fifth_division',
        'division'    => 'financial',
    ],
    'wallet_sixth_division' => [
        'label'       => 'web::permissions.corporation_wallet_sixth_division_label',
        'description' => 'web::permissions.corporation_wallet_sixth_division_description',
        'gate'        => 'Seat\Web\Acl\Policies\CorporationPolicy@wallet_sixth_division',
        'division'    => 'financial',
    ],
    'wallet_seventh_division' => [
        'label'       => 'web::permissions.corporation_wallet_seventh_division_label',
        'description' => 'web::permissions.corporation_wallet_seventh_division_description',
        'gate'        => 'Seat\Web\Acl\Policies\CorporationPolicy@wallet_seventh_division',
        'division'    => 'financial',
    ],
    'bookmark' => [
        'label'       => 'web::permissions.corporation_bookmark_label',
        'description' => 'web::permissions.corporation_bookmark_description',
        'gate'        => 'Seat\Web\Acl\Policies\CorporationPolicy@bookmark',
        'division'    => 'military',
    ],
    'contact' => [
        'label'       => 'web::permissions.corporation_contact_label',
        'description' => 'web::permissions.corporation_contact_description',
        'gate'        => 'Seat\Web\Acl\Policies\CorporationPolicy@contact',
        'division'    => 'military',
    ],
    'standing' => [
        'label'       => 'web::permissions.corporation_standing_label',
        'description' => 'web::permissions.corporation_standing_description',
        'gate'        => 'Seat\Web\Acl\Policies\CorporationPolicy@standing',
        'division'    => 'military',
    ],
    'killmail' => [
        'label'       => 'web::permissions.corporation_killmail_label',
        'description' => 'web::permissions.corporation_killmail_description',
        'gate'        => 'Seat\Web\Acl\Policies\CorporationPolicy@killmail',
        'division'    => 'military',
    ],
    'security' => [
        'label'       => 'web::permissions.corporation_security_label',
        'description' => 'web::permissions.corporation_security_description',
        'gate'        => 'Seat\Web\Acl\Policies\CorporationPolicy@security',
    ],
    'tracking' => [
        'label'       => 'web::permissions.corporation_tracking_label',
        'description' => 'web::permissions.corporation_tracking_description',
        'gate'        => 'Seat\Web\Acl\Policies\CorporationPolicy@tracking',
    ],
];
