# Omnidesk Laravel — Documentation

**Omnidesk Laravel** is a Laravel package for fast integration of your application with [Omnidesk](https://omnidesk.ru).

## Features

- **REST API** — create cases, fetch case list, create and update messages
- **Webhooks** — receive events from Omnidesk with configurable handlers per event type
- **Multi-tenancy** — multiple webhooks (channels) with separate URLs and optional API key authentication
- **Artisan commands** — create webhooks and list them

## Requirements

- PHP ^8.3.6
- Laravel (compatible via `orchestra/testbench` ^10.0)
- `spatie/laravel-data` ^4.19
- `spatie/laravel-package-tools` ^1.92

## Quick start

1. Install the package and run migrations:

```bash
composer require palach/laravel-omnidesk
php artisan vendor:publish --tag="omnidesk-migrations"
php artisan migrate
```

2. Publish config and optionally translations:

```bash
php artisan vendor:publish --tag="omnidesk-config"
php artisan vendor:publish --tag="omnidesk-translations"
```

3. Set environment variables in `.env`:

```env
OMNI_HOST=https://your-account.omnidesk.ru
OMNI_EMAIL=email@example.com
OMNI_API_KEY=your_api_key
OMNI_WEBHOOK_URL=omnidesk/{omniWebhook}/webhook - optional
```

4. Create a webhook and register its URL in Omnidesk:

```bash
php artisan omnidesk:webhooks:create
```

5. Configure webhook handlers in `config/omnidesk.php` (see [Webhooks](webhooks.md)).

## Documentation structure

| Section | Description |
|--------|--------------|
| [Installation](installation.md) | Detailed installation and publishing resources |
| [Configuration](configuration.md) | Config options and environment variables |
| [Webhooks](webhooks.md) | Webhook setup and custom handlers |
| [API](api.md) | HttpClient and use cases (cases, messages) |
| [Commands](commands.md) | Artisan commands |

## Languages

[Русский](../ru/README.md)

## License

MIT. See [LICENSE.md](../../LICENSE.md).
