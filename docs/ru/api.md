# API Omnidesk (HttpClient и Use Cases)

Пакет предоставляет HTTP-клиент для работы с API Omnidesk и типизированные use cases для типовых операций.

## HttpClient

Класс `Omnidesk\Services\HttpClient` зарегистрирован в контейнере как синглтон и принимает конфигурацию (host, email, api_key) из `config/omnidesk.php`.

Использование в коде (внедрение через конструктор или `app(HttpClient::class)`):

```php
use Omnidesk\Services\HttpClient;
```

### Аутентификация

Все запросы выполняются с HTTP Basic Auth: `email` и `api_key` из конфига. Заголовки: `Content-Type: application/json`, `Accept: application/json`.

### Методы

- **storeCase(StoreCasePayload $payload): StoreCaseResponse** — создание обращения (case).
- **fetchCaseList(FetchCaseListPayload $payload): FetchCaseListResponse** — список обращений с пагинацией и фильтрами.
- **storeMessage(StoreMessagePayload $payload): StoreMessageResponse** — создание сообщения в обращении.
- **updateMessage(UpdateMessagePayload $payload): UpdateMessageResponse** — обновление сообщения.

При ошибках сети или неожиданном формате ответа методы выбрасывают исключения (`RequestException`, `ConnectionException`, `UnexpectedResponseException`).

---

## Store Case (создание обращения)

**Payload:** `Omnidesk\UseCases\V1\StoreCase\Payload`  
**Response:** `Omnidesk\UseCases\V1\StoreCase\Response` (содержит `CaseData`).

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
use Omnidesk\Services\HttpClient;
use Omnidesk\UseCases\V1\StoreCase\CaseStoreData;
use Omnidesk\UseCases\V1\StoreCase\Payload as StoreCasePayload;
use Omnidesk\DTO\AttachmentData;

$client = app(HttpClient::class);
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
$response = $client->storeCase($payload);
$case = $response->case; // CaseData
```

---

## Fetch Case List (список обращений)

**Payload:** `Omnidesk\UseCases\V1\FetchCaseList\Payload`  
**Response:** `Omnidesk\UseCases\V1\FetchCaseList\Response` (поля: `cases` — коллекция `CaseData`, `total` — общее количество).

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
use Omnidesk\Services\HttpClient;
use Omnidesk\UseCases\V1\FetchCaseList\Payload as FetchCaseListPayload;

$client = app(HttpClient::class);
$payload = new FetchCaseListPayload(
    page: 1,
    limit: 20,
    status: ['open'],
);
$response = $client->fetchCaseList($payload);
$cases = $response->cases;
$total = $response->total;
```

---

## Store Message (создание сообщения)

**Payload:** `Omnidesk\UseCases\V1\StoreMessage\Payload`  
**Response:** `Omnidesk\UseCases\V1\StoreMessage\Response` (поле `message` — `MessageData`).

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
use Omnidesk\Services\HttpClient;
use Omnidesk\UseCases\V1\StoreMessage\MessageStoreData;
use Omnidesk\UseCases\V1\StoreMessage\Payload as StoreMessagePayload;
use Omnidesk\DTO\AttachmentData;

$client = app(HttpClient::class);
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
$response = $client->storeMessage($payload);
$message = $response->message; // MessageData
```

---

## Update Message (обновление сообщения)

**Payload:** `Omnidesk\UseCases\V1\UpdateMessage\Payload`  
**Response:** `Omnidesk\UseCases\V1\UpdateMessage\Response` (поле `message` — `MessageData`).

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
use Omnidesk\Services\HttpClient;
use Omnidesk\UseCases\V1\UpdateMessage\MessageUpdateData;
use Omnidesk\UseCases\V1\UpdateMessage\Payload as UpdateMessagePayload;

$client = app(HttpClient::class);
$payload = new UpdateMessagePayload(
    message: new MessageUpdateData(
        messageId: 111222,
        content: 'Обновлённый текст',
        caseId: 98765,
    )
);
$response = $client->updateMessage($payload);
$message = $response->message;
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
- `POST /api/cases/{caseIdOrNumber}/messages.json` — создание сообщения.
- `POST /api/cases/{caseIdOrNumber}/messages/{messageId}.json` — обновление сообщения.

`caseIdOrNumber` — либо `case_id`, либо `case_number` из соответствующего Payload (внутри клиента выбирается одно значение).
