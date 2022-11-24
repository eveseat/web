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

    'permissions_check_all'              => '全権限にチェックを入れる',
    'permission_limit'                   => '権限の制限',
    'limits'                             => '制限',
    'members_addition'                   => '+ メンバーを追加',

    // Divisions
    'no_division'                        => 'この権限には人が割り当てられていません',
    'military_division'                  => 'この権限は軍事部門の一部です',
    'industrial_division'                => 'この権限が工業部門の一部です',
    'financial_division'                 => 'この権限が財政部門の一部です.',
    'assets_division'                    => 'この権限は資産部門の一部です',

    // Global Scope
    'global_standing_builder_label'             => 'スタンディングビルダーへのアクセスを許可',
    'global_standing_builder_description'       => 'スタンディングビルダーはキャラクターやコーポレーション, アライアンスのスタンディングを表示します. これは主に, コアリションのアライアンス間, アライアンスのコーポレーション間でのスタンディングの変換のために使われます. もちらん, キャラクター情報においても活用できます.',
    'global_invalid_tokens_label'             => '無効なトークンの閲覧を許可する',
    'global_invalid_tokens_description'       => '無効なトークンを表示すると, 現在無効なアカウントに紐づけられているキャラクターを見ることができます. 通常は非表示です.',
    'global_moons_reporter_label'               => 'ムーンレポーター',
    'global_moons_reporter_description'         => 'ムーンレポーターはNewEden中の全ての衛星と, それらの登録済みの組成レポートを表示することができます.',
    'global_moons_reporter_manager_label'       => 'ムーンレポートマネージャー',
    'global_moons_reporter_manager_description' => 'ムーンレポートマネージャーは衛星報告書の作成と更新ができます.',
    'global_queue_manager_label'                => 'キューマネージャー',

    // Moon Reporter Scope
    'view_moon_reports_label'           => '衛星報告書を表示',
    'view_moon_reports_description'     => 'データが利用可能であれば、eve内のすべての衛星と衛星内の任意の資源を表示します。',
    'create_moon_reports_label'         => '新しい衛星報告書の作成',
    'create_moon_reports_description'   => 'ユーザーが衛星調査プローブの結果を送信できるようにします。',
    'manage_moon_reports_label'         => '衛星報告書の管理',
    'manage_moon_reports_description'   => '衛星報告書の編集や削除をユーザーに許可します。',

    // Character Scope
    'character_asset_label'              => 'キャラクター資産へのアクセスを許可',
    'character_asset_description'        => 'キャラクターのすべての資産(アイテム) とその位置と数量を表示します。',
    'character_calendar_label'           => 'キャラクターカレンダーイベントへのアクセスを許可する ',
    'character_calendar_description'     => 'キャラクターが作成または購読しているカレンダーイベントをすべて表示します。',
    'character_contact_label'            => 'キャラクターのコンタクトへのアクセスを許可する',
    'character_contact_description'      => '名前, スタンディング, メモなどを含むキャラクターのコンタクトを表示します. また, サードパーティのプラットフォームへのリンクを表示します. ( zkillboardなど)',
    'character_contract_label'           => 'キャラクターの契約へのアクセスを許可 ',
    'character_contract_description'     => '日時, タイプ, ステータス, 契約内容を含むキャラクターの契約を表示します.',
    'character_fitting_label'            => 'キャラクターのFitへのアクセスを許可',
    'character_fitting_description'      => '名前, 船名, モジュールを含む, キャラクターによって作成されたFItを表示します.',
    'character_industry_label'           => 'キャラクターのインダストリーへのアクセスを許可',
    'character_industry_description'     => 'キャラクターが所有しているすべてのジョブのリストを表示します。開始日、予想される終了日、位置、アクティビティ、実行量、入力ブループリント、出力製品などが表示されます。',
    'character_blueprint_label'          => 'キャラクターのブループリントへのアクセスを許可',
    'character_blueprint_description'    => 'キャラクターが所有しているブループリント(名前、利用可能なラン、リサーチレベル、ロケーションなど) が一覧表示されます。',
    'character_intel_label'              => 'キャラクター情報へのアクセスを許可',
    'character_intel_description'        => 'これは、ゲーム内のプロフィールに基づいて、キャラクターに関する集計情報を表示するツールです。 また、トランザクションやメールに基づいたゲーム内対話も表示されます。',
    'character_killmail_label'           => 'キャラクターのキルメールへのアクセスを許可する',
    'character_killmail_description'     => 'キャラクターのキルとロスをすべて表示します。データ、船体のタイプ、位置、被害者情報を表示します。',
    'character_mail_label'               => 'キャラクターメールへのアクセスを許可',
    'character_mail_description'         => 'キャラクターが受信したすべてのメールを表示します。',
    'character_market_label'             => 'キャラクターのマーケットへのアクセスを許可',
    'character_market_description'       => 'キャラクターによるすべての買注文または売注文を表示します。',
    'character_mining_label'             => 'キャラクターの採掘へのアクセスを許可',
    'character_mining_description'       => 'キャラクターによる採掘に関する統計情報を表示します。 ゲーム内の個人採掘元帳に基づいており、日付、システム、鉱石、量、量、および推定価格を表示します。',
    'character_notification_label'       => 'キャラクターの通知へのアクセスを許可',
    'character_notification_description' => 'DED 支払い補助金やスタンディング更新通知などのキャラクター通知を表示します',
    'character_planetary_label'          => 'キャラクターのPI情報へのアクセスを許可',
    'character_planetary_description'    => 'キャラクターがコマンドセンターとリンクされた施設を持つ惑星を表示します。',
    'character_research_label'           => 'キャラクターの研究エージェントへのアクセスを許可',
    'character_research_description'     => 'キャラクターのために現在稼働しているすべての研究エージェントを一覧表示します。',
    'character_skill_label'              => 'キャラクタースキルへのアクセスを許可',
    'character_skill_description'        => 'キャラクターが習得済みのスキルをレベル込みですべて表示します。',
    'character_standing_label'           => 'キャラクターのスタンディングリストへのアクセスを許可',
    'character_standing_description'     => 'NewEdenの様々な勢力へのキャラクターのスタンディングを表示します.',
    'character_sheet_label'              => 'キャラクターシートへのアクセスを許可',
    'character_sheet_description'        => 'キャラクターシートには、キャラクター属性、タイトル、インプラントなどの基本情報が含まれています。 をクリックします。スキルキューの概要と現在トレーニング中のスキルも表示されます。',
    'character_journal_label'            => 'キャラクターのウォレットジャーナルへのアクセスを許可',
    'character_journal_description'      => 'キャラクターのウォレットジャーナルを表示',
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
