# Webhooks

The package accepts POST requests from Omnidesk at a configured URL, determines the event type, and passes the payload to the registered handler.

## How it works

1. Omnidesk sends to your URL a body like: `{"type": "message_new", "object": { ... }}`.
2. The webhook route has parameter `{omniWebhook}` — used to find the record in `omni_webhooks` (by UUID).
3. From config `omnidesk.webhooks`, the `type` selects the `handler` and `data` classes.
4. `object` is mapped to a DTO (the `data` class), then `handler->handle($dataInput, $omniWebhook)` is called.
5. Response is `{"result": "ok"}` with status 200.

If the type is not in config or classes are missing/invalid, an exception is thrown (handled by your application).

## Webhook URL

By default the route is registered as:

```
POST omnidesk/{omniWebhook}/webhook
```

Prefix and path are set in `config/omnidesk.php` via `webhook_url` (or `OMNI_WEBHOOK_URL`). The URL must include the `{omniWebhook}` placeholder — it is the webhook UUID from the database.

Example full URL for a webhook with ID `550e8400-e29b-41d4-a716-446655440000`:

```
https://your-site.com/omnidesk/550e8400-e29b-41d4-a716-446655440000/webhook
```

Use this URL in Omnidesk webhook settings.

## Creating a webhook in the app

Via command:

```bash
php artisan omnidesk:webhooks:create
```

You will be prompted for: name (optional), channel, and whether to create an API key. A row is created in `omni_webhooks`. List webhooks with: `php artisan omnidesk:webhooks:list`.

The API key (if created) is stored encrypted in the database and can be used to verify incoming requests (implementation is in your middleware or handler).

## Registering handlers in config

In `config/omnidesk.php`:

```php
'webhooks' => [
    [
        'type' => 'message_new',
        'handler' => \App\Omnidesk\Handlers\MessageNewHandler::class,
        'data' => \App\Omnidesk\Data\MessageNewData::class,
    ],
],
```

- **type** — string matching the `type` field in the request body from Omnidesk.
- **handler** — class implementing `Omnidesk\Interfaces\WebhookHandlerInterface`.
- **data** — class extending `Spatie\LaravelData\Data` for deserializing `object`.

## Handler interface

```php
namespace Omnidesk\Interfaces;

use Omnidesk\Models\OmniWebhook;
use Spatie\LaravelData\Data;

interface WebhookHandlerInterface
{
    public function handle(Data $dataInput, OmniWebhook $omniWebhook): void;
}
```

- `$dataInput` — DTO built from `request->object` using the `data` class from config.
- `$omniWebhook` — webhook model (row from `omni_webhooks`) for the request URL.

## Example: "message_new" handler

Event type from Omnidesk: `message_new`. The package includes a test example data structure (namespace `Omnidesk\Test\WebhookMessageNew`).

**Data class** (matches fields from `object`):

```php
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class MessageNewData extends Data
{
    public function __construct(
        public readonly int $messageId,
        public readonly int $caseId,
        public readonly int $staffId,
        public readonly int $userId,
        public readonly string $customUserId,
        public readonly string $content,
        public readonly bool $note,
        public readonly bool $sentViaRule,
    ) {}
}
```

**Handler:**

```php
use Omnidesk\Interfaces\WebhookHandlerInterface;
use Omnidesk\Models\OmniWebhook;
use Spatie\LaravelData\Data;

final class MessageNewHandler implements WebhookHandlerInterface
{
    public function handle(Data $dataInput, OmniWebhook $omniWebhook): void
    {
        // $dataInput is an instance of MessageNewData
        // Dispatch to queue, save to DB, call a service, etc.
    }
}
```

For other Omnidesk event types, add your own `data` + `handler` pairs to `omnidesk.webhooks`.

## Middleware

The webhook route uses the `api` middleware stack by default. Extra middleware is configured in config:

```php
'webhook' => [
    'middleware' => ['throttle:60,1', 'log.webhooks'],
],
```

You can also add verification of the API key from `$omniWebhook->api_key` if it is set.
