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
    'character_journal_description'      => 'キャラクターのウォレットジャーナルを表示する',
    'character_transaction_label'        => 'キャラクターのウォレット取引記録へのアクセスを許可',
    'character_transaction_description'  => 'キャラクターのウォレット取引記録を表示する',
    'character_loyalty_points_label'     => 'ロイヤリティポイントへのアクセスを許可',
    'character_loyalty_points_description' => 'キャラクターのロイヤリティポイントを表示します。',

    // Corporation Scope
    'corporation_asset_label'                         => 'コーポレーション資産へのアクセスを許可',
    'corporation_asset_description'                   => 'コーポレーション・アセットには、単独所有のすべての資産、その場所、および数量が表示されています。',
    'corporation_asset_first_division_label'          => '部門1内のコーポレーション資産へのアクセスを許可',
    'corporation_asset_first_division_description'    => '部門1内のコーポレーション資産の閲覧を許可します',
    'corporation_asset_second_division_label'         => '部門2内のコーポレーション資産へのアクセスを許可',
    'corporation_asset_second_division_description'   => '部門2内のコーポレーション資産の閲覧を許可します',
    'corporation_asset_third_division_label'          => '部門3内のコーポレーション資産へのアクセスを許可',
    'corporation_asset_third_division_description'    => '部門3内のコーポレーション資産の閲覧を許可します',
    'corporation_asset_fourth_division_label'         => '部門4内のコーポレーション資産へのアクセスを許可',
    'corporation_asset_fourth_division_description'   => '部門4内のコーポレーション資産の閲覧を許可します',
    'corporation_asset_fifth_division_label'          => '部門5内のコーポレーション資産へのアクセスを許可',
    'corporation_asset_fifth_division_description'    => '部門5内のコーポレーション資産の閲覧を許可します',
    'corporation_asset_sixth_division_label'          => '部門6内のコーポレーション資産へのアクセスを許可',
    'corporation_asset_sixth_division_description'    => '部門6内のコーポレーション資産の閲覧を許可します',
    'corporation_asset_seventh_division_label'        => '部門7内のコーポレーション資産へのアクセスを許可',
    'corporation_asset_seventh_division_description'  => '部門7内のコーポレーション資産の閲覧を許可します',
    'corporation_contact_label'                       => 'コーポレーションのコンタクトへのアクセスを許可',
    'corporation_contact_description'                 => '名前、スタンディング、サードパーティのプラットフォーム(zkillboardなど) へのリンクなどを含むコーポレーションの連絡先を表示します。',
    'corporation_contract_label'                      => 'コーポレーションの契約へのアクセスを許可',
    'corporation_contract_description'                => '作成日、タイプ、ステータス、コンテンツなどを含むコーポレーションの契約を表示します。',
    'corporation_extraction_label'                    => 'コーポレーションの月掘り情報へのアクセスを許可',
    'corporation_extraction_description'              => 'コーポレーションが所有する精製所の月掘り情報を表示します。',
    'corporation_industry_label'                      => 'コーポレーション・インダストリーへのアクセスを許可',
    'corporation_industry_description'                => 'コーポレーションによって作成されたすべての工業ジョブ及び, その開始日、予定終了日、ロケーション、アクティビティ、実行量、入力ブループリント、出力製品を表示します.',
    'corporation_blueprint_label'                     => 'コーポレーションのブループリントへのアクセスを許可',
    'corporation_blueprint_description'               => 'コーポレーションが所有しているすべての設計図とその名前、利用可能なラン、研究レベルと場所を表示します。',
    'corporation_killmail_label'                      => 'コーポレーションのキルメールへのアクセス許可',
    'corporation_killmail_description'                => 'コーポレーションのメンバーによる, または, メンバーのされたすべてのキルを表示します。データ、船体のタイプ、位置、犠牲者情報を表示します。',
    'corporation_ledger_label'                        => 'コーポレーションウォレットの元帳へのアクセスを許可',
    'corporation_ledger_description'                  => '部門ごとに、種別おきにコーポレーションウォレット取引を表示します。',
    'corporation_market_label'                        => 'コーポレーション・マーケットへのアクセスを許可',
    'corporation_market_description'                  => 'コーポレーションにより行われたすべての売買注文を表示します。',
    'corporation_mining_label'                        => 'コーポレーションの採掘へのアクセスを許可',
    'corporation_mining_description'                  => 'キャラクターによるマイニングに関する統計情報を表示します。 これは、各コーポレーションメンバーのゲーム内の個人用マイニング元帳に基づいており、日付、システム、鉱石、体積、および推定価格を示しています。',
    'corporation_security_label'                      => 'コーポレーションのセキュリティへのアクセスを許可',
    'corporation_security_description'                => 'ロールの設定、タイトル、格納庫ログに関する情報を提供します。',
    'corporation_standing_label'                      => 'コーポレーションのスタンディングへのアクセスを許可',
    'corporation_standing_description'                => 'コーポレーションから割り当てられた階級のすべてのスタンディングを表示します。',
    'corporation_tracking_label'                      => 'コーポレーションのトラッキングへのアクセスを許可',
    'corporation_tracking_description'                => 'SeATに登録されたユーザーの全てのメンバーと比較したレポートを表示します.',
    'corporation_customs-office_label'                => 'コーポレーションの税関へのアクセス許可',
    'corporation_customs-office_description'          => '位置, 税率設定, アクセスレベルを含むコーポレーション所有の全ての税関を表示します.',
    'corporation_starbase_label'                      => 'コーポレーションのスターベースへのアクセスを許可',
    'corporation_starbase_description'                => 'タイプ, 位置, 推定オフライン日時, 強化ステータス, モジュールを含むコーポレーション所有の全てのスターベースを表示します.',
    'corporation_structure_label'                     => 'コーポレーションのストラクチャへのアクセスの許可',
    'corporation_structure_description'               => 'タイプ, タイプ, 位置, 推定オフライン日時, 強化ステータス, モジュールを含むコーポレーション所有の全てのストラクチャを表示します.',
    'corporation_summary_label'                       => 'コーポレーションシートへのアクセスを許可',
    'corporation_summary_description'                 => 'コーポレーションシートには, 名前, 説明, 部門などの基本情報が記載されています.',
    'corporation_wallet_first_division_label'         => 'コーポレーションウォレットの部門1へのアクセスを許可する。',
    'corporation_wallet_first_division_description'   => 'ウォレットの部門1のコーポレーションウォレットを表示します. ',
    'corporation_wallet_second_division_label'        => 'コーポレーションウォレットの部門2へのアクセスを許可する。',
    'corporation_wallet_second_division_description'  => 'ウォレットの部門2のコーポレーションウォレットを表示します.',
    'corporation_wallet_third_division_label'         => 'コーポレーションウォレットの部門3へのアクセスを許可する。',
    'corporation_wallet_third_division_description'   => 'ウォレットの部門3のコーポレーションウォレットを表示します.',
    'corporation_wallet_fourth_division_label'        => 'コーポレーションウォレットの部門4へのアクセスを許可する。',
    'corporation_wallet_fourth_division_description'  => 'ウォレットの部門4のコーポレーションウォレットを表示します.',
    'corporation_wallet_fifth_division_label'         => 'コーポレーションウォレットの部門5へのアクセスを許可する。',
    'corporation_wallet_fifth_division_description'   => 'ウォレットの部門5のコーポレーションウォレットを表示します.',
    'corporation_wallet_sixth_division_label'         => 'コーポレーションウォレットの部門6へのアクセスを許可する。',
    'corporation_wallet_sixth_division_description'   => 'ウォレットの部門6のコーポレーションウォレットを表示します.',
    'corporation_wallet_seventh_division_label'       => 'コーポレーションウォレットの部門7へのアクセスを許可する。',
    'corporation_wallet_seventh_division_description' => 'ウォレットの部門7のコーポレーションウォレットを表示します.',
    'corporation_journal_label'                       => 'コーポレーションウォレットジャーナルへのアクセスを許可',
    'corporation_journal_description'                 => 'コーポレーションのウォレットジャーナルを表示します。',
    'corporation_transaction_label'                   => 'コーポレーションのウォレット取引へのアクセスを許可',
    'corporation_transaction_description'             => 'コーポレーションのウォレット取引を表示します。',

    // Alliance Scope
    'alliance_contact_label'         => 'アライアンスのコンタクトへのアクセス許可',
    'alliance_contact_description'   => '名前, スタンディング, サードパーティのプラットフォームへのリンク先を含むアライアンスのコンタクトを表示する.',
    'alliance_summary_label'         => 'アライアンスの概要シートへのアクセスを許可',
    'alliance_summary_description'   => 'アライアンスシートは名前, 設立者, 加盟コーポレーションなどの基本情報を含む',
    'alliance_tracking_label'        => 'アライアンストラッキングへのアクセスを許可',
    'alliance_tracking_description'  => 'すべてのメンバーと比較して、SeATに登録されたユーザーのレポートを表示します。',

    // Mail Scope
    'mail_bodies_label'   => 'メール本文を読む',
    'mail_subjects_label' => 'メールの件名を読む',

    // People Scope
    'people_create_label' => '人を作成',
    'people_edit_label'   => 'ユーザーを編集',
    'people_view_label'   => '人物を表示',

    // Search Scope
    'search_character_assets_label'        => 'キャラクターアセットを検索',
    'search_character_contact_lists_label' => 'キャラクターのコンタクトリストを検索',
    'search_character_mail_label'          => 'キャラクターメールを検索',
    'search_characters_label'              => 'キャラクターを検索',
    'search_character_skills_label'        => 'キャラクタースキルを検索',
    'search_character_standings_label'     => 'キャラクタースタンディングを検索',
    'search_corporation_assets_label'      => 'コーポレーション資産を検索',
    'search_corporation_standings_label'   => 'コーポレーションスタンディングを検索',
];
