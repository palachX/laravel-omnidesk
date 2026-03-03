# API Omnidesk (клиенты и Use Cases)

Пакет предоставляет основной класс `Palach\Omnidesk\Omnidesk` для работы с API Omnidesk, транспортный слой и типизированные use cases для типовых операций.

## Omnidesk и клиенты

Класс `Palach\Omnidesk\Omnidesk` зарегистрирован в контейнере как синглтон и использует конфигурацию (host, email, api_key) из `config/omnidesk.php`.  
Вы можете получить к нему доступ через удобный фасад `Palach\Omnidesk\Facades\Omnidesk`.

Основной класс предоставляет доступ к шести типизированным клиентам:

- `Palach\Omnidesk\Clients\CasesClient` — операции с обращениями (cases)
- `Palach\Omnidesk\Clients\FiltersClient` — операции с фильтрами
- `Palach\Omnidesk\Clients\LabelsClient` — операции с метками
- `Palach\Omnidesk\Clients\MessagesClient` — операции с сообщениями
- `Palach\Omnidesk\Clients\NotesClient` — операции с заметками
- `Palach\Omnidesk\Clients\UsersClient` — операции с пользователями

Использование в коде (внедрение через конструктор или `app()`):

```php
// Рекомендуется: Использование фасада Omnidesk
use Palach\Omnidesk\Facades\Omnidesk;

/** @var CasesClient $cases */
$cases = Omnidesk::cases();

/** @var FiltersClient $filters */
$filters = Omnidesk::filters();

/** @var LabelsClient $labels */
$labels = Omnidesk::labels();

/** @var MessagesClient $messages */
$messages = Omnidesk::messages();

/** @var NotesClient $notes */
$notes = Omnidesk::notes();

/** @var UsersClient $users */
$users = Omnidesk::users();

// Альтернативно: Прямое внедрение класса
use Palach\Omnidesk\Omnidesk;

/** @var Omnidesk $omnidesk */
$omnidesk = app(Omnidesk::class);
$cases = $omnidesk->cases();
$filters = $omnidesk->filters();
$labels = $omnidesk->labels();
$messages = $omnidesk->messages();
$notes = $omnidesk->notes();
$users = $omnidesk->users();
```

### Транспорт и аутентификация

Внутри оба клиента используют `Palach\Omnidesk\Transport\OmnideskTransport`, который отправляет запросы с HTTP Basic Auth (`email` и `api_key` из конфига) и заголовком `Accept: application/json`.  
При ошибках сети или неожиданном формате ответа методы выбрасывают исключения (`RequestException`, `ConnectionException`, `UnexpectedResponseException`).

### Методы

#### CasesClient
- **`$casesClient->store(StoreCasePayload $payload): StoreCaseResponse`** — создание обращения (case).
- **`$casesClient->fetchList(FetchCaseListPayload $payload): FetchCaseListResponse`** — список обращений с пагинацией и фильтрами.
- **`$casesClient->rate(RateCasePayload $payload): RateCaseResponse`** — оценка обращения.
- **`$casesClient->trashCase(TrashCasePayload $payload): TrashCaseResponse`** — перемещение обращения в корзину.
- **`$casesClient->trashBulk(TrashCaseBulkPayload $payload): TrashCaseBulkResponse`** — перемещение нескольких обращений в корзину.
- **`$casesClient->restoreCase(RestoreCasePayload $payload): RestoreCaseResponse`** — восстановление обращения из корзины.
- **`$casesClient->restoreBulk(RestoreCaseBulkPayload $payload): RestoreCaseBulkResponse`** — восстановление нескольких обращений из корзины.
- **`$casesClient->spamCase(SpamCasePayload $payload): SpamCaseResponse`** — пометить обращение как спам.
- **`$casesClient->spamBulk(SpamCaseBulkPayload $payload): SpamCaseBulkResponse`** — пометить несколько обращений как спам.
- **`$casesClient->deleteCase(DeleteCasePayload $payload): DeleteCaseResponse`** — полное удаление обращения.
- **`$casesClient->deleteBulk(DeleteCaseBulkPayload $payload): DeleteCaseBulkResponse`** — полное удаление нескольких обращений.
- **`$casesClient->updateIdea(UpdateIdeaPayload $payload): UpdateIdeaResponse`** — редактирование предложения.
- **`$casesClient->updateIdeaOfficialResponse(UpdateIdeaOfficialResponsePayload $payload): UpdateIdeaOfficialResponseResponse`** — обновление официального ответа предложения.
- **`$casesClient->deleteIdeaOfficialResponse(DeleteIdeaOfficialResponsePayload $payload): void`** — удаление официального ответа предложения.
- **`$filtersClient->fetchList(FetchFilterListPayload $payload): FetchFilterListResponse`** — получение списка фильтров для аутентифицированного сотрудника.
- **`$labelsClient->store(StoreLabelPayload $payload): StoreLabelResponse`** — создание метки.
- **`$labelsClient->fetchList(FetchLabelListPayload $payload): FetchLabelListResponse`** — получение списка меток с пагинацией.
- **`$labelsClient->updateLabel(UpdateLabelPayload $payload): UpdateLabelResponse`** — редактирование метки.
- **`$labelsClient->deleteLabel(DeleteLabelPayload $payload): void`** — удаление метки.
- **`$messagesClient->store(StoreMessagePayload $payload): StoreMessageResponse`** — создание сообщения в обращении.
- **`$messagesClient->fetchMessages(FetchCaseMessagesPayload $payload): FetchCaseMessagesResponse`** — получение сообщений для конкретного обращения с пагинацией и сортировкой.
- **`$messagesClient->update(UpdateMessagePayload $payload): UpdateMessageResponse`** — обновление сообщения.
- **`$messagesClient->rate(RateMessagePayload $payload): RateMessageResponse`** — оценка сообщения.
- **`$messagesClient->deleteMessage(DeleteMessagePayload $payload): DeleteMessageResponse`** — удаление сообщения.
- **`$notesClient->deleteNote(DeleteNotePayload $payload): void`** — удаление заметки.
- **`$usersClient->fetch(FetchUserPayload $payload): FetchUserResponse`** — получение пользователя по ID.
- **`$usersClient->store(StoreUserPayload $payload): StoreUserResponse`** — создание пользователя.
- **`$usersClient->update(int $userId, UpdateUserPayload $payload): UpdateUserResponse`** — редактирование пользователя.
- **`$usersClient->fetchList(FetchUserListPayload $payload): FetchUserListResponse`** — получение списка пользователей с пагинацией и фильтрами.
- **`$usersClient->fetchUserIdentification(FetchUserIdentificationPayload $payload): FetchUserIdentificationResponse`** — получение кода идентификации пользователя.
- **`$usersClient->linkUser(int $userId, LinkUserPayload $payload): LinkUserResponse`** — связывание профилей пользователей.

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

## Store Label (создание метки)

**Payload:** `Palach\Omnidesk\UseCases\V1\StoreLabel\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\StoreLabel\Response` (содержит `LabelData`).

**LabelStoreData** (поле `label` в Payload):

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| label_title | string | да | Название метки |

Пример:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\LabelsClient;
use Palach\Omnidesk\UseCases\V1\StoreLabel\LabelStoreData;
use Palach\Omnidesk\UseCases\V1\StoreLabel\Payload as StoreLabelPayload;

/** @var LabelsClient $labels */
$labels = Omnidesk::labels();
$payload = new StoreLabelPayload(
    label: new LabelStoreData(
        labelTitle: 'Test title'
    )
);
$response = $labels->store($payload);
$label = $response->label; // LabelData
```

---

## Fetch Label List (получение списка меток)

**Payload:** `Palach\Omnidesk\UseCases\V1\FetchLabelList\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\FetchLabelList\Response` (поля: `labels` — коллекция `LabelData`, `total` — общее количество).

Получение списка меток.

Параметры запроса:

| Поле | Тип | Ограничения | Описание |
|------|-----|-------------|----------|
| page | int | 1–500 | Номер страницы (по умолчанию: 1) |
| limit | int | 1–100 | Лимит меток на странице (по умолчанию: 100) |

Для GET-запроса используется метод `Payload::toQuery()`.

Пример:

```php
use Palach\Omnidesk\Clients\LabelsClient;
use Palach\Omnidesk\Omnidesk;
use Palach\Omnidesk\UseCases\V1\FetchLabelList\Payload as FetchLabelListPayload;

/** @var Omnidesk $http */
$http = app(Omnidesk::class);

/** @var LabelsClient $labels */
$labels = $http->labels();
$payload = new FetchLabelListPayload(
    page: 1,
    limit: 20,
);
// Или с параметрами по умолчанию:
// $payload = new FetchLabelListPayload();
$response = $labels->fetchList($payload);
$labels = $response->labels;
$total = $response->total;

// Перебор меток
foreach ($labels as $label) {
    echo "ID метки: " . $label->labelId . "\n";
    echo "Название метки: " . $label->labelTitle . "\n";
}
```

---

## Update Label (редактирование метки)

**Payload:** `Palach\Omnidesk\UseCases\V1\UpdateLabel\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\UpdateLabel\Response` (содержит `LabelData`).

**LabelUpdateData** (поле `label` в Payload):

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| label_title | string | да | Название метки |

**Поля Payload:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| label_id | int | да | ID метки |
| label | LabelUpdateData | да | Данные для обновления метки |

Пример:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\LabelsClient;
use Palach\Omnidesk\UseCases\V1\UpdateLabel\LabelUpdateData;
use Palach\Omnidesk\UseCases\V1\UpdateLabel\Payload as UpdateLabelPayload;

/** @var LabelsClient $labels */
$labels = Omnidesk::labels();
$payload = new UpdateLabelPayload(
    labelId: 200,
    label: new LabelUpdateData(
        labelTitle: 'Новое название метки'
    )
);
$response = $labels->updateLabel($payload);
$label = $response->label; // LabelData
```

---

## Delete Label (удаление метки)

**Payload:** `Palach\Omnidesk\UseCases\V1\DeleteLabel\Payload`  
**Response:** void (без тела ответа).

**Поля Payload:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| id | int | да | ID метки |

Пример:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\LabelsClient;
use Palach\Omnidesk\UseCases\V1\DeleteLabel\Payload as DeleteLabelPayload;

/** @var LabelsClient $labels */
$labels = Omnidesk::labels();
$payload = new DeleteLabelPayload(
    id: 123,
);
$labels->deleteLabel($payload);
```

---

## Store User (создание пользователя)

**Payload:** `Palach\Omnidesk\UseCases\V1\StoreUser\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\StoreUser\Response` (содержит `UserData`).

**UserStoreData** (поле `user` в Payload):

**Обязательные поля (одно из):**
- `user_email` — email пользователя
- `user_phone` — телефон пользователя  
- `user_whatsapp_phone` — телефон для WhatsApp
- `user_vkontakte` — ID во ВКонтакте
- `user_odnoklassniki` — ID в Одноклассниках
- `user_facebook` — ID в Facebook
- `user_instagram` — username в Instagram
- `user_telegram` — ID в Telegram
- `user_telegram_data` — телефон или username для Telegram
- `user_viber` — ID в Viber
- `user_skype` — ID в Skype
- `user_line` — ID в Line
- `user_slack` — ID в Slack
- `user_mattermost` — ID в Mattermost
- `user_avito` — ID в Avito
- `user_custom_id` — ID для кастомного канала

**Опциональные поля:**
- `user_custom_channel` — ID кастомного канала (обязательно при использовании `user_custom_id`)
- `user_full_name` — полное имя пользователя
- `company_name` — имя компании
- `company_position` — должность
- `user_note` — заметка по пользователю
- `language_id` — язык пользователя
- `custom_fields` — массив кастомных полей

**UserData** (поле `user` в Response):
- `user_id` — ID пользователя
- Все поля из запроса плюс служебные поля (`created_at`, `updated_at`, `confirmed`, `active`, `deleted`, `password`, `type`, `thumbnail`, `linked_users`)

Пример:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\UsersClient;
use Palach\Omnidesk\UseCases\V1\StoreUser\UserStoreData;
use Palach\Omnidesk\UseCases\V1\StoreUser\Payload as StoreUserPayload;

/** @var UsersClient $users */
$users = Omnidesk::users();

$payload = new StoreUserPayload(
    user: new UserStoreData(
        userEmail: 'user@domain.ru',
        userFullName: 'John Doe',
        companyName: 'Example Corp',
        companyPosition: 'Developer',
        userNote: 'VIP customer',
        languageId: 1,
        customFields: [
            'cf_20' => 'some data',
            'cf_23' => true,
        ]
    )
);

$response = $users->store($payload);
$user = $response->user; // UserData
```

---

## Fetch User List (получение списка пользователей)

**Payload:** `Palach\Omnidesk\UseCases\V1\FetchUserList\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\FetchUserList\Response` (поля: `users` — коллекция `UserData`, `total` — общее количество).

Получение списка пользователей с пагинацией и фильтрами.

Параметры запроса (все опциональны):

| Поле | Тип | Ограничения | Описание |
|------|-----|-------------|----------|
| page | int\|Optional | 1–500 | Номер страницы (по умолчанию в API Omnidesk: 1) |
| limit | int\|Optional | 1–100 | Размер страницы (по умолчанию в API: 100) |
| user_email | string\|Optional | — | Поиск пользователей по email-адресу (не менее 3-х символов) |
| user_phone | string\|Optional | — | Поиск пользователей по телефону (не менее 3-х символов) |
| user_custom_id | string\|Optional | — | Поиск пользователя по кастомному id |
| user_custom_channel | string\|Optional | — | ID кастомного канала (к примеру, cch101) |
| company_id | array\|Optional | — | ID компании (выборка всех пользователей конкретной компании) |
| language_id | int\|Optional | — | язык пользователя |
| custom_fields | array\|Optional | — | Дополнительные поля данных |
| amount_of_cases | bool\|Optional | — | Количество обращений пользователя |
| from_time | string\|int\|Optional | — | Начало периода для фильтра по дате добавления пользователя |
| to_time | string\|int\|Optional | — | Конец периода для фильтра по дате добавления пользователя |
| from_updated_time | string\|int\|Optional | — | Начало периода для фильтра по дате обновления пользователя |
| to_updated_time | string\|int\|Optional | — | Конец периода для фильтра по дате обновления пользователя |
| from_last_contact_time | string\|int\|Optional | — | Начало периода для фильтра по дате последнего контакта пользователя |
| to_last_contact_time | string\|int\|Optional | — | Конец периода для фильтра по дате последнего контакта пользователя |

Для GET-запроса используется метод `Payload::toQuery()`.

Пример:

```php
use Palach\Omnidesk\Clients\UsersClient;
use Palach\Omnidesk\Omnidesk;
use Palach\Omnidesk\UseCases\V1\FetchUserList\Payload as FetchUserListPayload;

/** @var Omnidesk $http */
$http = app(Omnidesk::class);

/** @var UsersClient $users */
$users = $http->users();
$payload = new FetchUserListPayload(
    page: 1,
    limit: 20,
    userEmail: 'test@example.com',
    companyId: [123, 456],
);
// Или с параметрами по умолчанию:
// $payload = new FetchUserListPayload();
$response = $users->fetchList($payload);
$users = $response->users;
$total = $response->total;

// Перебор пользователей
foreach ($users as $user) {
    echo "ID пользователя: " . $user->userId . "\n";
    echo "Имя пользователя: " . $user->userFullName . "\n";
    echo "Email: " . $user->userEmail . "\n";
}
```

---

## Update User (редактирование пользователя)

**Payload:** `Palach\Omnidesk\UseCases\V1\UpdateUser\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\UpdateUser\Response` (содержит `UserData`).

**UserUpdateData** (поле `user` в Payload):

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| user_email | string | нет | Новый email-адрес (можно менять только пока не подтвержден) |
| user_phone | string | нет | Новый номер телефона (применимо только к пользователям типа phone) |
| user_full_name | string | нет | Полное имя |
| company_name | string | нет | Имя компании |
| company_position | string | нет | Должность |
| user_note | string | нет | Заметка по пользователю |
| language_id | int | нет | Язык пользователя |
| custom_fields | array | нет | Кастомные поля |

**Параметры метода:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| user_id | int | да | ID пользователя |
| payload | UpdateUserPayload | да | Данные для обновления |

Пример:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\UsersClient;
use Palach\Omnidesk\UseCases\V1\UpdateUser\UserUpdateData;
use Palach\Omnidesk\UseCases\V1\UpdateUser\Payload as UpdateUserPayload;

/** @var UsersClient $users */
$users = Omnidesk::users();

$payload = new UpdateUserPayload(
    user: new UserUpdateData(
        userFullName: "Измененное имя пользователя",
        languageId: 1,
        customFields: [
            'cf_20' => 'некоторые данные',
            'cf_23' => true,
        ]
    )
);

$response = $users->update(200, $payload);
$user = $response->user; // UserData
```

---

## Fetch User (получение пользователя)

**Payload:** `Palach\Omnidesk\UseCases\V1\FetchUser\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\FetchUser\Response` (содержит `UserData`).

**UserFetchData** (поле `user` в Payload):

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| user_id | int | да | ID пользователя |

Пример:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\UsersClient;
use Palach\Omnidesk\UseCases\V1\FetchUser\UserFetchData;
use Palach\Omnidesk\UseCases\V1\FetchUser\Payload as FetchUserPayload;

/** @var UsersClient $users */
$users = Omnidesk::users();

$payload = new FetchUserPayload(
    user: new UserFetchData(
        userId: 200
    )
);

$response = $users->fetch($payload);
$user = $response->user; // UserData
```

---

## Fetch User Identification (получение кода идентификации)

**Payload:** `Palach\Omnidesk\UseCases\V1\FetchUserIdentification\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\FetchUserIdentification\Response` (содержит код идентификации `code`).

**UserIdentificationData** (поле `user` в Payload):

| Поле | Тип | Обязательное | Описание |
|------|------|--------------|----------|
| user_email | string|Optional | Обязательно, если не указаны другие контактные данные. Валидный email-адрес |
| user_phone | string|Optional | Обязательно, если не указаны другие контактные данные. Валидный телефон |
| user_whatsapp_phone | string|Optional | Обязательно, если не указаны другие контактные данные. Валидный телефон, привязанный к WhatsApp |
| user_telegram_data | string|Optional | Обязательно, если не указаны другие контактные данные. Валидный телефон или username для Telegram |
| user_custom_id | string|Optional | Обязательно, если не указаны другие контактные данные. ID пользователя для кастомного канала |
| user_custom_channel | string|Optional | ID кастомного канала (например, cch101) |
| user_full_name | string|Optional | Полное имя пользователя |
| company_name | string|Optional | Название компании пользователя |
| company_position | string|Optional | Должность пользователя |
| user_note | string|Optional | Заметка по пользователю |
| language_id | int|Optional | ID языка пользователя |
| custom_fields | array|Optional | Кастомные поля |

Хотя бы одно из контактных полей (user_email, user_phone, user_whatsapp_phone, user_telegram_data, user_custom_id) должно быть указано.

Пример:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\UsersClient;
use Palach\Omnidesk\UseCases\V1\FetchUserIdentification\UserIdentificationData;
use Palach\Omnidesk\UseCases\V1\FetchUserIdentification\Payload as FetchUserIdentificationPayload;

/** @var UsersClient $users */
$users = Omnidesk::users();

$payload = new FetchUserIdentificationPayload(
    user: new UserIdentificationData(
        userFullName: 'Семёнов Алексей',
        companyName: 'ABCompany',
        userEmail: 'a.semenov@abcompany.com',
        userPhone: '+79221110000',
        userWhatsappPhone: '+79221110000',
        userCustomId: 'a.semenov',
        userCustomChannel: '481',
        customFields: [
            'cf_7264' => 'some data',
            'cf_7786' => 2,
            'cf_7486' => true,
        ]
    )
);

$response = $users->fetchUserIdentification($payload);
$code = $response->code; // string: "o_37BD49_uv"
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

## Link User (связывание профилей пользователей)

**Payload:** `Palach\Omnidesk\UseCases\V1\LinkUser\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\LinkUser\Response` (содержит `UserData`).

**Поля Payload:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| user_email | string|Optional | Email адрес пользователя для связывания |
| user_phone | string|Optional | Номер телефона пользователя для связывания |
| user_id | int|Optional | ID пользователя для связывания |

Хотя бы одно из полей (user_email, user_phone или user_id) должно быть предоставлено.

**Параметры метода:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| user_id | int | да | ID пользователя (из URL) - профиль которого будет связан |
| payload | LinkUserPayload | да | Данные для связывания |

Пример:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\UsersClient;
use Palach\Omnidesk\UseCases\V1\LinkUser\Payload as LinkUserPayload;

/** @var UsersClient $users */
$users = Omnidesk::users();

// Связывание по email
$payload = new LinkUserPayload(
    userEmail: 'user@domain.ru',
);

$response = $users->linkUser(1307386, $payload);
$user = $response->user; // UserData с обновленным массивом linked_users

// Связывание по ID пользователя
$payload = new LinkUserPayload(
    userId: 25830712,
);

$response = $users->linkUser(1307386, $payload);
$user = $response->user;
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

## Spam Case (пометить обращение как спам)

**Payload:** `Palach\Omnidesk\UseCases\V1\SpamCase\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\SpamCase\Response` (поле `case` — `CaseData`).

**Поля Payload:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| case_id | int | да | ID обращения |

Пример:

```php
use Palach\Omnidesk\Clients\CasesClient;
use Palach\Omnidesk\Omnidesk;
use Palach\Omnidesk\UseCases\V1\SpamCase\Payload as SpamCasePayload;

/** @var Omnidesk $http */
$http = app(Omnidesk::class);

/** @var CasesClient $cases */
$cases = $http->cases();
$payload = new SpamCasePayload(
    caseId: 98765,
);
$response = $cases->spamCase($payload);
$case = $response->case; // CaseData со spam: true
```

---

## Spam Case Bulk (пометить несколько обращений как спам)

**Payload:** `Palach\Omnidesk\UseCases\V1\SpamCase\BulkPayload`  
**Response:** `Palach\Omnidesk\UseCases\V1\SpamCase\BulkResponse` (поле `caseSuccessId` — массив успешно обработанных ID обращений).

**Поля Payload:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| case_ids | int[] | да | Массив ID обращений (максимум 10 за запрос) |

Пример:

```php
use Palach\Omnidesk\Clients\CasesClient;
use Palach\Omnidesk\Omnidesk;
use Palach\Omnidesk\UseCases\V1\SpamCase\BulkPayload as SpamCaseBulkPayload;

/** @var Omnidesk $http */
$http = app(Omnidesk::class);

/** @var CasesClient $cases */
$cases = $http->cases();
$payload = new SpamCaseBulkPayload(
    caseIds: [98765, 98766, 98767],
);
$response = $cases->spamBulk($payload);
$successIds = $response->caseSuccessId; // массив успешных ID обращений
```

---

## Update Idea (редактирование предложения)

**Payload:** `Palach\Omnidesk\UseCases\V1\UpdateIdea\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\UpdateIdea\Response` (содержит `CaseData`).

**IdeaUpdateData** (поле `message` в Payload):

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| content | string | нет | Содержимое предложения |
| stage | string | нет | Этап реализации (waiting, planned, in_progress, finished) |
| category_id | int | нет | ID категории |

**Поля Payload:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| case_id | int | да | ID обращения |
| message | IdeaUpdateData | да | Данные для обновления предложения |

Пример:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\CasesClient;
use Palach\Omnidesk\UseCases\V1\UpdateIdea\IdeaUpdateData;
use Palach\Omnidesk\UseCases\V1\UpdateIdea\Payload as UpdateIdeaPayload;

/** @var CasesClient $cases */
$cases = Omnidesk::cases();
$payload = new UpdateIdeaPayload(
    caseId: 123,
    message: new IdeaUpdateData(
        content: 'Новое содержимое',
        stage: 'planned',
        categoryId: 319,
    )
);
$response = $cases->updateIdea($payload);
$case = $response->case; // CaseData с обновленным предложением
```

---

## Update Idea Official Response (обновление официального ответа предложения)

**Payload:** `Palach\Omnidesk\UseCases\V1\UpdateIdeaOfficialResponse\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\UpdateIdeaOfficialResponse\Response` (содержит `CaseData`).

**IdeaOfficialResponseUpdateData** (поле `message` в Payload):

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| content | string | да | Содержание официального ответа |

**Поля Payload:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| case_id | int | да | ID обращения |
| message | IdeaOfficialResponseUpdateData | да | Данные для обновления официального ответа |

Пример:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\CasesClient;
use Palach\Omnidesk\UseCases\V1\UpdateIdeaOfficialResponse\IdeaOfficialResponseUpdateData;
use Palach\Omnidesk\UseCases\V1\UpdateIdeaOfficialResponse\Payload as UpdateIdeaOfficialResponsePayload;

/** @var CasesClient $cases */
$cases = Omnidesk::cases();
$payload = new UpdateIdeaOfficialResponsePayload(
    caseId: 123,
    message: new IdeaOfficialResponseUpdateData(
        content: 'Новый официальный ответ',
    )
);
$response = $cases->updateIdeaOfficialResponse($payload);
$case = $response->case; // CaseData с обновленным официальным ответом
```

---

## Delete Idea Official Response (удаление официального ответа предложения)

**Payload:** `Palach\Omnidesk\UseCases\V1\DeleteIdeaOfficialResponse\Payload`  
**Response:** void (без тела ответа).

**Поля Payload:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| case_id | int | да | ID обращения |

Пример:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\CasesClient;
use Palach\Omnidesk\UseCases\V1\DeleteIdeaOfficialResponse\Payload as DeleteIdeaOfficialResponsePayload;

/** @var CasesClient $cases */
$cases = Omnidesk::cases();
$payload = new DeleteIdeaOfficialResponsePayload(
    caseId: 123,
);
$cases->deleteIdeaOfficialResponse($payload);
```

---

## Delete Case (полное удаление обращения)

**Payload:** `Palach\Omnidesk\UseCases\V1\DeleteCase\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\DeleteCase\Response` (поле `case` — `CaseData`).

**Поля Payload:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| case_id | int | да | ID обращения |

Пример:

```php
use Palach\Omnidesk\Clients\CasesClient;
use Palach\Omnidesk\Omnidesk;
use Palach\Omnidesk\UseCases\V1\DeleteCase\Payload as DeleteCasePayload;

/** @var Omnidesk $http */
$http = app(Omnidesk::class);

/** @var CasesClient $cases */
$cases = $http->cases();
$payload = new DeleteCasePayload(
    caseId: 98765,
);
$response = $cases->deleteCase($payload);
$case = $response->case; // CaseData
```

---

## Delete Case Bulk (полное удаление нескольких обращений)

**Payload:** `Palach\Omnidesk\UseCases\V1\DeleteCase\BulkPayload`  
**Response:** `Palach\Omnidesk\UseCases\V1\DeleteCase\BulkResponse` (поле `caseSuccessId` — массив успешно обработанных ID обращений).

**Поля Payload:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| case_ids | int[] | да | Массив ID обращений (максимум 10 за запрос) |

Пример:

```php
use Palach\Omnidesk\Clients\CasesClient;
use Palach\Omnidesk\Omnidesk;
use Palach\Omnidesk\UseCases\V1\DeleteCase\BulkPayload as DeleteCaseBulkPayload;

/** @var Omnidesk $http */
$http = app(Omnidesk::class);

/** @var CasesClient $cases */
$cases = $http->cases();
$payload = new DeleteCaseBulkPayload(
    caseIds: [98765, 98766, 98767],
);
$response = $cases->deleteBulk($payload);
$successIds = $response->caseSuccessId; // массив успешных ID обращений
```

---

## DTO ответов

- **CaseData** (`Omnidesk\DTO\CaseData`) — структура обращения из ответов API. Содержит опциональное поле `$attachments` с массивом объектов `FileData`.
- **LabelData** (`Omnidesk\DTO\LabelData`) — структура метки из ответов API.
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
- `PUT /api/cases/{caseId}/spam.json` — пометить обращение как спам.
- `PUT /api/cases/{caseIds}/spam.json` — пометить несколько обращений как спам.
- `PUT /api/cases/{caseId}/idea.json` — редактирование предложения.
- `PUT /api/cases/{caseId}/idea_official_response.json` — обновление официального ответа предложения.
- `DELETE /api/cases/{caseId}/idea_official_response.json` — удаление официального ответа предложения.
- `DELETE /api/cases/{caseId}.json` — полное удаление обращения.
- `DELETE /api/cases/{caseIds}.json` — полное удаление нескольких обращений.
- `DELETE /api/cases/{caseId}/note/{messageId}.json` — удаление заметки.
- `POST /api/labels.json` — создание метки.
- `GET /api/labels.json` — получение списка меток.
- `PUT /api/labels/{labelId}.json` — редактирование метки.
- `DELETE /api/labels/{labelId}.json` — удаление метки.

`caseIdOrNumber` — либо `case_id`, либо `case_number` из соответствующего Payload (внутри клиента выбирается одно значение).
