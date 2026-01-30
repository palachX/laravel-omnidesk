# Artisan commands

The package registers two console commands for Omnidesk webhooks.

## omnidesk:webhooks:create

Creates a new webhook record in the `omni_webhooks` table.

```bash
php artisan omnidesk:webhooks:create
```

Prompts interactively for:

1. **Webhook name** (optional) — prompt from translation `omnidesk::commands.new_webhook.enter_name`.
2. **Channel** — prompt from `omnidesk::commands.new_webhook.enter_channel`.
3. **Create API key?** — if yes, a 30-character random string is generated and stored encrypted in the DB. Save the key when it is shown.

On success the command returns 0. On error it prints the message and returns 1.

Using the created webhook:

- The webhook URL is built from `id` (UUID): this ID is used in the route in place of `{omniWebhook}`.
- Register this URL in Omnidesk webhook settings.
- The `api_key` field can be used to verify incoming requests (implement in your code).

---

## omnidesk:webhooks:list

Prints a table of all webhooks from `omni_webhooks`.

```bash
php artisan omnidesk:webhooks:list
```

Columns:

| Column     | Description        |
|-----------|--------------------|
| ID        | Webhook UUID       |
| Name      | Name (if set)      |
| Channel   | Channel            |
| Created at| Creation date      |

The `api_key` column is not shown. If there are no webhooks, it prints `No webhooks found`. Exit code on success: 0.

---

## Localizing command messages

Prompts for `omnidesk:webhooks:create` come from the package translations. After publishing translations:

```bash
php artisan vendor:publish --tag="omnidesk-translations"
```

files appear in `lang/vendor/omnidesk/<locale>/commands.php`. Keys (for default English locale):

- `new_webhook.starting_message` — message at the start.
- `new_webhook.enter_name` — prompt for name.
- `new_webhook.enter_channel` — prompt for channel.
- `new_webhook.webhook_created` — message on success (placeholder `:name`).
- `new_webhook.ask_to_create_api_key` — prompt for creating API key.

You can edit files under `lang/vendor/omnidesk/` or add your locale and copy the keys there.
