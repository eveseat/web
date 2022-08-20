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

return [

    'permissions_check_all'              => 'Check all permissions',
    'permission_limit'                   => 'Permission limit',
    'limits'                             => 'limits',
    'members_addition'                   => 'Add members',

    // Divisions
    'no_division'                        => 'This permission has no division assigned',
    'military_division'                  => 'This permission is part of the Military division',
    'industrial_division'                => 'This permission is part of the Industrial division',
    'financial_division'                 => 'This permission is part of the Financial division',
    'assets_division'                    => 'This permission is part of the Assets division',

    // Global Scope
    'global_standing_builder_label'             => 'Grant access to the Standings Builder',
    'global_standing_builder_description'       => 'The Standings Builder shows an overview of your character, corporation and alliance standings. It is mainly used for exchanging standings between alliances of a coalition or corporations within an alliance. Also useful for character intel.',
    'global_invalid_tokens_label'             => 'Grant access to see invalidated tokens',
    'global_invalid_tokens_description'       => 'Showing invalid tokens allows you to see the characters associated with an account that are now invalid. Normally they are hidden.',
    'global_moons_reporter_label'               => 'Moon Reporter',
    'global_moons_reporter_description'         => 'The Moon Reporter can show all moons of New Eden and their registered composition reports.',
    'global_moons_reporter_manager_label'       => 'Moon Reports Manager',
    'global_moons_reporter_manager_description' => 'The Moon Reports Manager can create and update moon reports.',
    'global_queue_manager_label'                => 'Queue Manager',

    // Moon Reporter Scope
    'view_moon_reports_label'           => 'View Moon Reports',
    'view_moon_reports_description'     => 'Displays all moons in eve, and any resources within the moon, if that data is available.',
    'create_moon_reports_label'         => 'Create new Moon Reports',
    'create_moon_reports_description'   => 'Allows a user to submit moon probe results.',
    'manage_moon_reports_label'         => 'Manage Moon Reports',
    'manage_moon_reports_description'   => 'Allows a user to edit and/or delete moon reports.',

    // Character Scope
    'character_asset_label'              => 'Grant access to Character Assets',
    'character_asset_description'        => 'Displays every asset (item) of a character as well as their location and quantity.',
    'character_calendar_label'           => 'Grant access to Character Calendar Events ',
    'character_calendar_description'     => 'Displays every calendar event the character either created or is subscribed to.',
    'character_contact_label'            => 'Grant access to Character Contacts',
    'character_contact_description'      => 'Displays a characters contacts including name, standing and notes. Also shows links to third-party platforms (like zkillboard).',
    'character_contract_label'           => 'Grant access to Character Contracts ',
    'character_contract_description'     => 'Displays a characters contracts including creation date, type, status and content.',
    'character_fitting_label'            => 'Grant access to Character Fittings',
    'character_fitting_description'      => 'Displays fittings made by the character, including name, hull type and modules.',
    'character_industry_label'           => 'Grant access to Character Industry',
    'character_industry_description'     => 'Displays a list of all jobs owned by the character, including their starting date, expected ending, location, activity, runs amount, input blueprint and output product.',
    'character_blueprint_label'          => 'Grant access to Character Blueprints',
    'character_blueprint_description'    => 'Lists all blueprints owned by the character, including their name, available runs, research level and location.',
    'character_intel_label'              => 'Grant access to Character Intel',
    'character_intel_description'        => 'This is a tool showing aggregated information about a character based on a pre-built profile. It will also show you in-game interaction based on transactions and mails.',
    'character_killmail_label'           => 'Grant access to Character Killmails',
    'character_killmail_description'     => 'Displays all kills and losses of a character. It will show data, hull type, location and victim information.',
    'character_mail_label'               => 'Grant access to Character Mail',
    'character_mail_description'         => 'Displays all mails received by a character.',
    'character_market_label'             => 'Grant access to Character Market',
    'character_market_description'       => 'Displays all buy or sell orders made by a character.',
    'character_mining_label'             => 'Grant access to Character Mining',
    'character_mining_description'       => 'Displays statistics regarding mining done by a character. It is based on the in-game Personal Mining Ledger and shows date, system, ore, quantity, volume and estimated value.',
    'character_notification_label'       => 'Grant access to Character Notification',
    'character_notification_description' => 'Displays a characters notifications like DED payout grants or standings update notifiers',
    'character_planetary_label'          => 'Grant access to Character Planetary Interaction',
    'character_planetary_description'    => 'Displays planets on which the character has a command center and the linked installations.',
    'character_research_label'           => 'Grant access to Character Research Agents',
    'character_research_description'     => 'Lists all research agents which are currently working for the character.',
    'character_skill_label'              => 'Grant access to Character Skills',
    'character_skill_description'        => 'Displays all skills known by the character, including their trained level.',
    'character_standing_label'           => 'Grant access to Character Standing List',
    'character_standing_description'     => 'Lists a characters standings towards the different faction of New Eden.',
    'character_sheet_label'              => 'Grant access to Character Sheet',
    'character_sheet_description'        => 'The Character Sheet contains basic information like character attributes, titles, implants etc... It is also showing a skill queue summary and the skill currently in training.',
    'character_journal_label'            => 'Grant access to Character Wallet Journal',
    'character_journal_description'      => 'Displays a characters Wallet Journal',
    'character_transaction_label'        => 'Grant access to Character Wallet Transaction',
    'character_transaction_description'  => 'Displays a characters Wallet Transactions.',
    'character_loyalty_points_label'     => 'Grant access to Loyalty Points',
    'character_loyalty_points_description' => 'Displays a characters Loyalty Points.',

    // Corporation Scope
    'corporation_asset_label'                         => 'Grant access to Corporation Assets',
    'corporation_asset_description'                   => 'The Corporation Assets is showing every singled owned assets, their location and quantity.',
    'corporation_asset_first_division_label'          => 'Grant access to Corporation Assets inside the First Division',
    'corporation_asset_first_division_description'    => 'Grants permission to view all corporation assets inside the First (1st) Division.',
    'corporation_asset_second_division_label'         => 'Grant access to Corporation Assets inside the Second Division',
    'corporation_asset_second_division_description'   => 'Grants permission to view all corporation assets inside the Second (2nd) Division.',
    'corporation_asset_third_division_label'          => 'Grant access to Corporation Assets inside the Third Division',
    'corporation_asset_third_division_description'    => 'Grants permission to view all corporation assets inside the Third (3rd) Division.',
    'corporation_asset_fourth_division_label'         => 'Grant access to Corporation Assets inside the Fourth Division',
    'corporation_asset_fourth_division_description'   => 'Grants permission to view all corporation assets inside the Fourth (4th) Division.',
    'corporation_asset_fifth_division_label'          => 'Grant access to Corporation Assets inside the Fifth Division',
    'corporation_asset_fifth_division_description'    => 'Grants permission to view all corporation assets inside the Fifth (5th) Division.',
    'corporation_asset_sixth_division_label'          => 'Grant access to Corporation Assets inside the Sixth Division',
    'corporation_asset_sixth_division_description'    => 'Grants permission to view all corporation assets inside the Sixth (6th) Division.',
    'corporation_asset_seventh_division_label'        => 'Grant access to Corporation Assets inside the Seventh Division',
    'corporation_asset_seventh_division_description'  => 'Grants permission to view all corporation assets inside the Seventh (7th) Division.',
    'corporation_contact_label'                       => 'Grant access to Corporation Contacts',
    'corporation_contact_description'                 => 'Displays corporation contacts including name, standing and link to third-party platforms (like zkillboard).',
    'corporation_contract_label'                      => 'Grant access to Corporation Contracts',
    'corporation_contract_description'                => 'Displays corporation contracts including creation date, type, status and content.',
    'corporation_extraction_label'                    => 'Grant access to Corporation Extractions',
    'corporation_extraction_description'              => 'Displays moon extraction information of refineries owned by a corporation.',
    'corporation_industry_label'                      => 'Grant access to Corporation Industry',
    'corporation_industry_description'                => 'Displays all industry jobs made on behalf a corporation, their starting date, expected ending, location, activity, runs amount, input blueprint and output product.',
    'corporation_blueprint_label'                     => 'Grant access to Corporation Blueprints',
    'corporation_blueprint_description'               => 'Displays all blueprints owned by the corporation, their name, available runs, research level and locations.',
    'corporation_killmail_label'                      => 'Grant access to Corporation Kill Mails',
    'corporation_killmail_description'                => 'Displays all kills done or received by a corporation member. It will show data, hull type, location and victim information.',
    'corporation_ledger_label'                        => 'Grant access to Corporation Wallet Ledger',
    'corporation_ledger_description'                  => 'Displays corportion wallet transactions groups by category and per division.',
    'corporation_market_label'                        => 'Grant access to Corporation Market',
    'corporation_market_description'                  => 'Displays all buy or sell orders made on behalf of a corporation.',
    'corporation_mining_label'                        => 'Grant access to Corporation Mining',
    'corporation_mining_description'                  => 'Displays statistics regarding mining done by a character. It is based on the in-game Personal Mining Ledgers of each corporation member and shows date, system, ore, quantity, volume and estimated value.',
    'corporation_security_label'                      => 'Grant access to Corporation Security',
    'corporation_security_description'                => 'Provides information regarding roles setup, titles and hangar logs.',
    'corporation_standing_label'                      => 'Grant access to Corporation Standings',
    'corporation_standing_description'                => 'Displays all standings of the assigned level of a corporation.',
    'corporation_tracking_label'                      => 'Grant access to Corporation Tracking',
    'corporation_tracking_description'                => 'Displays a report of users registered on SeAT in comparison to all members.',
    'corporation_customs-office_label'                => 'Grant access to Corporation Customs Offices',
    'corporation_customs-office_description'          => 'Displays all Customs Offices owned by a corporation including their location, tax settings and accessibility level.',
    'corporation_starbase_label'                      => 'Grant access to Corporation Starbases',
    'corporation_starbase_description'                => 'Displays all starbases owned by the corporation including type, location, estimated offline period, reinforcement status and modules.',
    'corporation_structure_label'                     => 'Grant access to Corporation Structures',
    'corporation_structure_description'               => 'Displays all structures owned by the corporation including type, location, estimated offline period, reinforcement settings and services.',
    'corporation_summary_label'                       => 'Grant access to Corporation Sheet',
    'corporation_summary_description'                 => 'The Corporation Sheet contains basic information like the corporation name, description, divisions, etc...',
    'corporation_wallet_first_division_label'         => 'Grant access to the First Division of the Corporation Wallet.',
    'corporation_wallet_first_division_description'   => 'Displays the corporation wallet of the First (1st) Wallet Division.',
    'corporation_wallet_second_division_label'        => 'Grant access to the Second Division of the Corporation Wallet.',
    'corporation_wallet_second_division_description'  => 'Displays the corporation wallet of the Second (2nd) Wallet Division.',
    'corporation_wallet_third_division_label'         => 'Grant access to the Third Division of the Corporation Wallet.',
    'corporation_wallet_third_division_description'   => 'Displays the corporation wallet of the Third (3rd) Wallet Division.',
    'corporation_wallet_fourth_division_label'        => 'Grant access to the Fourth Division of the Corporation Wallet.',
    'corporation_wallet_fourth_division_description'  => 'Displays the corporation wallet of the Fourth (4th) Wallet Division.',
    'corporation_wallet_fifth_division_label'         => 'Grant access to the Fifth Division of the Corporation Wallet.',
    'corporation_wallet_fifth_division_description'   => 'Displays the corporation wallet of the Fifth (5th) Wallet Division.',
    'corporation_wallet_sixth_division_label'         => 'Grant access to the Sixth Division of the Corporation Wallet.',
    'corporation_wallet_sixth_division_description'   => 'Displays the corporation wallet of the Sixth (6th) Wallet Division.',
    'corporation_wallet_seventh_division_label'       => 'Grant access to the Seventh Division of the Corporation Wallet.',
    'corporation_wallet_seventh_division_description' => 'Displays the corporation wallet of the Seventh (7th) Wallet Division.',
    'corporation_journal_label'                       => 'Grant access to Corporation Wallet Journal',
    'corporation_journal_description'                 => 'Displays a corporations wallet journal.',
    'corporation_transaction_label'                   => 'Grant access to Corporation Wallet Transactions',
    'corporation_transaction_description'             => 'Displays a corporations Wallet Transactions.',

    // Alliance Scope
    'alliance_contact_label'         => 'Grant access to Alliance Contacts',
    'alliance_contact_description'   => 'Displays alliance contacts including name, standing and link to third-party platforms (like zkillboard).',
    'alliance_summary_label'         => 'Grant access to Alliance Summary Sheet',
    'alliance_summary_description'   => 'The Alliance Sheet contains basic information like the alliance name, founder, member corporations, etc...',
    'alliance_tracking_label'        => 'Grant access to Alliance Tracking',
    'alliance_tracking_description'  => 'Displays a report of users registered on SeAT in comparison to all members.',

    // Mail Scope
    'mail_bodies_label'   => 'Read Mail Bodies',
    'mail_subjects_label' => 'Read Mail Subjects',

    // People Scope
    'people_create_label' => 'Create People',
    'people_edit_label'   => 'Edit People',
    'people_view_label'   => 'View People',

    // Search Scope
    'search_character_assets_label'        => 'Search Character Assets',
    'search_character_contact_lists_label' => 'Search Character Contact Lists',
    'search_character_mail_label'          => 'Search Character Mail',
    'search_characters_label'              => 'Search Characters',
    'search_character_skills_label'        => 'Search Character Skills',
    'search_character_standings_label'     => 'Search Character Standings',
    'search_corporation_assets_label'      => 'Search Corporation Assets',
    'search_corporation_standings_label'   => 'Search Corporation Standings',
];
