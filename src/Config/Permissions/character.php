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
    'sheet' => [
        'label'       => 'web::permissions.character_sheet_label',
        'description' => 'web::permissions.character_sheet_description',
        'gate'        => 'Seat\Web\Acl\Policies\CharacterPolicy@sheet',
    ],
    'intel' => [
        'label'       => 'web::permissions.character_intel_label',
        'description' => 'web::permissions.character_intel_description',
        'gate'        => 'Seat\Web\Acl\Policies\CharacterPolicy@intel',
    ],
    'calendar' => [
        'label'       => 'web::permissions.character_calendar_label',
        'description' => 'web::permissions.character_calendar_description',
        'gate'        => 'Seat\Web\Acl\Policies\CharacterPolicy@calendar',
    ],
    'mail' => [
        'label'       => 'web::permissions.character_mail_label',
        'description' => 'web::permissions.character_mail_description',
        'gate'        => 'Seat\Web\Acl\Policies\CharacterPolicy@mail',
    ],
    'notification' => [
        'label'       => 'web::permissions.character_notification_label',
        'description' => 'web::permissions.character_notification_description',
        'gate'        => 'Seat\Web\Acl\Policies\CharacterPolicy@notification',
    ],
    'skill' => [
        'label'       => 'web::permissions.character_skill_label',
        'description' => 'web::permissions.character_skill_description',
        'gate'        => 'Seat\Web\Acl\Policies\CharacterPolicy@skill',
    ],
    'asset' => [
        'label'       => 'web::permissions.character_asset_label',
        'description' => 'web::permissions.character_asset_description',
        'gate'        => 'Seat\Web\Acl\Policies\CharacterPolicy@asset',
        'division'    => 'assets',
    ],
    'mining' => [
        'label'       => 'web::permissions.character_mining_label',
        'description' => 'web::permissions.character_mining_description',
        'gate'        => 'Seat\Web\Acl\Policies\CharacterPolicy@mining',
        'division'    => 'industrial',
    ],
    'industry' => [
        'label'       => 'web::permissions.character_industry_label',
        'description' => 'web::permissions.character_industry_description',
        'gate'        => 'Seat\Web\Acl\Policies\CharacterPolicy@industry',
        'division'    => 'industrial',
    ],
    'blueprint' => [
        'label'       => 'web::permissions.character_blueprint_label',
        'description' => 'web::permissions.character_blueprint_description',
        'gate'        => 'Seat\Web\Acl\Policies\CharacterPolicy@blueprint',
        'division'    => 'industrial',
    ],
    'research' => [
        'label'       => 'web::permissions.character_research_label',
        'description' => 'web::permissions.character_research_description',
        'gate'        => 'Seat\Web\Acl\Policies\CharacterPolicy@research',
        'division'    => 'industrial',
    ],
    'planetary' => [
        'label'       => 'web::permissions.character_planetary_label',
        'description' => 'web::permissions.character_planetary_description',
        'gate'        => 'Seat\Web\Acl\Policies\CharacterPolicy@planetary',
        'division'    => 'industrial',
    ],
    'contract' => [
        'label'       => 'web::permissions.character_contract_label',
        'description' => 'web::permissions.character_contract_description',
        'gate'        => 'Seat\Web\Acl\Policies\CharacterPolicy@contract',
        'division'    => 'financial',
    ],
    'market' => [
        'label'       => 'web::permissions.character_market_label',
        'description' => 'web::permissions.character_market_description',
        'gate'        => 'Seat\Web\Acl\Policies\CharacterPolicy@market',
        'division'    => 'financial',
    ],
    'journal' => [
        'label'       => 'web::permissions.character_journal_label',
        'description' => 'web::permissions.character_journal_description',
        'gate'        => 'Seat\Web\Acl\Policies\CharacterPolicy@journal',
        'division'    => 'financial',
    ],
    'transaction' => [
        'label'       => 'web::permissions.character_transaction_label',
        'description' => 'web::permissions.character_transaction_description',
        'gate'        => 'Seat\Web\Acl\Policies\CharacterPolicy@transaction',
        'division'    => 'financial',
    ],
    'bookmark' => [
        'label'       => 'web::permissions.character_bookmark_label',
        'description' => 'web::permissions.character_bookmark_description',
        'gate'        => 'Seat\Web\Acl\Policies\CharacterPolicy@bookmark',
        'division'    => 'military',
    ],
    'contact' => [
        'label'       => 'web::permissions.character_contact_label',
        'description' => 'web::permissions.character_contact_description',
        'gate'        => 'Seat\Web\Acl\Policies\CharacterPolicy@contact',
        'division'    => 'military',
    ],
    'standing' => [
        'label'       => 'web::permissions.character_standing_label',
        'description' => 'web::permissions.character_standing_description',
        'gate'        => 'Seat\Web\Acl\Policies\CharacterPolicy@standing',
        'division'    => 'military',
    ],
    'killmail' => [
        'label'       => 'web::permissions.character_killmail_label',
        'description' => 'web::permissions.character_killmail_description',
        'gate'        => 'Seat\Web\Acl\Policies\CharacterPolicy@killmail',
        'division'    => 'military',
    ],
    'fitting' => [
        'label'       => 'web::permissions.character_fitting_label',
        'description' => 'web::permissions.character_fitting_description',
        'gate'        => 'Seat\Web\Acl\Policies\CharacterPolicy@fitting',
        'division'    => 'military',
    ],
];
