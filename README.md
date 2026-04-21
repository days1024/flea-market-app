# 環境構築

## Dockerビルド
- https://github.com/days1024/flea-market-app.git
- docker-compose up -d --build

## Laravel環境構築
- docker-compose exec php bash
- composer install
- cp .env.example .env ,環境変数を適宜変更
- php artisan key:generate
- php artisan migrate --seed
- （または php artisan migrate:fresh --seed）
- php artisan storage:link

## Stripe設定

以下のキーをStripe公式サイトから取得し、.envに設定してください。

- STRIPE_KEY（pk_test_xxx）
- STRIPE_SECRET（sk_test_xxx）

※ 取得方法：Stripe Dashboard → Developers → API keys

## メール設定

以下を .env に設定してください

- MAIL_FROM_ADDRESS=your_email@example.com
- MAIL_FROM_NAME="Your App Name"

## 開発環境
- 商品一覧画面(トップ画面): http://localhost/
- 商品一覧画面(トップ画面)_マイリスト  http://localhost/?tab=mylist
- 会員登録画面  http://localhost/register
- ログイン画面 http://localhost/login
- 商品詳細画面: http://localhost/item/{item_id}
- 商品購入画面: http://localhost/purchase/{item_id}
- 送付先住所変更画面: http://localhost/purchase/address/{item_id}
- 商品出品画面: http://localhost/sell
- プロフィール画面: http://localhost/mypage
- プロフィール編集画面: http://localhost/mypage/profile
- プロフィール画面_購入した商品一覧: http://localhost/mypage?page=buy
- プロフィール画面_出品した商品一覧: http://localhost/mypage?page=sell
- phpMyAdmin: http://localhost:8080/

## 機能
-  ログアウト機能(/logout)
-  いいね機能(/items/{item}/like)
-  コメント送信機能(/items/{id}/comment)
-  メール認証機能
-  商品検索機能
-  商品購入機能
-   ※購入ボタンを押した際、stripe画面に接続されますが、今回はボタンを押した時点で購入される仕様となっております。

- ユーザー未認証の場合は商品一覧画面、商品詳細画面のみ入れるようなっております(いいね、コメント送信は登録後行えます)

## テスト（PHPUnit）/Laravelのテストは以下のコマンドでまとめて実行できます。

###手順1:Laravel本体（src配下）へ移動し、テスト用環境ファイルを作成してください。
-  cp .env.example .env.testing
###手順2:env.testingの設定を変更してください
- APP_KEY/DB設定/メール設定/stripe設定
###手順3:テスト用データベースを作成してください
- CREATE DATABASE laravel_test;
###手順4:テストを実行してください
-  php artisan test


# 使用技術

- PHP 8.1.34 (Docker)
- Composer 2.9.3
- Docker version 29.1.3
- MySQL 8.0.26
- nginx:1.21.1
- Laravel: 8.83.29

# ER図

![ER図](docs/ER図.png)