# Установка

## Установка через Composer

```bash
composer require palach/laravel-omnidesk
```

Пакет регистрирует `OmnideskServiceProvider` автоматически (через `extra.laravel.providers` в `composer.json`).

## Публикация миграций

Таблица `omni_webhooks` хранит данные вебхуков (id, name, channel, api_key).

```bash
php artisan vendor:publish --tag="omnidesk-migrations"
php artisan migrate
```

После миграции в БД появится таблица `omni_webhooks` с полями:

- `id` (UUID, primary)
- `name` (nullable)
- `channel` (обязательный)
- `api_key` (nullable, хранится в зашифрованном виде)
- `created_at`, `updated_at`

## Публикация конфигурации

Файл конфигурации: `config/omnidesk.php`.

```bash
php artisan vendor:publish --tag="omnidesk-config"
```

После публикации настройте `config/omnidesk.php` и переменные окружения (см. [Конфигурация](configuration.md)).

## Публикация переводов (опционально)

Сообщения для Artisan-команд (например, `omnidesk:webhooks:create`) можно локализовать:

```bash
php artisan vendor:publish --tag="omnidesk-translations"
```

Файлы появятся в `lang/vendor/omnidesk/` (например, `lang/vendor/omnidesk/en/commands.php`).

## Проверка установки

- Выполните миграции без ошибок.
- В конфиге есть `config/omnidesk.php` (если публиковали).
- Команды доступны:

```bash
php artisan omnidesk:webhooks:create
php artisan omnidesk:webhooks:list
```

- Маршрут вебхука зарегистрирован (по умолчанию `POST omnidesk/{omniWebhook}/webhook`, если задан `omnidesk.webhook_url`).
