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

    'permissions_check_all'              => 'Cocher toutes les autorisations',
    'permission_limit'                   => 'Restrictions',
    'limits'                             => 'limits',
    'members_addition'                   => 'Ajouter des membres',

    // Divisions
    'no_division'                        => 'Cette autorisation n\'a aucune division affectée',
    'military_division'                  => 'Cette autorisation fait partie de la division militaire',
    'industrial_division'                => 'Cette autorisation fait partie de la division industrielle',
    'financial_division'                 => 'Cette autorisation fait partie de la division financière',
    'assets_division'                    => 'Cette autorisation fait partie de la division des immobilisations',

    // Global Scope
    'global_standing_builder_label'             => 'Accorde l\'accès au gestionnaire de réputation',
    'global_standing_builder_description'       => 'Le gestionnaire de réputation fournis un aperçu des réputations de votre personnage, corporation ou alliance. Il est principalement utilisé dans l\'échange de réputation entre les alliances d\'une coalition ou des corporations au sein d\'une alliance. Il peut aussi être utile pour la base de connaissances des personnages.',
    'global_invalid_tokens_label'             => 'Accorde l\'accès pour voir les jetons invalidés',
    'global_invalid_tokens_description'       => 'Afficher les jetons non valides vous permet de voir les personnages associés à un compte qui sont maintenant invalides. Normalement ceux-ci ne sont pas visibles.',
    'global_moons_reporter_label'               => 'Moon Reporter',
    'global_moons_reporter_description'         => 'The Moon Reporter can show all moons of New Eden and their registered composition reports.',
    'global_moons_reporter_manager_label'       => 'Moon Reports Manager',
    'global_moons_reporter_manager_description' => 'The Moon Reports Manager can create and update moon reports.',
    'global_queue_manager_label'                => 'Gestionnaire de file d’attente',

    // Moon Reporter Scope
    'view_moon_reports_label'           => 'Afficher les rapports de lunes',
    'view_moon_reports_description'     => 'Affiche toutes les lunes dans EVE, et les ressources disponibles sur celles-ci, si ces données sont disponibles.',
    'create_moon_reports_label'         => 'Créer un nouveau rapport',
    'create_moon_reports_description'   => 'Permet à un utilisateur de soumettre les résultats d\'une analyse de lune.',
    'manage_moon_reports_label'         => 'Gérer les rapports des lunes',
    'manage_moon_reports_description'   => 'Permet à un utilisateur de modifier et/ou supprimer les rapports de lune.',

    // Character Scope
    'character_asset_label'              => 'Accorder l\'accès aux immobilisations de personnage',
    'character_asset_description'        => 'Affiche toutes les immobilisations (objets) d\'un personnage ainsi que leur emplacement et leur quantité.',
    'character_calendar_label'           => 'Accorder l\'accès aux événements du calendrier des personnages ',
    'character_calendar_description'     => 'Affiche chaque événement du calendrier créés par le personnage ou auxquels le personnage est abonné.',
    'character_contact_label'            => 'Accorder l\'accès aux contacts du personnage',
    'character_contact_description'      => 'Affiche les contacts de personnage incluant le nom, la réputation et les notes. Affiche également les liens vers des plates-formes tierces (comme zkillboard).',
    'character_contract_label'           => 'Accorder l\'accès aux contrats de personnage ',
    'character_contract_description'     => 'Affiche les contrats du personnage incluant la date de création, le type, le statut et le contenu.',
    'character_fitting_label'            => 'Accorder l\'accès aux configurations d\'équipements du personnage',
    'character_fitting_description'      => 'Affiche les configurations d\'équipements faits par le personnage, y compris le nom, le type de coque et les modules.',
    'character_industry_label'           => 'Accorder l\'accès à l\'industrie du personnage',
    'character_industry_description'     => 'Affiche une liste de toutes les opérations industrielles détenues par le personnage, y compris leur date de début, la fin prévue, l\'emplacement, l\'activité, la quantité, le plan d\'entrée et le produit de sortie.',
    'character_blueprint_label'          => 'Accorder l\'accès aux plans détenus par le personnage',
    'character_blueprint_description'    => 'Liste tous les plans détenus par le personnage, y compris leur nom, leurs nombres d\'opérations disponibles, le niveau de recherche et l\'emplacement.',
    'character_intel_label'              => 'Accorder l\'accès à la base de connaissance du personnage',
    'character_intel_description'        => 'Il s\'agit d\'un outil qui montre des informations agrégées sur un personnage, basé sur un profil pré-construit. Il vous montrera également l’interaction en jeu en fonction des transactions et des mails.',
    'character_killmail_label'           => 'Accorder l\'accès aux rapports de combats de personnage',
    'character_killmail_description'     => 'Affiche tous les assassinats et pertes d\'un personnage. Il affiche les données, le type de coque, l\'emplacement et les informations de la victime.',
    'character_mail_label'               => 'Accorder l\'accès au courrier du personnage',
    'character_mail_description'         => 'Affiche tous les mails reçus par un personnage.',
    'character_market_label'             => 'Accorder l\'accès aux opérations commerciales des personnages',
    'character_market_description'       => 'Affiche tous les ordres d\'achat ou de vente d\'un personnage.',
    'character_mining_label'             => 'Accorder l\'accès aux opérations minières de personnage',
    'character_mining_description'       => 'Affiche les statistiques concernant l\'extraction effectuée par un personnage. Il est basé sur les relevés minier personnel en jeu et affiche la date, le système, le minerai, la quantité, le volume et la valeur estimée.',
    'character_notification_label'       => 'Accorder l\'accès aux notifications de personnage',
    'character_notification_description' => 'Affiche des notifications de personnage comme les subventions de paiement DED ou les notifications de mise à jour de réputation',
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
    'corporation_ledger_description'                  => 'Affiche les transactions corporatives regroupées par catégorie et par division.',
    'corporation_market_label'                        => 'Accorder l\'accès aux opérations commerciales des corporations',
    'corporation_market_description'                  => 'Affiche tous les ordres d\'achat ou de vente effectués au nom d\'une corporation.',
    'corporation_mining_label'                        => 'Accorder l\'accès aux opérations minières des corporations',
    'corporation_mining_description'                  => 'Affiche les statistiques concernant l\'extraction effectuée par un personnage. Il est basé sur les relevés minier personnel de chaque membre de la corporation et affiche la date, le système, le minerai, la quantité, le volume et la valeur estimée.',
    'corporation_security_label'                      => 'Accorder l\'accès aux options de sécurité de la corporation',
    'corporation_security_description'                => 'Fournit des informations concernant la configuration des rôles, les titres et les journaux de hangar.',
    'corporation_standing_label'                      => 'Accorder l\'accès aux réputations de la corporation',
    'corporation_standing_description'                => 'Affiche toutes les réputations d\'une corporation.',
    'corporation_tracking_label'                      => 'Accorder l\'accès au suivi des corporations',
    'corporation_tracking_description'                => 'Affiche un rapport des utilisateurs inscrits sur SeAT par rapport à tous les membres.',
    'corporation_customs-office_label'                => 'Accorder l\'accès aux bureaux des douanes des corporations',
    'corporation_customs-office_description'          => 'Affiche tous les bureaux de douane détenus par une corporation, y compris leur emplacement, leurs paramètres fiscaux et leur niveau d\'accessibilité.',
    'corporation_starbase_label'                      => 'Grant access to Corporation Starbases',
    'corporation_starbase_description'                => 'Displays all starbases owned by the corporation including type, location, estimated offline period, reinforcement status and modules.',
    'corporation_structure_label'                     => 'Accorder l\'accès aux structures de la corporation',
    'corporation_structure_description'               => 'Affiche toutes les structures détenues par la corporation, y compris le type, l\'emplacement, la période hors-ligne estimée, les paramètres de renforcement et les services.',
    'corporation_summary_label'                       => 'Accorder l\'accès à la fiche de corporation',
    'corporation_summary_description'                 => 'La fiche de corporation contient des renseignements de base tels que le nom de la corporation, la description, les divisions, etc...',
    'corporation_wallet_first_division_label'         => 'Accorder l\'accès à la première division du portefeuille de la corporation.',
    'corporation_wallet_first_division_description'   => 'Affiche le portefeuille de la première division de la corporation.',
    'corporation_wallet_second_division_label'        => 'Accorder l\'accès à la seconde division du portefeuille de la corporation.',
    'corporation_wallet_second_division_description'  => 'Affiche le portefeuille de la deuxième division de la corporation.',
    'corporation_wallet_third_division_label'         => 'Accorder l\'accès à la troisième division du portefeuille de la corporation.',
    'corporation_wallet_third_division_description'   => 'Affiche le portefeuille de la troisième division de la corporation.',
    'corporation_wallet_fourth_division_label'        => 'Accorder l\'accès à la quatrième division du portefeuille de la corporation.',
    'corporation_wallet_fourth_division_description'  => 'Affiche le portefeuille de la quatrième division de la corporation.',
    'corporation_wallet_fifth_division_label'         => 'Accorder l\'accès à la cinquième division du portefeuille de la corporation.',
    'corporation_wallet_fifth_division_description'   => 'Affiche le portefeuille de la cinquième division de la corporation.',
    'corporation_wallet_sixth_division_label'         => 'Accorder l\'accès à la sixième division du portefeuille de la corporation.',
    'corporation_wallet_sixth_division_description'   => 'Affiche le portefeuille de la sixième division de la corporation.',
    'corporation_wallet_seventh_division_label'       => 'Accorder l\'accès à la septième division du portefeuille de la corporation.',
    'corporation_wallet_seventh_division_description' => 'Affiche le portefeuille de la septième division de la corporation.',
    'corporation_journal_label'                       => 'Grant access to Corporation Wallet Journal',
    'corporation_journal_description'                 => 'Displays a corporations wallet journal.',
    'corporation_transaction_label'                   => 'Grant access to Corporation Wallet Transactions',
    'corporation_transaction_description'             => 'Displays a corporations Wallet Transactions.',

    // Alliance Scope
    'alliance_contact_label'         => 'Autoriser l\'accès aux contacts d\'Alliance',
    'alliance_contact_description'   => 'Affiche les contacts d\'alliance incluant le nom, la réputation et les notes. Affiche également les liens vers des plates-formes tierces (comme zkillboard).',
    'alliance_summary_label'         => 'Accorder l\'accès à la fiche de résumé d\'Alliance',
    'alliance_summary_description'   => 'La feuille d\'alliance contient des informations de base comme le nom de l\'alliance, le fondateur, les sociétés membres, etc...',
    'alliance_tracking_label'        => 'Accorder l\'accès au suivi d\'Alliance',
    'alliance_tracking_description'  => 'Affiche un rapport des utilisateurs inscrits sur SeAT par rapport à tous les membres.',

    // Mail Scope
    'mail_bodies_label'   => 'Lire les corps des mails',
    'mail_subjects_label' => 'Lire les objets des mails',

    // People Scope
    'people_create_label' => 'Créer des contacts',
    'people_edit_label'   => 'Modifier les contacts',
    'people_view_label'   => 'Voir les contacts',

    // Search Scope
    'search_character_assets_label'        => 'Rechercher des immobilisations de personnage',
    'search_character_contact_lists_label' => 'Rechercher dans la liste des contacts du personnage',
    'search_character_mail_label'          => 'Rechercher dans les mails de personnage',
    'search_characters_label'              => 'Search Characters',
    'search_character_skills_label'        => 'Search Character Skills',
    'search_character_standings_label'     => 'Search Character Standings',
    'search_corporation_assets_label'      => 'Rechercher dans les immobilisations de corporation',
    'search_corporation_standings_label'   => 'Rechercher dans les réputations de corporation',
];
