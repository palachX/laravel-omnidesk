# Конфигурация

Файл конфигурации: `config/omnidesk.php`. Параметры можно переопределять через `.env`.

## Основные параметры

| Параметр | Описание | Переменная окружения | По умолчанию |
|----------|----------|----------------------|--------------|
| `host` | Базовый URL Omnidesk | `OMNI_HOST` | `http://localhost.omnidesk.ru` |
| `email` | Email для Basic Auth API | `OMNI_EMAIL` | `example@example.com` |
| `api_key` | API-ключ Omnidesk | `OMNI_API_KEY` | `api_key` |
| `webhook_url` | Шаблон URL для приёма вебхуков (должен содержать `{omniWebhook}`) | `OMNI_WEBHOOK_URL` | `webhook_url` |

### Важно про `webhook_url`

В маршруте должен быть параметр `{omniWebhook}`, чтобы по URL определялся конкретный вебхук (запись в `omni_webhooks`). Примеры:

- `omnidesk/{omniWebhook}/webhook` — по умолчанию в коде маршрута пакета
- Любой другой путь с плейсхолдером `{omniWebhook}`

Без `{omniWebhook}` в конфиге возможна ошибка при регистрации маршрута.

## Вебхуки

### `webhooks`

Массив конфигураций типов вебхуков. Каждый элемент:

| Ключ | Описание |
|------|----------|
| `type` | Строковый тип события от Omnidesk (например, `message_new`) |
| `handler` | Класс, реализующий `Omnidesk\Interfaces\WebhookHandlerInterface` |
| `data` | Класс Spatie Data для входящего объекта (типизированные данные из `object`) |

Пример в `config/omnidesk.php`:

```php
'webhooks' => [
    [
        'type' => 'message_new',
        'handler' => \App\Omnidesk\WebhookMessageNewHandler::class,
        'data' => \App\Omnidesk\WebhookMessageNewDataInput::class,
    ],
],
```

Подробнее: [Вебхуки](webhooks.md).

### `webhook.middleware`

Массив имён middleware для маршрута вебхука. По умолчанию к маршруту уже подключён `api`. Сюда можно добавить, например, логирование или кастомную проверку:

```php
'webhook' => [
    'middleware' => ['log.webhooks'],
],
```

## Пример .env

```env
OMNI_HOST=https://ваш-аккаунт.omnidesk.ru
OMNI_EMAIL=admin@example.com
OMNI_API_KEY=ваш_api_ключ_omnidesk
OMNI_WEBHOOK_URL=omnidesk/{omniWebhook}/webhook
```

Конфиг пакета использует `config('omnidesk')` и маппит его в DTO `Omnidesk\DTO\OmnideskConfig` (snake_case → camelCase через Spatie Laravel Data).
