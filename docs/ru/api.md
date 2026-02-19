# API Omnidesk (HttpClient, клиенты и Use Cases)

Пакет предоставляет фасад HTTP‑клиента для работы с API Omnidesk, транспортный слой и типизированные use cases для типовых операций.

## HttpClient и клиенты

Класс `Palach\Omnidesk\Facade\HttpClient` зарегистрирован в контейнере как синглтон и использует конфигурацию (host, email, api_key) из `config/omnidesk.php`.  
Он предоставляет два типизированных клиента:

- `Palach\Omnidesk\Clients\CasesClient` — операции с обращениями (cases)
- `Palach\Omnidesk\Clients\MessagesClient` — операции с сообщениями

Использование в коде (внедрение через конструктор или `app(HttpClient::class)`):

```php
use Palach\Omnidesk\Facade\HttpClient;
use Palach\Omnidesk\Clients\CasesClient;
use Palach\Omnidesk\Clients\MessagesClient;

/** @var HttpClient $http */
$http = app(HttpClient::class);

/** @var CasesClient $cases */
$cases = $http->cases();

/** @var MessagesClient $messages */
$messages = $http->messages();
```

### Транспорт и аутентификация

Внутри оба клиента используют `Palach\Omnidesk\Transport\OmnideskTransport`, который отправляет запросы с HTTP Basic Auth (`email` и `api_key` из конфига) и заголовком `Accept: application/json`.  
При ошибках сети или неожиданном формате ответа методы выбрасывают исключения (`RequestException`, `ConnectionException`, `UnexpectedResponseException`).

### Методы

- **CasesClient::store(StoreCasePayload $payload): StoreCaseResponse** — создание обращения (case).
- **CasesClient::fetchList(FetchCaseListPayload $payload): FetchCaseListResponse** — список обращений с пагинацией и фильтрами.
- **CasesClient::rate(RateCasePayload $payload): RateCaseResponse** — оценка обращения.
- **MessagesClient::store(StoreMessagePayload $payload): StoreMessageResponse** — создание сообщения в обращении.
- **MessagesClient::update(UpdateMessagePayload $payload): UpdateMessageResponse** — обновление сообщения.
- **MessagesClient::rate(RateMessagePayload $payload): RateMessageResponse** — оценка сообщения.

---

## Store Case (создание обращения)

**Payload:** `Palach\Omnidesk\UseCases\V1\StoreCase\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\StoreCase\Response` (содержит `CaseData`).

**CaseStoreData** (поле `case` в Payload):

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| user_custom_id | string | да | Внешний идентификатор пользователя |
| subject | string | да | Тема обращения |
| content | string | да | Текст (plain) |
| content_html | string | да | Текст (HTML) |
| channel | string | да | Канал |
| user_email | string | нет* | Email пользователя |
| user_phone | string | нет* | Телефон пользователя |
| attachments | AttachmentData[] | нет | Массив DTO `AttachmentData` (двоичное содержимое файла в base64) |
| attachmentUrls | string[] | нет | Массив строковых URL файлов, которые нужно прикрепить к обращению |

\* Одно из полей `user_email` или `user_phone` обязательно (атрибут `RequiredWithout`).

Пример:

```php
use Palach\Omnidesk\Facade\HttpClient;
use Palach\Omnidesk\Clients\CasesClient;
use Palach\Omnidesk\UseCases\V1\StoreCase\CaseStoreData;
use Palach\Omnidesk\UseCases\V1\StoreCase\Payload as StoreCasePayload;
use Palach\Omnidesk\DTO\AttachmentData;

/** @var HttpClient $http */
$http = app(HttpClient::class);

/** @var CasesClient $cases */
$cases = $http->cases();
$payload = new StoreCasePayload(
    case: new CaseStoreData(
        userCustomId: 'ext-123',
        subject: 'Тема',
        content: 'Текст',
        contentHtml: '<p>Текст</p>',
        channel: 'email',
        userEmail: 'user@example.com',
        attachments: [
            new AttachmentData(
                name: 'manual.pdf',
                mimeType: 'application/pdf',
                content: '...base64-encoded-file...',
            ),
        ],
        attachmentUrls: [
            'https://example.com/files/manual.pdf',
        ],
$payload = new StoreCasePayload(
    case: new CaseStoreData(
        userCustomId: 'ext-123',
        subject: 'Тема',
        content: 'Текст',
        contentHtml: '<p>Текст</p>',
        channel: 'email',
        userEmail: 'user@example.com',
        attachments: [
            new AttachmentData(
                name: 'manual.pdf',
                mimeType: 'application/pdf',
                content: '...base64-encoded-file...',
            ),
        ],
        attachmentUrls: [
            'https://example.com/files/manual.pdf',
        ],
    )
);
$response = $cases->store($payload);
$case = $response->case; // CaseData
```

---

## Fetch Case List (список обращений)

**Payload:** `Palach\Omnidesk\UseCases\V1\FetchCaseList\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\FetchCaseList\Response` (поля: `cases` — коллекция `CaseData`, `total` — общее количество).

Параметры запроса (все опциональны):

| Поле | Тип | Ограничения | Описание |
|------|-----|-------------|----------|
| status | array\|Optional | — | Фильтр по статусам |
| channel | array\|Optional | — | Фильтр по каналам |
| user_custom_id | array\|Optional | — | Фильтр по user_custom_id |
| page | int\|Optional | 1–500 | Страница (по умолчанию в API Omnidesk: 1) |
| limit | int\|Optional | 1–100 | Размер страницы (по умолчанию в API: 100) |
| show_active_chats | bool\|Optional | — | Показывать активные чаты |

Для GET-запроса используется метод `Payload::toQuery()`.

Пример:

```php
use Palach\Omnidesk\Facade\HttpClient;
use Palach\Omnidesk\Clients\CasesClient;
use Palach\Omnidesk\UseCases\V1\FetchCaseList\Payload as FetchCaseListPayload;

/** @var HttpClient $http */
$http = app(HttpClient::class);

/** @var CasesClient $cases */
$cases = $http->cases();
$payload = new FetchCaseListPayload(
    page: 1,
    limit: 20,
    status: ['open'],
);
$response = $cases->fetchList($payload);
$cases = $response->cases;
$total = $response->total;
```

---

## Store Message (создание сообщения)

**Payload:** `Palach\Omnidesk\UseCases\V1\StoreMessage\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\StoreMessage\Response` (поле `message` — `MessageData`).

**MessageStoreData** (поле `message` в Payload):

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| user_id | int | да | ID пользователя Omnidesk |
| content | string | да | Текст сообщения |
| case_id | int | нет* | ID обращения |
| case_number | string | нет* | Номер обращения |
| attachments | AttachmentData[] | нет | Массив DTO `AttachmentData` (двоичное содержимое файла в base64) |
| attachmentUrls | string[] | нет | Массив строковых URL файлов, которые нужно прикрепить к сообщению |

\* Обязательно одно из: `case_id` или `case_number` (`RequiredWithout`).

Пример:

```php
use Palach\Omnidesk\Facade\HttpClient;
use Palach\Omnidesk\Clients\MessagesClient;
use Palach\Omnidesk\UseCases\V1\StoreMessage\MessageStoreData;
use Palach\Omnidesk\UseCases\V1\StoreMessage\Payload as StoreMessagePayload;
use Palach\Omnidesk\DTO\AttachmentData;

/** @var HttpClient $http */
$http = app(HttpClient::class);

/** @var MessagesClient $messages */
$messages = $http->messages();
$payload = new StoreMessagePayload(
    message: new MessageStoreData(
        userId: 12345,
        content: 'Текст ответа',
        caseId: 98765,
        attachments: [
            new AttachmentData(
                name: 'screenshot.png',
                mimeType: 'image/png',
                content: '...base64-encoded-file...',
            ),
        ],
        attachmentUrls: [
            'https://example.com/files/screenshot.png',
        ],
    )
);
$response = $messages->store($payload);
$message = $response->message; // MessageData
```

---

## Update Message (обновление сообщения)

**Payload:** `Palach\Omnidesk\UseCases\V1\UpdateMessage\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\UpdateMessage\Response` (поле `message` — `MessageData`).

**MessageUpdateData** (поле `message` в Payload):

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| message_id | int | да | ID сообщения |
| content | string | да | Новый текст |
| case_id | int | нет* | ID обращения |
| case_number | string | нет* | Номер обращения |

\* Одно из: `case_id` или `case_number`.

Пример:

```php
use Palach\Omnidesk\Facade\HttpClient;
use Palach\Omnidesk\Clients\MessagesClient;
use Palach\Omnidesk\UseCases\V1\UpdateMessage\MessageUpdateData;
use Palach\Omnidesk\UseCases\V1\UpdateMessage\Payload as UpdateMessagePayload;

/** @var HttpClient $http */
$http = app(HttpClient::class);

/** @var MessagesClient $messages */
$messages = $http->messages();
$payload = new UpdateMessagePayload(
    message: new MessageUpdateData(
        messageId: 111222,
        content: 'Обновлённый текст',
        caseId: 98765,
    )
);
$response = $messages->update($payload);
$message = $response->message;
```

---

## Rate Message (оценка сообщения)

**Payload:** `Palach\Omnidesk\UseCases\V1\RateMessage\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\RateMessage\Response` (поле `message` — `MessageData`).

**Поля Payload:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| case_id | int | да | ID обращения |
| message_id | int | да | ID сообщения |
| rate | RateMessageData | да | Данные оценки |

**RateMessageData** (поле `rate` в Payload):

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| rating | string | да | Значение оценки (например, "positive", "negative") |
| rating_comment | string|Optional | нет | Опциональный комментарий к оценке |

Пример:

```php
use Palach\Omnidesk\Facade\HttpClient;
use Palach\Omnidesk\Clients\MessagesClient;
use Palach\Omnidesk\UseCases\V1\RateMessage\RateMessageData;
use Palach\Omnidesk\UseCases\V1\RateMessage\Payload as RateMessagePayload;

/** @var HttpClient $http */
$http = app(HttpClient::class);

/** @var MessagesClient $messages */
$messages = $http->messages();
$payload = new RateMessagePayload(
    caseId: 98765,
    messageId: 111222,
    rate: new RateMessageData(
        rating: 'positive',
        ratingComment: 'Отличная поддержка!',
    )
);
$response = $messages->rate($payload);
$message = $response->message; // MessageData
```

---

## Rate Case (оценка обращения)

**Payload:** `Palach\Omnidesk\UseCases\V1\RateCase\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\RateCase\Response` (поле `case` — `CaseData`).

**Поля Payload:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| case_id | int | да | ID обращения |
| rate | RateData | да | Данные оценки |

**RateData** (поле `rate` в Payload):

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| rating | string | да | Значение оценки (например, "positive", "negative") |
| rating_comment | string|Optional | нет | Опциональный комментарий к оценке |
| rated_staff_id | int|Optional | нет | Опциональный ID оцененного сотрудника |

Пример:

```php
use Palach\Omnidesk\Facade\HttpClient;
use Palach\Omnidesk\Clients\CasesClient;
use Palach\Omnidesk\UseCases\V1\RateCase\RateData;
use Palach\Omnidesk\UseCases\V1\RateCase\Payload as RateCasePayload;

/** @var HttpClient $http */
$http = app(HttpClient::class);

/** @var CasesClient $cases */
$cases = $http->cases();
$payload = new RateCasePayload(
    caseId: 98765,
    rate: new RateData(
        rating: 'positive',
        ratingComment: 'Отличная поддержка!',
        ratedStaffId: 12345,
    )
);
$response = $cases->rate($payload);
$case = $response->case; // CaseData
```

---

## DTO ответов

- **CaseData** (`Omnidesk\DTO\CaseData`) — структура обращения из ответов API. Содержит опциональное поле `$attachments` с массивом объектов `FileData`.
- **MessageData** (`Omnidesk\DTO\MessageData`) — структура сообщения из ответов API. Содержит опциональное поле `$attachments` с массивом объектов `FileData`.
- **AttachmentData** (`Omnidesk\DTO\AttachmentData`) — DTO для исходящих вложений в payload Store Case/Store Message: `name`, `mimeType`, `content` (двоичное тело файла в base64).
- **FileData** (`Omnidesk\DTO\FileData`) — DTO вложения в ответах: `fileId`, `fileName`, `fileSize`, `mimeType`, `url`.

Поля соответствуют ответам Omnidesk API и маппятся через Spatie Laravel Data (snake_case → camelCase).

## Эндпоинты API Omnidesk

Клиент использует следующие пути относительно `host`:

- `POST /api/cases.json` — создание обращения.
- `GET /api/cases.json` — список обращений (query-параметры из `FetchCaseListPayload::toQuery()`).
- `POST /api/cases/{caseId}/rate.json` — оценка обращения.
- `POST /api/cases/{caseIdOrNumber}/messages.json` — создание сообщения.
- `POST /api/cases/{caseIdOrNumber}/messages/{messageId}.json` — обновление сообщения.
- `POST /api/cases/{caseId}/messages/{messageId}/rate.json` — оценка сообщения.

`caseIdOrNumber` — либо `case_id`, либо `case_number` из соответствующего Payload (внутри клиента выбирается одно значение).
