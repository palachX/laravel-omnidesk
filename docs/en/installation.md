# Installation

## Install via Composer

```bash
composer require palach/laravel-omnidesk
```

The package registers `OmnideskServiceProvider` automatically (via `extra.laravel.providers` in `composer.json`).

## Publish migrations

The `omni_webhooks` table stores webhook records (id, name, channel, api_key).

```bash
php artisan vendor:publish --tag="omnidesk-migrations"
php artisan migrate
```

After migration, the database will have table `omni_webhooks` with columns:

- `id` (UUID, primary)
- `name` (nullable)
- `channel` (required)
- `api_key` (nullable, stored encrypted)
- `created_at`, `updated_at`

## Publish configuration

Config file: `config/omnidesk.php`.

```bash
php artisan vendor:publish --tag="omnidesk-config"
```

After publishing, configure `config/omnidesk.php` and environment variables (see [Configuration](configuration.md)).

## Publish translations (optional)

Artisan command messages (e.g. for `omnidesk:webhooks:create`) can be localized:

```bash
php artisan vendor:publish --tag="omnidesk-translations"
```

Files will appear under `lang/vendor/omnidesk/` (e.g. `lang/vendor/omnidesk/en/commands.php`).

## Verify installation

- Migrations run without errors.
- Config exists at `config/omnidesk.php` (if published).
- Commands are available:

```bash
php artisan omnidesk:webhooks:create
php artisan omnidesk:webhooks:list
```

- Webhook route is registered (default `POST omnidesk/{omniWebhook}/webhook` when `omnidesk.webhook_url` is set).
