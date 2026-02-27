# API Omnidesk (клиенты и Use Cases)

Пакет предоставляет основной класс `Palach\Omnidesk\Omnidesk` для работы с API Omnidesk, транспортный слой и типизированные use cases для типовых операций.

## Omnidesk и клиенты

Класс `Palach\Omnidesk\Omnidesk` зарегистрирован в контейнере как синглтон и использует конфигурацию (host, email, api_key) из `config/omnidesk.php`.  
Вы можете получить к нему доступ через удобный фасад `Palach\Omnidesk\Facades\Omnidesk`.

Основной класс предоставляет доступ к четырем типизированным клиентам:

- `Palach\Omnidesk\Clients\CasesClient` — операции с обращениями (cases)
- `Palach\Omnidesk\Clients\FiltersClient` — операции с фильтрами
- `Palach\Omnidesk\Clients\MessagesClient` — операции с сообщениями
- `Palach\Omnidesk\Clients\NotesClient` — операции с заметками

Использование в коде (внедрение через конструктор или `app()`):

```php
// Рекомендуется: Использование фасада Omnidesk
use Palach\Omnidesk\Facades\Omnidesk;

/** @var CasesClient $cases */
$cases = Omnidesk::cases();

/** @var FiltersClient $filters */
$filters = Omnidesk::filters();

/** @var MessagesClient $messages */
$messages = Omnidesk::messages();

/** @var NotesClient $notes */
$notes = Omnidesk::notes();

// Альтернативно: Прямое внедрение класса
use Palach\Omnidesk\Omnidesk;

/** @var Omnidesk $omnidesk */
$omnidesk = app(Omnidesk::class);
$cases = $omnidesk->cases();
$filters = $omnidesk->filters();
$messages = $omnidesk->messages();
$notes = $omnidesk->notes();
```

### Транспорт и аутентификация

Внутри оба клиента используют `Palach\Omnidesk\Transport\OmnideskTransport`, который отправляет запросы с HTTP Basic Auth (`email` и `api_key` из конфига) и заголовком `Accept: application/json`.  
При ошибках сети или неожиданном формате ответа методы выбрасывают исключения (`RequestException`, `ConnectionException`, `UnexpectedResponseException`).

### Методы

- **`$casesClient->store(StoreCasePayload $payload): StoreCaseResponse`** — создание обращения (case).
- **`$casesClient->fetchList(FetchCaseListPayload $payload): FetchCaseListResponse`** — список обращений с пагинацией и фильтрами.
- **`$casesClient->rate(RateCasePayload $payload): RateCaseResponse`** — оценка обращения.
- **`$casesClient->trashCase(TrashCasePayload $payload): TrashCaseResponse`** — перемещение обращения в корзину.
- **`$casesClient->trashBulk(TrashCaseBulkPayload $payload): TrashCaseBulkResponse`** — перемещение нескольких обращений в корзину.
- **`$casesClient->restoreCase(RestoreCasePayload $payload): RestoreCaseResponse`** — восстановление обращения из корзины.
- **`$casesClient->restoreBulk(RestoreCaseBulkPayload $payload): RestoreCaseBulkResponse`** — восстановление нескольких обращений из корзины.
- **`$filtersClient->fetchList(FetchFilterListPayload $payload): FetchFilterListResponse`** — получение списка фильтров для аутентифицированного сотрудника.
- **`$messagesClient->store(StoreMessagePayload $payload): StoreMessageResponse`** — создание сообщения в обращении.
- **`$messagesClient->fetchMessages(FetchCaseMessagesPayload $payload): FetchCaseMessagesResponse`** — получение сообщений для конкретного обращения с пагинацией и сортировкой.
- **`$messagesClient->update(UpdateMessagePayload $payload): UpdateMessageResponse`** — обновление сообщения.
- **`$messagesClient->rate(RateMessagePayload $payload): RateMessageResponse`** — оценка сообщения.
- **`$messagesClient->deleteMessage(DeleteMessagePayload $payload): DeleteMessageResponse`** — удаление сообщения.
- **`$notesClient->deleteNote(DeleteNotePayload $payload): void`** — удаление заметки.

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
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\CasesClient;
use Palach\Omnidesk\UseCases\V1\StoreCase\CaseStoreData;
use Palach\Omnidesk\UseCases\V1\StoreCase\Payload as StoreCasePayload;
use Palach\Omnidesk\DTO\AttachmentData;

/** @var CasesClient $cases */
$cases = Omnidesk::cases();
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
use Palach\Omnidesk\Clients\CasesClient;use Palach\Omnidesk\Omnidesk;use Palach\Omnidesk\UseCases\V1\FetchCaseList\Payload as FetchCaseListPayload;

/** @var Omnidesk $http */
$http = app(Omnidesk::class);

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

## Fetch Filter List (получение списка фильтров)

**Payload:** `Palach\Omnidesk\UseCases\V1\FetchFilterList\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\FetchFilterList\Response` (поля: `filters` — коллекция `FilterData`, `totalCount` — общее количество).

Получает все фильтры для аутентифицированного сотрудника.

Параметры запроса (все опциональны):

| Поле | Тип | Ограничения | Описание |
|------|-----|-------------|----------|
| page | int\|Optional | 1–500 | Страница (по умолчанию в API Omnidesk: 1) |
| limit | int\|Optional | 1–100 | Размер страницы (по умолчанию в API: 100) |

Для GET-запроса используется метод `Payload::toQuery()`.

Пример:

```php
use Palach\Omnidesk\Clients\FiltersClient;
use Palach\Omnidesk\Omnidesk;
use Palach\Omnidesk\UseCases\V1\FetchFilterList\Payload as FetchFilterListPayload;

/** @var Omnidesk $http */
$http = app(Omnidesk::class);

/** @var FiltersClient $filters */
$filters = $http->filters();
$payload = new FetchFilterListPayload(
    page: 1,
    limit: 20,
);
$response = $filters->fetchList($payload);
$filters = $response->filters;
$totalCount = $response->totalCount;

// Перебор фильтров
foreach ($filters as $filter) {
    echo "ID фильтра: " . $filter->filterId . "\n";
    echo "Название фильтра: " . $filter->filterName . "\n";
    echo "Выбран: " . ($filter->isSelected ? 'Да' : 'Нет') . "\n";
    echo "Пользовательский: " . ($filter->isCustom ? 'Да' : 'Нет') . "\n";
}
```

**Свойства FilterData:**

| Поле | Тип | Описание |
|------|-----|----------|
| filterId | int\|null | Идентификатор фильтра (числовой ID или null) |
| filterName | string | Название фильтра |
| isSelected | bool | Выбран ли фильтр в данный момент |
| isCustom | bool | Является ли этот фильтр пользовательским |

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
use Palach\Omnidesk\Clients\MessagesClient;use Palach\Omnidesk\DTO\AttachmentData;use Palach\Omnidesk\Omnidesk;use Palach\Omnidesk\UseCases\V1\StoreMessage\MessageStoreData;use Palach\Omnidesk\UseCases\V1\StoreMessage\Payload as StoreMessagePayload;

/** @var Omnidesk $http */
$http = app(Omnidesk::class);

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

## Fetch Case Messages (получение сообщений обращения)

**Payload:** `Palach\Omnidesk\UseCases\V1\FetchCaseMessages\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\FetchCaseMessages\Response` (поля: `messages` — коллекция `MessageData`, `totalCount` — общее количество).

Получает сообщения для конкретного обращения с опциями пагинации и сортировки.

Параметры запроса:

| Поле | Тип | Ограничения | Описание |
|------|-----|-------------|----------|
| case_id | int | да | ID обращения (обязательный) |
| page | int\|Optional | 1+ | Номер страницы (по умолчанию в API Omnidesk: показывает последние 100 сообщений, если нет page/limit) |
| limit | int\|Optional | 1–100 | Сообщений на странице (по умолчанию в API: 100) |
| order | string\|Optional | "asc", "desc" | Порядок сортировки (по умолчанию в API: "asc" - от старых к новым) |

Для GET-запроса используется метод `Payload::toQuery()`.

Пример:

```php
use Palach\Omnidesk\Clients\MessagesClient;
use Palach\Omnidesk\Omnidesk;
use Palach\Omnidesk\UseCases\V1\FetchCaseMessages\Payload as FetchCaseMessagesPayload;

/** @var Omnidesk $http */
$http = app(Omnidesk::class);

/** @var MessagesClient $messages */
$messages = $http->messages();
$payload = new FetchCaseMessagesPayload(
    caseId: 2000,
    page: 1,
    limit: 50,
    order: 'asc',
);
$response = $messages->fetchMessages($payload);
$messages = $response->messages;
$totalCount = $response->totalCount;

// Перебор сообщений
foreach ($messages as $message) {
    echo "ID сообщения: " . $message->messageId . "\n";
    echo "Содержимое: " . $message->content . "\n";
    echo "Создано: " . $message->createdAt . "\n";
}
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
use Palach\Omnidesk\Clients\MessagesClient;use Palach\Omnidesk\Omnidesk;use Palach\Omnidesk\UseCases\V1\UpdateMessage\MessageUpdateData;use Palach\Omnidesk\UseCases\V1\UpdateMessage\Payload as UpdateMessagePayload;

/** @var Omnidesk $http */
$http = app(Omnidesk::class);

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
use Palach\Omnidesk\Clients\MessagesClient;use Palach\Omnidesk\Omnidesk;use Palach\Omnidesk\UseCases\V1\RateMessage\Payload as RateMessagePayload;use Palach\Omnidesk\UseCases\V1\RateMessage\RateMessageData;

/** @var Omnidesk $http */
$http = app(Omnidesk::class);

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

## Delete Message (удаление сообщения)

**Payload:** `Palach\Omnidesk\UseCases\V1\DeleteMessage\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\DeleteMessage\Response` (поле `success` — boolean).

**Поля Payload:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| case_id | int | да | ID обращения |
| message_id | int | да | ID сообщения |

Пример:

```php
use Palach\Omnidesk\Clients\MessagesClient;use Palach\Omnidesk\Omnidesk;use Palach\Omnidesk\UseCases\V1\DeleteMessage\Payload as DeleteMessagePayload;

/** @var Omnidesk $http */
$http = app(Omnidesk::class);

/** @var MessagesClient $messages */
$messages = $http->messages();
$payload = new DeleteMessagePayload(
    caseId: 98765,
    messageId: 111222,
);
$response = $messages->deleteMessage($payload);
$success = $response->success; // true
```

---

## Delete Note (удаление заметки)

**Payload:** `Palach\Omnidesk\UseCases\V1\DeleteNote\Payload`  
**Response:** void (без тела ответа).

**Поля Payload:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| case_id | int | да | ID обращения |
| message_id | int | да | ID сообщения |

Пример:

```php
use Palach\Omnidesk\Clients\NotesClient;use Palach\Omnidesk\Omnidesk;use Palach\Omnidesk\UseCases\V1\DeleteNote\Payload as DeleteNotePayload;

/** @var Omnidesk $http */
$http = app(Omnidesk::class);

/** @var NotesClient $notes */
$notes = $http->notes();
$payload = new DeleteNotePayload(
    caseId: 98765,
    messageId: 111222,
);
$notes->deleteNote($payload);
```

---

## Trash Case (перемещение обращения в корзину)

**Payload:** `Palach\Omnidesk\UseCases\V1\TrashCase\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\TrashCase\Response` (поле `case` — `CaseData`).

**Поля Payload:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| case_id | int | да | ID обращения |

Пример:

```php
use Palach\Omnidesk\Clients\CasesClient;use Palach\Omnidesk\Omnidesk;use Palach\Omnidesk\UseCases\V1\TrashCase\Payload as TrashCasePayload;

/** @var Omnidesk $http */
$http = app(Omnidesk::class);

/** @var CasesClient $cases */
$cases = $http->cases();
$payload = new TrashCasePayload(
    caseId: 98765,
);
$response = $cases->trashCase($payload);
$case = $response->case; // CaseData
```

---

## Trash Case Bulk (перемещение нескольких обращений в корзину)

**Payload:** `Palach\Omnidesk\UseCases\V1\TrashCase\BulkPayload`  
**Response:** `Palach\Omnidesk\UseCases\V1\TrashCase\BulkResponse` (поле `caseSuccessId` — массив успешно обработанных ID обращений).

**Поля Payload:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| case_ids | int[] | да | Массив ID обращений (максимум 10 за запрос) |

Пример:

```php
use Palach\Omnidesk\Clients\CasesClient;use Palach\Omnidesk\Omnidesk;use Palach\Omnidesk\UseCases\V1\TrashCase\BulkPayload as TrashCaseBulkPayload;

/** @var Omnidesk $http */
$http = app(Omnidesk::class);

/** @var CasesClient $cases */
$cases = $http->cases();
$payload = new TrashCaseBulkPayload(
    caseIds: [98765, 98766, 98767],
);
$response = $cases->trashBulk($payload);
$successIds = $response->caseSuccessId; // массив успешных ID обращений
```

---

## Restore Case (восстановление обращения из корзины)

**Payload:** `Palach\Omnidesk\UseCases\V1\RestoreCase\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\RestoreCase\Response` (поле `case` — `CaseData`).

**Поля Payload:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| case_id | int | да | ID обращения |

Пример:

```php
use Palach\Omnidesk\Clients\CasesClient;use Palach\Omnidesk\Omnidesk;use Palach\Omnidesk\UseCases\V1\RestoreCase\Payload as RestoreCasePayload;

/** @var Omnidesk $http */
$http = app(Omnidesk::class);

/** @var CasesClient $cases */
$cases = $http->cases();
$payload = new RestoreCasePayload(
    caseId: 98765,
);
$response = $cases->restoreCase($payload);
$case = $response->case; // CaseData
```

---

## Restore Case Bulk (восстановление нескольких обращений из корзины)

**Payload:** `Palach\Omnidesk\UseCases\V1\RestoreCase\BulkPayload`  
**Response:** `Palach\Omnidesk\UseCases\V1\RestoreCase\BulkResponse` (поле `caseSuccessId` — массив успешно обработанных ID обращений).

**Поля Payload:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| case_ids | int[] | да | Массив ID обращений (максимум 10 за запрос) |

Пример:

```php
use Palach\Omnidesk\Clients\CasesClient;use Palach\Omnidesk\Omnidesk;use Palach\Omnidesk\UseCases\V1\RestoreCase\BulkPayload as RestoreCaseBulkPayload;

/** @var Omnidesk $http */
$http = app(Omnidesk::class);

/** @var CasesClient $cases */
$cases = $http->cases();
$payload = new RestoreCaseBulkPayload(
    caseIds: [98765, 98766, 98767],
);
$response = $cases->restoreBulk($payload);
$successIds = $response->caseSuccessId; // массив успешных ID обращений
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
use Palach\Omnidesk\Clients\CasesClient;use Palach\Omnidesk\Omnidesk;use Palach\Omnidesk\UseCases\V1\RateCase\Payload as RateCasePayload;use Palach\Omnidesk\UseCases\V1\RateCase\RateData;

/** @var Omnidesk $http */
$http = app(Omnidesk::class);

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
- `GET /api/cases/{caseId}/messages.json` — получение сообщений обращения (query-параметры из `FetchCaseMessagesPayload::toQuery()`).
- `POST /api/cases/{caseIdOrNumber}/messages/{messageId}.json` — обновление сообщения.
- `POST /api/cases/{caseId}/messages/{messageId}/rate.json` — оценка сообщения.
- `PUT /api/cases/{caseId}/trash.json` — перемещение обращения в корзину.
- `PUT /api/cases/{caseIds}/trash.json` — перемещение нескольких обращений в корзину.
- `PUT /api/cases/{caseId}/restore.json` — восстановление обращения из корзины.
- `PUT /api/cases/{caseIds}/restore.json` — восстановление нескольких обращений из корзины.
- `DELETE /api/cases/{caseId}/note/{messageId}.json` — удаление заметки.

`caseIdOrNumber` — либо `case_id`, либо `case_number` из соответствующего Payload (внутри клиента выбирается одно значение).
