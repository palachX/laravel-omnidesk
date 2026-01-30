# Configuration

Config file: `config/omnidesk.php`. Options can be overridden via `.env`.

## Main options

| Option | Description | Env variable | Default |
|--------|-------------|--------------|---------|
| `host` | Omnidesk base URL | `OMNI_HOST` | `http://localhost.omnidesk.ru` |
| `email` | Email for API Basic Auth | `OMNI_EMAIL` | `example@example.com` |
| `api_key` | Omnidesk API key | `OMNI_API_KEY` | `api_key` |
| `webhook_url` | URL pattern for webhooks (must contain `{omniWebhook}`) | `OMNI_WEBHOOK_URL` | `webhook_url` |

### About `webhook_url`

The route must include the `{omniWebhook}` parameter so the specific webhook record in `omni_webhooks` can be resolved by URL. Examples:

- `omnidesk/{omniWebhook}/webhook` — default in the package route
- Any other path with the `{omniWebhook}` placeholder

Without `{omniWebhook}` in the config, route registration may fail.

## Webhooks

### `webhooks`

Array of webhook type configs. Each entry:

| Key | Description |
|-----|-------------|
| `type` | String event type from Omnidesk (e.g. `message_new`) |
| `handler` | Class implementing `Omnidesk\Interfaces\WebhookHandlerInterface` |
| `data` | Spatie Data class for the incoming `object` (typed payload) |

Example in `config/omnidesk.php`:

```php
'webhooks' => [
    [
        'type' => 'message_new',
        'handler' => \App\Omnidesk\WebhookMessageNewHandler::class,
        'data' => \App\Omnidesk\WebhookMessageNewDataInput::class,
    ],
],
```

See [Webhooks](webhooks.md) for details.

### `webhook.middleware`

Array of middleware names for the webhook route. The route already uses the `api` middleware by default. Add e.g. logging or custom checks here:

```php
'webhook' => [
    'middleware' => ['log.webhooks'],
],
```

## Example .env

```env
OMNI_HOST=https://your-account.omnidesk.ru
OMNI_EMAIL=admin@example.com
OMNI_API_KEY=your_omnidesk_api_key
OMNI_WEBHOOK_URL=omnidesk/{omniWebhook}/webhook
```

The package reads `config('omnidesk')` and maps it to the `Omnidesk\DTO\OmnideskConfig` DTO (snake_case → camelCase via Spatie Laravel Data).
