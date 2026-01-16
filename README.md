# coachtechフリマ

## 環境構築

### Docker ビルド

1. git clone git@github.com:mami-kbb/frima.git
2. docker-compose up -d --build

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;※ OS 環境によって MySQL コンテナが起動しない場合があります。その場合は docker-compose.yml を各自の環境に合わせて調整してください。

### Laravel 環境構築

1. docker-compose exec php bash
2. composer install
3. cp .env.example .env
4. .env ファイルの一部を以下のように編集

```
DB_HOST=mysql
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
```

5. php artisan key:generate
6. php artisan migrate
7. php artisan db:seed

## Mail 設定（開発環境）

メール認証機能を利用するため、`.env` に以下を設定してください。

```
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_FROM_ADDRESS=test@example.com
MAIL_FROM_NAME="Flea Market App"
```
※ MailHog を使用しています
※ ブラウザで http://localhost:8025にアクセスしてください

## Stripe決済（テスト環境）

Stripeを使用して決済機能を実装しています。
現在はテスト環境のみ対応しています。

### インストール
```
1. composer require stripe/stripe-php
```

### 環境変数設定

`.env`ファイルに以下を設定してください。
```
STRIPE_KEY=pk_test_xxxxxxxxxxxxx
STRIPE_SECRET=sk_test_xxxxxxxxxxxxx
```
※ 各自のStripe アカウントのテスト用 API キーを設定してください。

### 設定ファイル

`config/services.php`に Stripe の設定を追加しています。
```
'stripe' => [
    'key' => env('STRIPE_KEY'),
    'secret' => env('STRIPE_SECRET'),
],
```

### 決済の流れ
1. 購入画面で支払い方法を選択
2. 「購入する」ボタンを押下
3. StripeのCheckout画面へ遷移
4. 決済完了後、商品一覧画面へ遷移

### テストカード情報
- カード番号：4242 4242 4242 4242
- 有効期限：任意の未来日
- CVC：任意の3桁

### 注意事項
本番環境で利用する場合は、Stripeの本番用APIキーに切り替えてください。

## テスト環境構築

1. cp .env.example .env.testing
2. .env.testing ファイルの一部を以下のように編集

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;※ demo_test は事前に MySQL 上で空のデータベースを作成してください
（`CREATE DATABASE demo_test;` で作成できます）

```
APP_ENV=test
DB_CONNECTION=mysql_test
DB_HOST=mysql
DB_DATABASE=demo_test
DB_USERNAME=root
DB_PASSWORD=root
```

3. php artisan key:generate --env=testing
4. php artisan migrate --env=testing
5. php artisan test

## user のログイン用初期データ

- メールアドレス: hoge@example.com
- パスワード: hoge1234

## 使用技術

- MySQL 8.0.26
- PHP 8.1-fpm
- Laravel 8
- mailhog
- Stripe

## 開発環境

- ログイン画面: http://localhost/login
- 会員登録画面: http://localhost/register
- 商品一覧画面: http://localhost/
- phpMyAdmin: http://localhost:8080/

## ER 図
![image](er.png)

## 追記事項

- マイページに表示される商品からも商品詳細画面を開けるようになっています。
- それに伴い、商品詳細画面（ show.blade.php ）では自分が出品した商品および購入済み商品は購入ボタンが表示されないようにしました。
- 要件シートの基本設計書内にあるRegisterRequest.phpの内容につきましては、Fortifyの仕様に従い`App\Actions\Fortify\CreateNewUser` クラス内に実装しています。
- メール認証機能を導入しました。