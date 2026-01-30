# Omnidesk Laravel — документация

**Omnidesk Laravel** — пакет Laravel для быстрой интеграции вашего приложения с [Omnidesk](https://omnidesk.ru).

## Возможности

- **REST API** — создание обращений (cases), получение списка обращений, создание и обновление сообщений
- **Вебхуки** — приём событий от Omnidesk с настраиваемыми обработчиками по типу события
- **Мультитенантность** — несколько вебхуков (каналов) с отдельными URL и опциональной авторизацией по API-ключу
- **Команды Artisan** — создание вебхуков и просмотр списка

## Требования

- PHP ^8.3.6
- Laravel (совместим с пакетом через `orchestra/testbench` ^10.0)
- `spatie/laravel-data` ^4.19
- `spatie/laravel-package-tools` ^1.92

## Быстрый старт

1. Установите пакет и выполните миграции:

```bash
composer require palach/laravel-omnidesk
php artisan vendor:publish --tag="omnidesk-migrations"
php artisan migrate
```

2. Опубликуйте конфигурацию и при необходимости переводы:

```bash
php artisan vendor:publish --tag="omnidesk-config"
php artisan vendor:publish --tag="omnidesk-translations"
```

3. Задайте переменные окружения в `.env`:

```env
OMNI_HOST=https://ваш-аккаунт.omnidesk.ru
OMNI_EMAIL=email@example.com
OMNI_API_KEY=ваш_api_ключ
OMNI_WEBHOOK_URL=omnidesk/{omniWebhook}/webhook - опционально
```

4. Создайте вебхук и зарегистрируйте его URL в Omnidesk:

```bash
php artisan omnidesk:webhooks:create
```

5. Настройте обработчики вебхуков в `config/omnidesk.php` (см. [Вебхуки](webhooks.md)).

## Структура документации

| Раздел | Описание |
|--------|----------|
| [Установка](installation.md) | Подробная установка и публикация ресурсов |
| [Конфигурация](configuration.md) | Параметры конфигурации и переменные окружения |
| [Вебхуки](webhooks.md) | Настройка вебхуков и написание своих обработчиков |
| [API](api.md) | Использование HttpClient и use cases (cases, сообщения) |
| [Команды](commands.md) | Artisan-команды пакета |

## Языки

[English](../en/README.md)

## Лицензия

MIT. См. [LICENSE.md](../../LICENSE.md).
