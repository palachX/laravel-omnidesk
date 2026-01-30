# Вебхуки

Пакет принимает POST-запросы от Omnidesk на заданный URL, определяет тип события и передаёт данные в зарегистрированный обработчик.

## Как это устроено

1. Omnidesk отправляет на ваш URL тело вида: `{"type": "message_new", "object": { ... }}`.
2. Маршрут вебхука содержит параметр `{omniWebhook}` — по нему находится запись в таблице `omni_webhooks` (по UUID).
3. По полю `type` из конфига `omnidesk.webhooks` выбираются классы `handler` и `data`.
4. `object` маппится в DTO (класс `data`), затем вызывается `handler->handle($dataInput, $omniWebhook)`.
5. В ответ возвращается `{"result": "ok"}` с кодом 200.

Если тип не найден в конфиге или классы не найдены/не подходят, выбрасывается исключение (обработка на уровне приложения).

## URL вебхука

По умолчанию маршрут регистрируется как:

```
POST omnidesk/{omniWebhook}/webhook
```

Префикс и путь задаются в `config/omnidesk.php` через `webhook_url` (или `OMNI_WEBHOOK_URL`). В URL должен быть плейсхолдер `{omniWebhook}` — подставляется UUID вебхука из БД.

Пример полного URL после создания вебхука с ID `550e8400-e29b-41d4-a716-446655440000`:

```
https://ваш-сайт.com/omnidesk/550e8400-e29b-41d4-a716-446655440000/webhook
```

Этот URL нужно указать в настройках вебхуков в Omnidesk.

## Создание вебхука в приложении

Через команду:

```bash
php artisan omnidesk:webhooks:create
```

Будет запрошено: имя (опционально), канал (channel), создавать ли API-ключ. Запись создаётся в `omni_webhooks`. Список вебхуков: `php artisan omnidesk:webhooks:list`.

API-ключ (если создан) хранится в БД в зашифрованном виде и может использоваться для проверки запросов (реализация проверки — в вашем middleware или в обработчике).

## Регистрация обработчиков в конфиге

В `config/omnidesk.php`:

```php
'webhooks' => [
    [
        'type' => 'message_new',
        'handler' => \App\Omnidesk\Handlers\MessageNewHandler::class,
        'data' => \App\Omnidesk\Data\MessageNewData::class,
    ],
],
```

- **type** — строка, совпадающая с полем `type` в теле запроса от Omnidesk.
- **handler** — класс, реализующий `Omnidesk\Interfaces\WebhookHandlerInterface`.
- **data** — класс, наследующий `Spatie\LaravelData\Data`, для десериализации `object`.

## Интерфейс обработчика

```php
namespace Omnidesk\Interfaces;

use Omnidesk\Models\OmniWebhook;
use Spatie\LaravelData\Data;

interface WebhookHandlerInterface
{
    public function handle(Data $dataInput, OmniWebhook $omniWebhook): void;
}
```

- `$dataInput` — DTO, собранный из `request->object` через класс `data` из конфига.
- `$omniWebhook` — модель вебхука (запись из `omni_webhooks`), соответствующая URL запроса.

## Пример: обработчик «message_new»

Тип события от Omnidesk: `message_new`. В пакете есть тестовый пример структуры данных (namespace `Omnidesk\Test\WebhookMessageNew`).

**Класс данных** (соответствует полям из `object`):

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

**Обработчик:**

```php
use Omnidesk\Interfaces\WebhookHandlerInterface;
use Omnidesk\Models\OmniWebhook;
use Spatie\LaravelData\Data;

final class MessageNewHandler implements WebhookHandlerInterface
{
    public function handle(Data $dataInput, OmniWebhook $omniWebhook): void
    {
        // $dataInput — экземпляр MessageNewData
        // Можно отправить в очередь, записать в БД, вызвать сервис и т.д.
    }
}
```

Для других типов событий Omnidesk создайте свои пары `data` + `handler` и добавьте их в `omnidesk.webhooks`.

## Middleware

К маршруту вебхука по умолчанию подключён стек `api`. Дополнительные middleware задаются в конфиге:

```php
'webhook' => [
    'middleware' => ['throttle:60,1', 'log.webhooks'],
],
```

Здесь же можно добавить проверку API-ключа из `$omniWebhook->api_key`, если он задан.
