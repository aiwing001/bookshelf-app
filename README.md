# BookShelf

BookShelfは、読書記録や読書計画を管理できるWebアプリケーションです。

書籍の登録・レビュー・お気に入り・ランキング・読書計画・通知などの機能を備えています。

本プロジェクトではLaravelを用いてバックエンドを中心に実装し、以下の機能を開発しました。

- 提供されたBladeテンプレートを利用したバックエンド開発
- 書籍・レビュー・ジャンル・読書計画のCRUD機能
- 認証・認可機能（Laravel Fortify・Policy）
- FormRequestによるバリデーション
- 通知機能・バッチ処理
- REST API
- PHPUnitによるテストコード

---

## 使用技術

### バックエンド

- PHP 8.5.8
- Laravel 10.50.2

### データベース

- MySQL

### 認証

- Laravel Fortify
- Laravel Sanctum

### フロントエンド（提供）

- Blade
- Tailwind CSS

### 開発環境

- Laravel Sail（Docker）
- Git / GitHub

### テスト

- PHPUnit

---

## 機能一覧

| 機能 | 内容 |
|------|------|
| 認証 | ユーザー登録・ログイン・ログアウト |
| 書籍管理 | 書籍の登録・一覧・詳細・編集・削除 |
| レビュー | 投稿・編集・削除 |
| お気に入り | 書籍のお気に入り登録・解除 |
| ランキング | 評価ランキング表示 |
| 読書計画 | 作成・編集・削除・期限管理 |
| 通知 | リマインダー通知・既読管理 |
| API | 書籍情報の取得・登録・更新・削除 |

---

## 環境構築

### 1. リポジトリをクローン

```bash
git clone https://github.com/aiwing001/bookshelf-app.git

cd bookshelf-app
```

### 2. Composerパッケージをインストール

```bash
docker run --rm \
-u "$(id -u):$(id -g)" \
-v "$(pwd):/var/www/html" \
-w /var/www/html \
-e COMPOSER_CACHE_DIR=/tmp/composer_cache \
laravelsail/php82-composer:latest \
composer install
```

### 3. `.env` を作成

```bash
cp .env.example .env
```

`.env` のデータベース設定を以下のように変更してください。

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password
```

### 4. Sailを起動

```bash
./vendor/bin/sail up -d --build
```

### 5. アプリケーションキーを生成

```bash
./vendor/bin/sail artisan key:generate
```

### 6. データベースを作成

```bash
./vendor/bin/sail artisan migrate:fresh --seed
```

### 7. フロントエンドをセットアップ

```bash
./vendor/bin/sail npm install

./vendor/bin/sail npm run dev
```

### 8. Google Books APIを設定

ISBN検索機能を利用するには、Google Books APIのAPIキーが必要です。

`.env`

```env
GOOGLE_BOOKS_API_KEY=あなたのAPIキー
```

`config/services.php`

```php
'google_books' => [
    'key' => env('GOOGLE_BOOKS_API_KEY'),
],
```

APIキーを設定していない場合は、ISBN検索機能は利用できません。

---

## ER図

![ER図](docs/er-diagram.png)

---

## API一覧

| Method | Endpoint | 内容 |
|---------|----------|------|
| GET | /api/v1/books | 書籍一覧取得 |
| GET | /api/v1/books/{book} | 書籍詳細取得 |
| POST | /api/v1/books | 書籍登録 |
| PUT | /api/v1/books/{book} | 書籍更新 |
| DELETE | /api/v1/books/{book} | 書籍削除 |

---

## テスト

PHPUnitを使用して、Unitテスト・Featureテストを実施しています。

```bash
./vendor/bin/sail artisan test
```

### テスト用アカウント

Seederで作成される以下のアカウントを利用できます。

- Email：`yamada@example.com`
- Password：`password`

※ 他にも複数のテストユーザーをSeederで作成しています。

### 通知機能の確認

通知を手動で生成する場合は、以下のコマンドを実行してください。

```bash
./vendor/bin/sail artisan app:check-reading-plans
```

※ 期日当日または3日前の読書計画が存在する場合に通知が生成されます。

---

## 工夫した点

- FormRequestを利用し、バリデーション処理を分離して保守性を向上
- Policyを利用し、所有者のみ編集・削除できる認可を実装
- Notificationとバッチ処理を組み合わせ、読書計画の期限通知を自動化
- REST APIを実装し、書籍情報をJSON形式で取得・操作可能にした
- PHPUnitによるUnitテスト・Featureテストを作成し、主要機能の動作を確認

---

## 開発環境URL

http://localhost

---

## 作成者

持田 修司