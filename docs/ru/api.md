# API Omnidesk (клиенты и Use Cases)

Пакет предоставляет основной класс `Palach\Omnidesk\Omnidesk` для работы с API Omnidesk, транспортный слой и типизированные use cases для типовых операций.

## Omnidesk и клиенты

Класс `Palach\Omnidesk\Omnidesk` зарегистрирован в контейнере как синглтон и использует конфигурацию (host, email, api_key) из `config/omnidesk.php`.  
Вы можете получить к нему доступ через удобный фасад `Palach\Omnidesk\Facades\Omnidesk`.

Основной класс предоставляет доступ к четырнадцати типизированным клиентам:

- `Palach\Omnidesk\Clients\CasesClient` — операции с обращениями (cases)
- `Palach\Omnidesk\Clients\ClientEmailsClient` — операции с email-адресами клиентов
- `Palach\Omnidesk\Clients\CompaniesClient` — операции с компаниями
- `Palach\Omnidesk\Clients\CustomFieldsClient` — операции с кастомными полями
- `Palach\Omnidesk\Clients\CustomChannelsClient` — операции с кастомными каналами
- `Palach\Omnidesk\Clients\StaffClient` — операции с персоналом
- `Palach\Omnidesk\Clients\FiltersClient` — операции с фильтрами
- `Palach\Omnidesk\Clients\GroupsClient` — операции с группами
- `Palach\Omnidesk\Clients\KnowledgeBaseClient` — операции с базой знаний
- `Palach\Omnidesk\Clients\LabelsClient` — операции с метками
- `Palach\Omnidesk\Clients\LanguagesClient` — операции с языками
- `Palach\Omnidesk\Clients\MacrosClient` — операции с шаблонами
- `Palach\Omnidesk\Clients\MessagesClient` — операции с сообщениями
- `Palach\Omnidesk\Clients\NotesClient` — операции с заметками
- `Palach\Omnidesk\Clients\UsersClient` — операции с пользователями

Использование в коде (внедрение через конструктор или `app()`):

```php
// Рекомендуется: Использование фасада Omnidesk
use Palach\Omnidesk\Facades\Omnidesk;

/** @var CasesClient $cases */
$cases = Omnidesk::cases();

/** @var ClientEmailsClient $clientEmails */
$clientEmails = Omnidesk::clientEmails();

/** @var CompaniesClient $companies */
$companies = Omnidesk::companies();

/** @var StaffClient $staff */
$staff = Omnidesk::staff();

/** @var FiltersClient $filters */
$filters = Omnidesk::filters();

/** @var GroupsClient $groups */
$groups = Omnidesk::groups();

/** @var KnowledgeBaseClient $knowledgeBase */
$knowledgeBase = Omnidesk::knowledgeBase();

/** @var LabelsClient $labels */
$labels = Omnidesk::labels();

/** @var LanguagesClient $languages */
$languages = Omnidesk::languages();

/** @var MacrosClient $macros */
$macros = Omnidesk::macros();

/** @var MessagesClient $messages */
$messages = Omnidesk::messages();

/** @var NotesClient $notes */
$notes = Omnidesk::notes();

/** @var CustomFieldsClient $customFields */
$customFields = Omnidesk::customFields();

/** @var CustomChannelsClient $customChannels */
$customChannels = Omnidesk::customChannels();

/** @var UsersClient $users */
$users = Omnidesk::users();

// Альтернативно: Прямое внедрение класса
use Palach\Omnidesk\Omnidesk;

/** @var Omnidesk $omnidesk */
$omnidesk = app(Omnidesk::class);
$cases = $omnidesk->cases();
$clientEmails = $omnidesk->clientEmails();
$companies = $omnidesk->companies();
$customFields = $omnidesk->customFields();
$customChannels = $omnidesk->customChannels();
$staff = $omnidesk->staff();
$filters = $omnidesk->filters();
$groups = $omnidesk->groups();
$knowledgeBase = $omnidesk->knowledgeBase();
$labels = $omnidesk->labels();
$languages = $omnidesk->languages();
$macros = $omnidesk->macros();
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
- **`$clientEmailsClient->fetchList(): FetchClientEmailListResponse`** — получение списка email-адресов клиента.
- **`$customFieldsClient->fetchList(): FetchCustomFieldListResponse`** — получение списка кастомных полей.
- **`$customChannelsClient->fetchList(): FetchCustomChannelListResponse`** — получение списка кастомных каналов.
- **`$languagesClient->fetchList(): FetchLanguageListResponse`** — получение списка языков.
- **`$filtersClient->fetchList(FetchFilterListPayload $payload): FetchFilterListResponse`** — получение списка фильтров для аутентифицированного сотрудника.
- **`$macrosClient->fetchList(): FetchMacroListResponse`** — получение списка шаблонов (общих и личных).
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
- **`$staffClient->store(StoreStaffPayload $payload): StoreStaffResponse`** — создание сотрудника.
- **`$staffClient->update(int $staffId, UpdateStaffPayload $payload): UpdateStaffResponse`** — редактирование сотрудника.
- **`$staffClient->disableStaff(int $staffId, DisabledStaffPayload $payload): DisabledStaffResponse`** — отключение сотрудника.
- **`$staffClient->enableStaff(int $staffId): EnabledStaffResponse`** — включение сотрудника.
- **`$staffClient->deleteStaff(int $staffId, DeleteStaffPayload $payload): DeleteStaffResponse`** — удаление сотрудника.
- **`$staffClient->fetchStaff(FetchStaffPayload $payload): FetchStaffResponse`** — получение сотрудника по ID.
- **`$staffClient->fetchStaffList(?FetchStaffListPayload $payload): FetchStaffListResponse`** — получение списка сотрудников с пагинацией и фильтрами.
- **`$staffClient->fetchStaffRoleList(): FetchStaffRoleListResponse`** — получение списка ролей сотрудников.
- **`$staffClient->fetchStaffStatusList(): FetchStaffStatusListResponse`** — получение списка статусов сотрудников.
- **`$companiesClient->store(StoreCompanyPayload $payload): StoreCompanyResponse`** — создание компании.
- **`$companiesClient->update(int $companyId, UpdateCompanyPayload $payload): UpdateCompanyResponse`** — редактирование компании.
- **`$companiesClient->fetchCompanyList(?FetchCompanyListPayload $payload): FetchCompanyListResponse`** — получение списка компаний с пагинацией и фильтрами.
- **`$companiesClient->getCompany(FetchCompanyPayload $payload): FetchCompanyResponse`** — получение компании по ID.
- **`$companiesClient->deleteCompany(int $companyId): DeleteCompanyResponse`** — удаление компании (перенос в список удалённых).
- **`$companiesClient->blockCompany(int $companyId): BlockCompanyResponse`** — блокирование компании (все последующие обращения компании будут помечаться как спам).
- **`$companiesClient->disableCompany(int $companyId): DisabledCompanyResponse`** — удаление компании (перенос в список удалённых).
- **`$companiesClient->recoveryCompany(int $companyId): RecoveryCompanyResponse`** — восстановление компании после блокировки или удаления.
- **`$groupsClient->getGroup(FetchGroupPayload $payload): FetchGroupResponse`** — получение группы по ID.
- **`$groupsClient->store(StoreGroupPayload $payload): StoreGroupResponse`** — создание группы.
- **`$groupsClient->update(int $groupId, UpdateGroupPayload $payload): UpdateGroupResponse`** — редактирование группы.
- **`$groupsClient->fetchList(FetchGroupListPayload $payload): FetchGroupListResponse`** — получение списка групп с пагинацией.
- **`$groupsClient->disableGroup(int $groupId, int $replaceGroupId): DisabledGroupResponse`** — отключение группы.
- **`$groupsClient->enableGroup(int $groupId): EnabledGroupResponse`** — включение группы.
- **`$groupsClient->deleteGroup(int $groupId, DeleteGroupPayload $payload): void`** — удаление группы.
- **`$knowledgeBaseClient->storeCategory(StoreKnowledgeBaseCategoryPayload $payload): StoreKnowledgeBaseCategoryResponse`** — создание категории базы знаний.
- **`$knowledgeBaseClient->storeSection(StoreKnowledgeBaseSectionPayload $payload): StoreKnowledgeBaseSectionResponse`** — создание раздела базы знаний.
- **`$knowledgeBaseClient->storeArticle(StoreKnowledgeBaseArticlePayload $payload): StoreKnowledgeBaseArticleResponse`** — создание статьи базы знаний.
- **`$knowledgeBaseClient->updateCategory(int $categoryId, UpdateKnowledgeBaseCategoryPayload $payload): UpdateKnowledgeBaseCategoryResponse`** — редактирование категории базы знаний.
- **`$knowledgeBaseClient->updateSection(int $sectionId, UpdateKnowledgeBaseSectionPayload $payload): UpdateKnowledgeBaseSectionResponse`** — редактирование раздела базы знаний.
- **`$knowledgeBaseClient->updateArticle(int $articleId, UpdateKnowledgeBaseArticlePayload $payload): UpdateKnowledgeBaseArticleResponse`** — редактирование статьи базы знаний.
- **`$knowledgeBaseClient->disableSection(int $sectionId): DisabledKnowledgeBaseSectionResponse`** — отключение раздела базы знаний.
- **`$knowledgeBaseClient->disableArticle(int $articleId): DisabledKnowledgeBaseArticleResponse`** — отключение статьи базы знаний.
- **`$knowledgeBaseClient->enableArticle(int $articleId): EnabledKnowledgeBaseArticleResponse`** — включение статьи базы знаний.
- **`$knowledgeBaseClient->enableSection(int $sectionId): EnabledKnowledgeBaseSectionResponse`** — включение раздела базы знаний.
- **`$knowledgeBaseClient->fetchList(FetchKnowledgeBaseCategoryListPayload $payload): FetchKnowledgeBaseCategoryListResponse`** — получение списка категорий базы знаний с пагинацией и фильтрацией по языку.
- **`$knowledgeBaseClient->fetchSectionList(FetchKnowledgeBaseSectionListPayload $payload): FetchKnowledgeBaseSectionListResponse`** — получение списка разделов базы знаний с пагинацией и фильтрацией по языку.
- **`$knowledgeBaseClient->fetchArticleList(FetchKnowledgeBaseArticleListPayload $payload): FetchKnowledgeBaseArticleListResponse`** — получение списка статей базы знаний с пагинацией, поиском и фильтрацией.
- **`$knowledgeBaseClient->getSection(FetchKnowledgeBaseSectionPayload $payload): FetchKnowledgeBaseSectionResponse`** — получение раздела базы знаний по ID.
- **`$knowledgeBaseClient->fetchArticle(FetchKnowledgeBaseArticlePayload $payload): FetchKnowledgeBaseArticleResponse`** — получение статьи базы знаний по ID.
- **`$knowledgeBaseClient->disableCategory(int $categoryId): DisabledKnowledgeBaseCategoryResponse`** — отключение категории базы знаний.
- **`$knowledgeBaseClient->enableCategory(int $categoryId): EnabledKnowledgeBaseCategoryResponse`** — включение категории базы знаний.
- **`$knowledgeBaseClient->moveUpCategory(int $categoryId): MoveUpKnowledgeBaseCategoryResponse`** — перемещение категории базы знаний вверх.
- **`$knowledgeBaseClient->moveUpSection(int $sectionId): MoveUpKnowledgeBaseSectionResponse`** — перемещение раздела базы знаний вверх.
- **`$knowledgeBaseClient->moveDownSection(int $sectionId): MoveDownKnowledgeBaseSectionResponse`** — перемещение раздела базы знаний вниз.
- **`$knowledgeBaseClient->moveDownCategory(int $categoryId): MoveDownKnowledgeBaseCategoryResponse`** — перемещение категории базы знаний вниз.
- **`$knowledgeBaseClient->deleteCategory(int $categoryId): DeleteKnowledgeBaseCategoryResponse`** — удаление категории базы знаний.
- **`$knowledgeBaseClient->deleteSection(int $sectionId): DeleteKnowledgeBaseSectionResponse`** — удаление раздела базы знаний.
- **`$usersClient->fetch(FetchUserPayload $payload): FetchUserResponse`** — получение пользователя по ID.
- **`$usersClient->store(StoreUserPayload $payload): StoreUserResponse`** — создание пользователя.
- **`$usersClient->update(int $userId, UpdateUserPayload $payload): UpdateUserResponse`** — редактирование пользователя.
- **`$usersClient->fetchList(FetchUserListPayload $payload): FetchUserListResponse`** — получение списка пользователей с пагинацией и фильтрами.
- **`$usersClient->fetchUserIdentification(FetchUserIdentificationPayload $payload): FetchUserIdentificationResponse`** — получение кода идентификации пользователя.
- **`$usersClient->linkUser(int $userId, LinkUserPayload $payload): LinkUserResponse`** — связывание профилей пользователей.
- **`$usersClient->unlinkUser(int $userId, UnlinkUserPayload $payload): UnlinkUserResponse`** — отвязывание профилей пользователей.
- **`$usersClient->disableUser(int $userId): DisableUserResponse`** — удаление пользователя (перенос в список удалённых).
- **`$usersClient->blockUser(int $userId): BlockUserResponse`** — блокирование пользователя (все последующие обращения пользователя будут помечаться как спам).
- **`$usersClient->deleteUser(int $userId): DeleteUserResponse`** — полное удаление пользователя (доступно только для сотрудников с полным доступом).
- **`$usersClient->recoveryUser(int $userId): RecoveryUserResponse`** — восстановление пользователя после блокировки или удаления.

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

## Store Knowledge Base Category (создание категории базы знаний)

**Payload:** `Palach\Omnidesk\UseCases\V1\StoreKnowledgeBaseCategory\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\StoreKnowledgeBaseCategory\Response` (содержит `KnowledgeBaseCategoryData`).

**Параметры Payload:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| category_title | string|array | да | Название категории. Если включена мультиязычность, можно передать массив с ID языков в качестве ключей и названиями в качестве значений. |

**KnowledgeBaseCategoryData** (поле `kb_category` в Response):
- `category_id` — ID категории
- `category_title` — Название категории
- `active` — Статус активности
- `created_at` — Дата создания
- `updated_at` — Дата обновления

Пример:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\KnowledgeBaseClient;
use Palach\Omnidesk\UseCases\V1\StoreKnowledgeBaseCategory\Payload as StoreKnowledgeBaseCategoryPayload;

/** @var KnowledgeBaseClient $knowledgeBase */
$knowledgeBase = Omnidesk::knowledgeBase();

// Один язык
$payload = new StoreKnowledgeBaseCategoryPayload(
    categoryTitle: 'Test category'
);

// Мультиязычный - когда включена мультиязычность и база знаний доступна на нескольких языках
$payload = new StoreKnowledgeBaseCategoryPayload(
    categoryTitle: [
        '1' => 'Название категории',
        '2' => 'Category name'
    ]
);

$response = $knowledgeBase->storeCategory($payload);
$category = $response->kbCategory; // KnowledgeBaseCategoryData
```

---

## Store Knowledge Base Section (создание раздела базы знаний)

**Payload:** `Palach\Omnidesk\UseCases\V1\StoreKnowledgeBaseSection\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\StoreKnowledgeBaseSection\Response` (содержит `KnowledgeBaseSectionData`).

**Параметры Payload:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| section_title | string|array | да | Название раздела. Если включена мультиязычность, можно передать массив с ID языков в качестве ключей и названиями в качестве значений. |
| section_description | string|array | нет | Описание раздела. Если включена мультиязычность, можно передать массив с ID языков в качестве ключей и описаниями в качестве значений. |
| category_id | string | да | ID категории |

**KnowledgeBaseSectionData** (поле `kb_section` в Response):
- `section_id` — ID раздела
- `category_id` — ID категории
- `section_title` — Название раздела
- `section_description` — Описание раздела
- `active` — Статус активности
- `created_at` — Дата создания
- `updated_at` — Дата обновления

Пример:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\KnowledgeBaseClient;
use Palach\Omnidesk\UseCases\V1\StoreKnowledgeBaseSection\Payload as StoreKnowledgeBaseSectionPayload;
use Palach\Omnidesk\UseCases\V1\StoreKnowledgeBaseSection\KnowledgeBaseSectionStoreData;

/** @var KnowledgeBaseClient $knowledgeBase */
$knowledgeBase = Omnidesk::knowledgeBase();

// Один язык
$payload = new StoreKnowledgeBaseSectionPayload(
    kbSection: new KnowledgeBaseSectionStoreData(
        sectionTitle: 'Test section',
        sectionDescription: 'Test section description',
        categoryId: '1'
    )
);

// Мультиязычный - когда включена мультиязычность и база знаний доступна на нескольких языках
$payload = new StoreKnowledgeBaseSectionPayload(
    kbSection: new KnowledgeBaseSectionStoreData(
        sectionTitle: [
            '1' => 'Тестовый раздел',
            '2' => 'Test section'
        ],
        sectionDescription: [
            '1' => 'Тестовое описание раздела',
            '2' => 'Test section description'
        ],
        categoryId: '1'
    )
);

$response = $knowledgeBase->storeSection($payload);
$section = $response->kbSection; // KnowledgeBaseSectionData
```

---

## Store Knowledge Base Article (создание статьи базы знаний)

**Payload:** `Palach\Omnidesk\UseCases\V1\StoreKnowledgeBaseArticle\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\StoreKnowledgeBaseArticle\Response` (содержит `KnowledgeBaseArticleData`).

**Параметры Payload:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| article_title | string|array | да | Название статьи. Если включена мультиязычность, можно передать массив с ID языков в качестве ключей и названиями в качестве значений. |
| article_content | string|array | да | Содержание статьи. Если включена мультиязычность, можно передать массив с ID языков в качестве ключей и содержанием в качестве значений. |
| article_tags | string|array | нет | Ключевые слова для поиска. Если включена мультиязычность, можно передать массив с ID языков. |
| section_id | string|array | да | ID раздела(ов). Можно передать массив для добавления статьи в несколько разделов. |
| access_type | string | нет | Уровень доступа (public или staff_only). По умолчанию - public. |

**KnowledgeBaseArticleData** (поле `kb_article` в Response):
- `article_id` — ID статьи
- `section_id` — ID основного раздела
- `section_id_arr` — Массив ID разделов (если статья добавлена в несколько разделов)
- `article_title` — Название статьи
- `article_content` — Содержание статьи
- `article_tags` — Ключевые слова для поиска
- `access_type` — Уровень доступа
- `active` — Статус активности
- `created_at` — Дата создания
- `updated_at` — Дата обновления

Пример:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\KnowledgeBaseClient;
use Palach\Omnidesk\UseCases\V1\StoreKnowledgeBaseArticle\Payload as StoreKnowledgeBaseArticlePayload;
use Palach\Omnidesk\UseCases\V1\StoreKnowledgeBaseArticle\KnowledgeBaseArticleStoreData;

/** @var KnowledgeBaseClient $knowledgeBase */
$knowledgeBase = Omnidesk::knowledgeBase();

// Один язык
$payload = new StoreKnowledgeBaseArticlePayload(
    kbArticle: new KnowledgeBaseArticleStoreData(
        articleTitle: 'Test article title',
        articleContent: 'Test article content',
        articleTags: 'test,article,content',
        sectionId: [10, 11],
        accessType: 'public'
    )
);

// Мультиязычный - когда включена мультиязычность и база знаний доступна на нескольких языках
$payload = new StoreKnowledgeBaseArticlePayload(
    kbArticle: new KnowledgeBaseArticleStoreData(
        articleTitle: [
            '1' => 'Название статьи',
            '2' => 'Test article title'
        ],
        articleContent: [
            '1' => 'Содержание статьи',
            '2' => 'Test article content'
        ],
        articleTags: [
            '1' => 'тег,тег,тег',
            '2' => 'tag,tag,tag'
        ],
        sectionId: [10, 11],
        accessType: 'public'
    )
);

$response = $knowledgeBase->storeArticle($payload);
$article = $response->kbArticle; // KnowledgeBaseArticleData
```

---

## Update Knowledge Base Category (редактирование категории базы знаний)

**Payload:** `Palach\Omnidesk\UseCases\V1\UpdateKnowledgeBaseCategory\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\UpdateKnowledgeBaseCategory\Response` (содержит `KnowledgeBaseCategoryData`).

**Параметры Payload:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| category_title | string|array | да | Название категории. Если включена мультиязычность, можно передать массив с ID языков в качестве ключей и названиями в качестве значений. |

**KnowledgeBaseCategoryData** (поле `kb_category` в Response):
- `category_id` — ID категории
- `category_title` — Название категории
- `active` — Статус активности
- `created_at` — Дата создания
- `updated_at` — Дата обновления

Пример:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\KnowledgeBaseClient;
use Palach\Omnidesk\UseCases\V1\UpdateKnowledgeBaseCategory\Payload as UpdateKnowledgeBaseCategoryPayload;
use Palach\Omnidesk\UseCases\V1\UpdateKnowledgeBaseCategory\KnowledgeBaseCategoryUpdateData;

/** @var KnowledgeBaseClient $knowledgeBase */
$knowledgeBase = Omnidesk::knowledgeBase();

// Один язык
$payload = new UpdateKnowledgeBaseCategoryPayload(
    kbCategory: new KnowledgeBaseCategoryUpdateData(
        categoryTitle: 'Updated category name'
    )
);

// Мультиязычный - когда включена мультиязычность и база знаний доступна на нескольких языках
$payload = new UpdateKnowledgeBaseCategoryPayload(
    kbCategory: new KnowledgeBaseCategoryUpdateData(
        categoryTitle: [
            '1' => 'Обновленное название категории',
            '2' => 'Updated category name'
        ]
    )
);

$response = $knowledgeBase->updateCategory(234, $payload);
$category = $response->kbCategory; // KnowledgeBaseCategoryData
```

---

## Update Knowledge Base Section (редактирование раздела базы знаний)

**Payload:** `Palach\Omnidesk\UseCases\V1\UpdateKnowledgeBaseSection\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\UpdateKnowledgeBaseSection\Response` (содержит `KnowledgeBaseSectionData`).

**Параметры Payload:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| section_title | string|array | да | Название раздела. Если включена мультиязычность, можно передать массив с ID языков в качестве ключей и названиями в качестве значений. |
| section_description | string|array | да | Описание раздела. Если включена мультиязычность, можно передать массив с ID языков в качестве ключей и описаниями в качестве значений. |
| category_id | int | да | ID категории |

**KnowledgeBaseSectionData** (поле `kb_section` в Response):
- `section_id` — ID раздела
- `category_id` — ID категории
- `section_title` — Название раздела
- `section_description` — Описание раздела
- `active` — Статус активности
- `created_at` — Дата создания
- `updated_at` — Дата обновления

Пример:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\KnowledgeBaseClient;
use Palach\Omnidesk\UseCases\V1\UpdateKnowledgeBaseSection\Payload as UpdateKnowledgeBaseSectionPayload;
use Palach\Omnidesk\UseCases\V1\UpdateKnowledgeBaseSection\KnowledgeBaseSectionUpdateData;

/** @var KnowledgeBaseClient $knowledgeBase */
$knowledgeBase = Omnidesk::knowledgeBase();

// Один язык
$payload = new UpdateKnowledgeBaseSectionPayload(
    kbSection: new KnowledgeBaseSectionUpdateData(
        sectionTitle: 'Обновленное название раздела',
        sectionDescription: 'Обновленное описание раздела',
        categoryId: 2
    )
);

// Мультиязычный - когда включена мультиязычность и база знаний доступна на нескольких языках
$payload = new UpdateKnowledgeBaseSectionPayload(
    kbSection: new KnowledgeBaseSectionUpdateData(
        sectionTitle: [
            '1' => 'Обновленное название раздела',
            '2' => 'Updated section name'
        ],
        sectionDescription: [
            '1' => 'Обновленное описание раздела',
            '2' => 'Updated section description'
        ],
        categoryId: 2
    )
);

$response = $knowledgeBase->updateSection(10, $payload);
$section = $response->kbSection; // KnowledgeBaseSectionData
```

---

## Update Knowledge Base Article (редактирование статьи базы знаний)

**Payload:** `Palach\Omnidesk\UseCases\V1\UpdateKnowledgeBaseArticle\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\UpdateKnowledgeBaseArticle\Response` (содержит `KnowledgeBaseArticleData`).

**Параметры Payload:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| article_title | string|array | да | Название статьи. Если включена мультиязычность, можно передать массив с ID языков в качестве ключей и названиями в качестве значений. |
| article_content | string|array | да | Содержание статьи. Если включена мультиязычность, можно передать массив с ID языков в качестве ключей и содержанием в качестве значений. |
| article_tags | string|array | да | Ключевые слова для поиска. Если включена мультиязычность, можно передать массив с ID языков. |
| section_id | string|array|int | да | ID раздела(ов). Можно передать массив для добавления статьи в несколько разделов. |
| access_type | string | нет | Уровень доступа (public или staff_only). По умолчанию - public. |

**KnowledgeBaseArticleData** (поле `kb_article` в Response):
- `article_id` — ID статьи
- `section_id` — ID основного раздела
- `article_title` — Название статьи
- `article_content` — Содержание статьи
- `article_tags` — Ключевые слова для поиска
- `access_type` — Уровень доступа
- `active` — Статус активности
- `created_at` — Дата создания
- `updated_at` — Дата обновления

Пример:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\KnowledgeBaseClient;
use Palach\Omnidesk\UseCases\V1\UpdateKnowledgeBaseArticle\Payload as UpdateKnowledgeBaseArticlePayload;
use Palach\Omnidesk\UseCases\V1\UpdateKnowledgeBaseArticle\KnowledgeBaseArticleUpdateData;

/** @var KnowledgeBaseClient $knowledgeBase */
$knowledgeBase = Omnidesk::knowledgeBase();

// Один язык
$payload = new UpdateKnowledgeBaseArticlePayload(
    kbArticle: new KnowledgeBaseArticleUpdateData(
        articleTitle: 'Обновленное название статьи',
        articleContent: 'Обновленное содержание статьи',
        articleTags: 'обновленный,тег,статья',
        sectionId: 20,
        accessType: 'public'
    )
);

// Мультиязычный - когда включена мультиязычность и база знаний доступна на нескольких языках
$payload = new UpdateKnowledgeBaseArticlePayload(
    kbArticle: new KnowledgeBaseArticleUpdateData(
        articleTitle: [
            '1' => 'Обновленное название статьи',
            '2' => 'Updated article title'
        ],
        articleContent: [
            '1' => 'Обновленное содержание статьи',
            '2' => 'Updated article content'
        ],
        articleTags: [
            '1' => 'обновленный,тег,статья',
            '2' => 'updated,tag,article'
        ],
        sectionId: 20,
        accessType: 'public'
    )
);

// Несколько разделов
$payload = new UpdateKnowledgeBaseArticlePayload(
    kbArticle: new KnowledgeBaseArticleUpdateData(
        articleTitle: 'Статья в нескольких разделах',
        articleContent: 'Содержание для нескольких разделов',
        articleTags: 'мульти,раздел,статья',
        sectionId: [20, 21, 22],
        accessType: 'staff_only'
    )
);

$response = $knowledgeBase->updateArticle(100, $payload);
$article = $response->kbArticle; // KnowledgeBaseArticleData
```

---

## Fetch Knowledge Base Category List (получение списка категорий базы знаний)

**Payload:** `Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseCategoryList\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseCategoryList\Response` (поля: `kbCategories` — коллекция `KnowledgeBaseCategoryData`, `total` — общее количество).

Получение списка категорий базы знаний с пагинацией и фильтрацией по языку.

**Параметры Payload:**

| Поле | Тип | Ограничения | Описание |
|------|-----|-------------|----------|
| page | int | 1–500 | Номер страницы (по умолчанию: 1) |
| limit | int | 1–100 | Лимит категорий на странице (по умолчанию: 100) |
| language_id | string | Опционально | ID языка для локализованных названий категорий. Используйте "all" для получения всех языков. По умолчанию: основной язык |

Для GET-запросов используется метод `Payload::toQuery()`.

**KnowledgeBaseCategoryData** (поле `kb_category` в Response):
- `category_id` — ID категории
- `category_title` — Название категории (строка или массив названий на разных языках)
- `active` — Статус активности
- `created_at` — Дата создания
- `updated_at` — Дата обновления

Пример:

```php
use Palach\Omnidesk\Clients\KnowledgeBaseClient;
use Palach\Omnidesk\Omnidesk;
use Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseCategoryList\Payload as FetchKnowledgeBaseCategoryListPayload;

/** @var Omnidesk $http */
$http = app(Omnidesk::class);

/** @var KnowledgeBaseClient $knowledgeBase */
$knowledgeBase = $http->knowledgeBase();

// Получение категорий с пагинацией
$payload = new FetchKnowledgeBaseCategoryListPayload(
    page: 1,
    limit: 20,
    languageId: '1',
);

// Или с параметрами по умолчанию:
// $payload = new FetchKnowledgeBaseCategoryListPayload();

// Получение всех языков
$payloadAllLanguages = new FetchKnowledgeBaseCategoryListPayload(
    page: 1,
    limit: 50,
    languageId: 'all',
);

$response = $knowledgeBase->fetchList($payload);
$categories = $response->kbCategories;
$total = $response->total;

// Перебор категорий
foreach ($categories as $category) {
    echo "ID категории: " . $category->categoryId . "\n";
    echo "Название категории: " . (is_array($category->categoryTitle) ? implode(', ', $category->categoryTitle) : $category->categoryTitle) . "\n";
    echo "Активна: " . ($category->active ? 'Да' : 'Нет') . "\n";
}
```

---

## Fetch Knowledge Base Section List (получение списка разделов базы знаний)

**Payload:** `Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseSectionList\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseSectionList\Response` (поля: `kbSections` — коллекция `KnowledgeBaseSectionData`, `total` — общее количество).

Получение списка разделов базы знаний с пагинацией и фильтрацией по языку.

**Параметры Payload:**

| Поле | Тип | Ограничения | Описание |
|------|-----|-------------|----------|
| page | int | 1–500 | Номер страницы (по умолчанию: 1) |
| limit | int | 1–100 | Лимит разделов на странице (по умолчанию: 100) |
| category_id | string | Обязательно | ID категории |
| language_id | string | Опционально | ID языка для локализованных названий разделов. Используйте "all" для получения всех языков. По умолчанию: основной язык |

Для GET-запросов используется метод `Payload::toQuery()`.

**KnowledgeBaseSectionData** (поле `kb_section` в Response):
- `section_id` — ID раздела
- `category_id` — ID категории
- `section_title` — Название раздела (строка или массив названий на разных языках)
- `section_description` — Описание раздела (строка или массив описаний на разных языках)
- `active` — Статус активности
- `created_at` — Дата создания
- `updated_at` — Дата обновления

Пример:

```php
use Palach\Omnidesk\Clients\KnowledgeBaseClient;
use Palach\Omnidesk\Omnidesk;
use Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseSectionList\Payload as FetchKnowledgeBaseSectionListPayload;

/** @var Omnidesk $http */
$http = app(Omnidesk::class);

/** @var KnowledgeBaseClient $knowledgeBase */
$knowledgeBase = $http->knowledgeBase();

// Получение разделов с пагинацией
$payload = new FetchKnowledgeBaseSectionListPayload(
    categoryId: '1',
    page: 1,
    limit: 20,
    languageId: '1',
);

// Или с параметрами по умолчанию:
// $payload = new FetchKnowledgeBaseSectionListPayload(categoryId: '1');

// Получение всех языков
$payloadAllLanguages = new FetchKnowledgeBaseSectionListPayload(
    categoryId: '1',
    page: 1,
    limit: 50,
    languageId: 'all',
);

$response = $knowledgeBase->fetchSectionList($payload);
$sections = $response->kbSections;
$total = $response->total;

// Перебор разделов
foreach ($sections as $section) {
    echo "ID раздела: " . $section->sectionId . "\n";
    echo "ID категории: " . $section->categoryId . "\n";
    echo "Название раздела: " . (is_array($section->sectionTitle) ? implode(', ', $section->sectionTitle) : $section->sectionTitle) . "\n";
    echo "Описание раздела: " . (is_array($section->sectionDescription) ? implode(', ', $section->sectionDescription) : $section->sectionDescription) . "\n";
    echo "Активна: " . ($section->active ? 'Да' : 'Нет') . "\n";
}
```

---

## Fetch Knowledge Base Article List (получение списка статей базы знаний)

**Payload:** `Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseArticleList\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseArticleList\Response` (поля: `kbArticles` — коллекция `KnowledgeBaseArticleData`, `total` — общее количество).

Получение списка статей базы знаний с пагинацией, поиском и фильтрацией.

**Параметры Payload:**

| Поле | Тип | Ограничения | Описание |
|------|-----|-------------|----------|
| page | int | 1–500 | Номер страницы (по умолчанию: 1) |
| limit | int | 1–100 | Лимит статей на странице (по умолчанию: 100) |
| search | string | Минимум 3 символа | Строка для поиска (опционально) |
| section_id | string | Опционально | ID раздела для вывода статей только конкретного раздела |
| language_id | string | Опционально | ID языка для локализованных данных статей. Используйте "all" для получения всех языков. По умолчанию: основной язык |
| sort | string | Опционально | Сортировка. Доступные значения: id_desc, id_asc, created_at_desc, created_at_asc, manual_order |

Для GET-запросов используется метод `Payload::toQuery()`.

**KnowledgeBaseArticleData** (поле `kb_article` в Response):
- `article_id` — ID статьи
- `section_id` — ID основного раздела
- `section_id_arr` — Массив ID разделов (если статья добавлена в несколько разделов)
- `article_title` — Название статьи (строка или массив названий на разных языках)
- `article_content` — Содержание статьи (строка или массив содержимого на разных языках)
- `article_tags` — Ключевые слова для поиска (строка или массив тегов на разных языках)
- `access_type` — Уровень доступа
- `active` — Статус активности
- `created_at` — Дата создания
- `updated_at` — Дата обновления

Пример:

```php
use Palach\Omnidesk\Clients\KnowledgeBaseClient;
use Palach\Omnidesk\Omnidesk;
use Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseArticleList\Payload as FetchKnowledgeBaseArticleListPayload;

/** @var Omnidesk $http */
$http = app(Omnidesk::class);

/** @var KnowledgeBaseClient $knowledgeBase */
$knowledgeBase = $http->knowledgeBase();

// Получение статей с пагинацией
$payload = new FetchKnowledgeBaseArticleListPayload(
    page: 1,
    limit: 20,
    languageId: '1',
);

// Поиск статей
$searchPayload = new FetchKnowledgeBaseArticleListPayload(
    search: 'test query',
    page: 1,
    limit: 10,
);

// Фильтрация по разделу с сортировкой
$sectionPayload = new FetchKnowledgeBaseArticleListPayload(
    sectionId: '10',
    sort: 'id_desc',
    page: 1,
    limit: 15,
);

// Получение всех языков
$allLanguagesPayload = new FetchKnowledgeBaseArticleListPayload(
    page: 1,
    limit: 50,
    languageId: 'all',
);

$response = $knowledgeBase->fetchArticleList($payload);
$articles = $response->kbArticles;
$total = $response->total;

// Перебор статей
foreach ($articles as $article) {
    echo "ID статьи: " . $article->articleId . "\n";
    echo "ID раздела: " . $article->sectionId . "\n";
    echo "Название статьи: " . (is_array($article->articleTitle) ? implode(', ', $article->articleTitle) : $article->articleTitle) . "\n";
    echo "Содержание статьи: " . (is_array($article->articleContent) ? implode(', ', $article->articleContent) : $article->articleContent) . "\n";
    echo "Теги: " . (is_array($article->articleTags) ? implode(', ', $article->articleTags) : $article->articleTags) . "\n";
    echo "Уровень доступа: " . $article->accessType . "\n";
    echo "Активна: " . ($article->active ? 'Да' : 'Нет') . "\n";
}
```

---

## Fetch Knowledge Base Section (получение раздела базы знаний)

**Payload:** `Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseSection\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseSection\Response` (содержит `KnowledgeBaseSectionData`).

Получение раздела базы знаний по ID.

**Параметры Payload:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| section_id | int | да | ID раздела |
| language_id | string | нет | ID языка для локализованных данных раздела. Используйте "all" для получения всех языков. По умолчанию: основной язык |

Для GET-запроса используется метод `Payload::toQuery()`.

**KnowledgeBaseSectionData** (поле `kb_section` в Response):
- `section_id` — ID раздела
- `category_id` — ID категории
- `section_title` — Название раздела (строка или массив названий на разных языках)
- `section_description` — Описание раздела (строка или массив описаний на разных языках)
- `active` — Статус активности
- `created_at` — Дата создания
- `updated_at` — Дата обновления

Пример:

```php
use Palach\Omnidesk\Clients\KnowledgeBaseClient;
use Palach\Omnidesk\Omnidesk;
use Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseSection\Payload as FetchKnowledgeBaseSectionPayload;

/** @var Omnidesk $http */
$http = app(Omnidesk::class);

/** @var KnowledgeBaseClient $knowledgeBase */
$knowledgeBase = $http->knowledgeBase();

// Получение раздела на основном языке
$payload = new FetchKnowledgeBaseSectionPayload(
    sectionId: 10
);

// Получение раздела на конкретном языке
$payloadWithLanguage = new FetchKnowledgeBaseSectionPayload(
    sectionId: 10,
    languageId: '2'
);

// Получение раздела на всех языках
$payloadAllLanguages = new FetchKnowledgeBaseSectionPayload(
    sectionId: 10,
    languageId: 'all'
);

$response = $knowledgeBase->getSection($payload);
$section = $response->kbSection; // KnowledgeBaseSectionData

echo "ID раздела: " . $section->sectionId . "\n";
echo "ID категории: " . $section->categoryId . "\n";
echo "Название раздела: " . (is_array($section->sectionTitle) ? implode(', ', $section->sectionTitle) : $section->sectionTitle) . "\n";
echo "Описание раздела: " . (is_array($section->sectionDescription) ? implode(', ', $section->sectionDescription) : $section->sectionDescription) . "\n";
echo "Активна: " . ($section->active ? 'Да' : 'Нет') . "\n";
```

---

## Fetch Knowledge Base Article (получение статьи базы знаний)

**Payload:** `Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseArticle\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseArticle\Response` (содержит `KnowledgeBaseArticleData`).

Получение статьи базы знаний по ID с опциональной фильтрацией по языку.

**Параметры Payload:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| article_id | int | да | ID статьи |
| language_id | string | нет | ID языка для локализованных данных статьи. Используйте "all" для получения всех языков. По умолчанию: основной язык |

Для GET-запроса используется метод `Payload::toQuery()`.

**KnowledgeBaseArticleData** (поле `kb_article` в Response):
- `article_id` — ID статьи
- `section_id` — Основной ID раздела
- `section_id_arr` — Массив ID разделов (если статья находится в нескольких разделах)
- `article_title` — Название статьи (строка или массив названий на разных языках)
- `article_content` — Содержание статьи (строка или массив содержимого на разных языках)
- `article_tags` — Ключевые слова для поиска (строка или массив тегов на разных языках)
- `access_type` — Уровень доступа (public/private)
- `active` — Статус активности
- `created_at` — Дата создания
- `updated_at` — Дата обновления

Пример:

```php
use Palach\Omnidesk\Clients\KnowledgeBaseClient;
use Palach\Omnidesk\Omnidesk;
use Palach\Omnidesk\UseCases\V1\FetchKnowledgeBaseArticle\Payload as FetchKnowledgeBaseArticlePayload;

/** @var Omnidesk $http */
$http = app(Omnidesk::class);

/** @var KnowledgeBaseClient $knowledgeBase */
$knowledgeBase = $http->knowledgeBase();

// Получение статьи на основном языке
$payload = new FetchKnowledgeBaseArticlePayload(
    articleId: 100
);

// Получение статьи на конкретном языке
$payloadWithLanguage = new FetchKnowledgeBaseArticlePayload(
    articleId: 100,
    languageId: '2'
);

// Получение статьи на всех языках
$payloadAllLanguages = new FetchKnowledgeBaseArticlePayload(
    articleId: 100,
    languageId: 'all'
);

$response = $knowledgeBase->fetchArticle($payload);
$article = $response->kbArticle; // KnowledgeBaseArticleData

echo "ID статьи: " . $article->articleId . "\n";
echo "ID раздела: " . $article->sectionId . "\n";
echo "ID разделов: " . implode(', ', $article->sectionIdArr ?? []) . "\n";
echo "Название статьи: " . (is_array($article->articleTitle) ? implode(', ', $article->articleTitle) : $article->articleTitle) . "\n";
echo "Содержание статьи: " . (is_array($article->articleContent) ? implode(', ', $article->articleContent) : $article->articleContent) . "\n";
echo "Теги: " . (is_array($article->articleTags) ? implode(', ', $article->articleTags) : $article->articleTags) . "\n";
echo "Тип доступа: " . $article->accessType . "\n";
echo "Активна: " . ($article->active ? 'Да' : 'Нет') . "\n";
```

---

## Disable Knowledge Base Category (отключение категории базы знаний)

**Response:** `Palach\Omnidesk\UseCases\V1\DisabledKnowledgeBaseCategory\Response` (содержит `KnowledgeBaseCategoryData`).

**Параметры метода:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| category_id | int | да | ID категории |

**KnowledgeBaseCategoryData** (поле `kb_category` в Response):
- `category_id` — ID категории
- `category_title` — Название категории
- `active` — Статус активности (будет false после отключения)
- `created_at` — Дата создания
- `updated_at` — Дата обновления

Пример:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\KnowledgeBaseClient;

/** @var KnowledgeBaseClient $knowledgeBase */
$knowledgeBase = Omnidesk::knowledgeBase();

$response = $knowledgeBase->disableCategory(234);
$category = $response->kbCategory; // KnowledgeBaseCategoryData с active = false
```

---

## Disable Knowledge Base Section (отключение раздела базы знаний)

**Response:** `Palach\Omnidesk\UseCases\V1\DisabledKnowledgeBaseSection\Response` (содержит `KnowledgeBaseSectionData`).

**Параметры метода:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| section_id | int | да | ID раздела |

**KnowledgeBaseSectionData** (поле `kb_section` в Response):
- `section_id` — ID раздела
- `category_id` — ID категории
- `section_title` — Название раздела
- `section_description` — Описание раздела
- `active` — Статус активности (будет false после отключения)
- `created_at` — Дата создания
- `updated_at` — Дата обновления

Пример:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\KnowledgeBaseClient;

/** @var KnowledgeBaseClient $knowledgeBase */
$knowledgeBase = Omnidesk::knowledgeBase();

$response = $knowledgeBase->disableSection(10);
$section = $response->kbSection; // KnowledgeBaseSectionData с active = false
```

---

## Disable Knowledge Base Article (отключение статьи базы знаний)

**Response:** `Palach\Omnidesk\UseCases\V1\DisabledKnowledgeBaseArticle\Response` (содержит `KnowledgeBaseArticleData`).

**Параметры метода:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| article_id | int | да | ID статьи |

**KnowledgeBaseArticleData** (поле `kb_article` в Response):
- `article_id` — ID статьи
- `section_id` — ID раздела
- `article_title` — Название статьи
- `article_content` — Содержимое статьи
- `access_type` — Тип доступа (public, private и т.д.)
- `active` — Статус активности (будет false после отключения)
- `created_at` — Дата создания
- `updated_at` — Дата обновления

Пример:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\KnowledgeBaseClient;

/** @var KnowledgeBaseClient $knowledgeBase */
$knowledgeBase = Omnidesk::knowledgeBase();

$response = $knowledgeBase->disableArticle(100);
$article = $response->kbArticle; // KnowledgeBaseArticleData с active = false
```

---

## Enable Knowledge Base Article (включение статьи базы знаний)

**Response:** `Palach\Omnidesk\UseCases\V1\EnabledKnowledgeBaseArticle\Response` (содержит `KnowledgeBaseArticleData`).

**Параметры метода:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| article_id | int | да | ID статьи |

**KnowledgeBaseArticleData** (поле `kb_article` в Response):
- `article_id` — ID статьи
- `section_id` — ID раздела
- `article_title` — Название статьи
- `article_content` — Содержимое статьи
- `access_type` — Тип доступа (public, private и т.д.)
- `active` — Статус активности (будет true после включения)
- `created_at` — Дата создания
- `updated_at` — Дата обновления

Пример:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\KnowledgeBaseClient;

/** @var KnowledgeBaseClient $knowledgeBase */
$knowledgeBase = Omnidesk::knowledgeBase();

$response = $knowledgeBase->enableArticle(100);
$article = $response->kbArticle; // KnowledgeBaseArticleData с active = true
```

---

## Enable Knowledge Base Section (включение раздела базы знаний)

**Response:** `Palach\Omnidesk\UseCases\V1\EnabledKnowledgeBaseSection\Response` (содержит `KnowledgeBaseSectionData`).

**Параметры метода:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| section_id | int | да | ID раздела |

**KnowledgeBaseSectionData** (поле `kb_section` в Response):
- `section_id` — ID раздела
- `category_id` — ID категории
- `section_title` — Название раздела
- `section_description` — Описание раздела
- `active` — Статус активности (будет true после включения)
- `created_at` — Дата создания
- `updated_at` — Дата обновления

Пример:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\KnowledgeBaseClient;

/** @var KnowledgeBaseClient $knowledgeBase */
$knowledgeBase = Omnidesk::knowledgeBase();

$response = $knowledgeBase->enableSection(10);
$section = $response->kbSection; // KnowledgeBaseSectionData с active = true
```

---

## Enable Knowledge Base Category (включение категории базы знаний)

**Response:** `Palach\Omnidesk\UseCases\V1\EnabledKnowledgeBaseCategory\Response` (содержит `KnowledgeBaseCategoryData`).

**Параметры метода:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| category_id | int | да | ID категории |

**KnowledgeBaseCategoryData** (поле `kb_category` в Response):
- `category_id` — ID категории
- `category_title` — Название категории
- `active` — Статус активности (будет true после включения)
- `created_at` — Дата создания
- `updated_at` — Дата обновления

Пример:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\KnowledgeBaseClient;

/** @var KnowledgeBaseClient $knowledgeBase */
$knowledgeBase = Omnidesk::knowledgeBase();

$response = $knowledgeBase->enableCategory(234);
$category = $response->kbCategory; // KnowledgeBaseCategoryData с active = true
```

---

## Move Up Knowledge Base Category (перемещение категории базы знаний вверх)

**Response:** `Palach\Omnidesk\UseCases\V1\MoveUpKnowledgeBaseCategory\Response` (содержит `KnowledgeBaseCategoryData`).

**Параметры метода:**

| Поле | Тип | Обязательно | Описание |
|------|-----|-------------|----------|
| category_id | int | да | ID категории |

**KnowledgeBaseCategoryData** (поле `kb_category` в ответе):
- `category_id` — ID категории
- `category_title` — Название категории
- `active` — Статус активности
- `created_at` — Дата создания
- `updated_at` — Дата обновления

Пример:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\KnowledgeBaseClient;

/** @var KnowledgeBaseClient $knowledgeBase */
$knowledgeBase = Omnidesk::knowledgeBase();

$response = $knowledgeBase->moveUpCategory(234);
$category = $response->kbCategory; // KnowledgeBaseCategoryData
```

---

## Move Up Knowledge Base Section (перемещение раздела базы знаний вверх)

**Response:** `Palach\Omnidesk\UseCases\V1\MoveUpKnowledgeBaseSection\Response` (содержит `KnowledgeBaseSectionData`).

**HTTP Method:** `PUT /api/kb_section/{id}/moveup.json`

Переместить раздел на шаг вверх.

**Параметры метода:**

| Поле | Тип | Обязательно | Описание |
|------|-----|-------------|----------|
| section_id | int | да | ID раздела |

**KnowledgeBaseSectionData** (поле `kb_section` в ответе):
- `section_id` — ID раздела
- `category_id` — ID категории
- `section_title` — Название раздела
- `section_description` — Описание раздела
- `active` — Статус активности
- `created_at` — Дата создания
- `updated_at` — Дата обновления

Пример:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\KnowledgeBaseClient;

/** @var KnowledgeBaseClient $knowledgeBase */
$knowledgeBase = Omnidesk::knowledgeBase();

$response = $knowledgeBase->moveUpSection(10);
$section = $response->kbSection; // KnowledgeBaseSectionData
```

---

## Move Down Knowledge Base Section (перемещение раздела базы знаний вниз)

**Response:** `Palach\Omnidesk\UseCases\V1\MoveDownKnowledgeBaseSection\Response` (содержит `KnowledgeBaseSectionData`).

**HTTP Method:** `PUT /api/kb_section/{id}/movedown.json`

Переместить раздел на шаг вниз.

**Параметры метода:**

| Поле | Тип | Обязательно | Описание |
|------|-----|-------------|----------|
| section_id | int | да | ID раздела |

**KnowledgeBaseSectionData** (поле `kb_section` в ответе):
- `section_id` — ID раздела
- `category_id` — ID категории
- `section_title` — Название раздела
- `section_description` — Описание раздела
- `active` — Статус активности
- `created_at` — Дата создания
- `updated_at` — Дата обновления

Пример:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\KnowledgeBaseClient;

/** @var KnowledgeBaseClient $knowledgeBase */
$knowledgeBase = Omnidesk::knowledgeBase();

$response = $knowledgeBase->moveDownSection(10);
$section = $response->kbSection; // KnowledgeBaseSectionData
```

---

## Move Down Knowledge Base Category (перемещение категории базы знаний вниз)

**Response:** `Palach\Omnidesk\UseCases\V1\MoveDownKnowledgeBaseCategory\Response` (содержит `KnowledgeBaseCategoryData`).

**Параметры метода:**

| Поле | Тип | Обязательно | Описание |
|------|-----|-------------|----------|
| category_id | int | да | ID категории |

**KnowledgeBaseCategoryData** (поле `kb_category` в ответе):
- `category_id` — ID категории
- `category_title` — Название категории
- `active` — Статус активности
- `created_at` — Дата создания
- `updated_at` — Дата обновления

Пример:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\KnowledgeBaseClient;

/** @var KnowledgeBaseClient $knowledgeBase */
$knowledgeBase = Omnidesk::knowledgeBase();

$response = $knowledgeBase->moveDownCategory(234);
$category = $response->kbCategory; // KnowledgeBaseCategoryData
```

---

## Delete Knowledge Base Category (удаление категории)

**Response:** `Palach\Omnidesk\UseCases\V1\DeleteKnowledgeBaseCategory\Response` (содержит `KnowledgeBaseCategoryData`).

**HTTP Method:** `DELETE /api/kb_category/{id}.json`

Удаление категории базы знаний.

Пример:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\KnowledgeBaseClient;

/** @var KnowledgeBaseClient $knowledgeBase */
$knowledgeBase = Omnidesk::knowledgeBase();

$response = $knowledgeBase->deleteCategory(234);
$category = $response->kbCategory; // KnowledgeBaseCategoryData
```

#### Удаление раздела базы знаний

```php
/** @var KnowledgeBaseClient $knowledgeBase */
$knowledgeBase = Omnidesk::knowledgeBase();

$response = $knowledgeBase->deleteSection(345);
$section = $response->kbSection; // KnowledgeBaseSectionData
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

## Store Company (создание компании)

**Payload:** `Palach\Omnidesk\UseCases\V1\StoreCompany\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\StoreCompany\Response` (содержит `CompanyData`).

**Параметры Payload:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| company_name | string | да | Название компании |
| company_domains | string|null | нет | Можно указывать несколько доменов через запятую |
| company_default_group | int|null | нет | ID группы |
| company_address | string|null | нет | Адрес компании |
| company_note | string|null | нет | Заметка |
| company_users | string|null | нет | Через запятую ID пользователей, которые должны попасть в компанию |

**CompanyData** (поле `company` в Response):
- `company_id` — ID компании
- Все поля из запроса плюс служебные поля (`active`, `deleted`, `created_at`, `updated_at`)

Пример:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\CompaniesClient;
use Palach\Omnidesk\UseCases\V1\StoreCompany\Payload as StoreCompanyPayload;

/** @var CompaniesClient $companies */
$companies = Omnidesk::companies();

$payload = new StoreCompanyPayload(
    companyName: 'New Company',
    companyDomains: 'company.ru',
    companyDefaultGroup: 492,
    companyAddress: 'Some address',
    companyNote: 'Some note',
    companyUsers: '1351,1348,1347'
);

$response = $companies->store($payload);
$company = $response->company; // CompanyData
```

---

## Store Staff (создание сотрудника)

**Payload:** `Palach\Omnidesk\UseCases\V1\StoreStaff\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\StoreStaff\Response` (содержит `StaffData`).

**Параметры Payload:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| staff_email | string | да | Валидный email-адрес сотрудника |
| staff_full_name | string | нет | Полное имя сотрудника |
| staff_signature | string | нет | Подпись сотрудника для email-обращений |
| staff_signature_chat | string | нет | Подпись сотрудника для чатов |

**StaffData** (поле `staff` в ответе):
- `staff_id` — ID сотрудника
- Все поля запроса плюс служебные поля (`thumbnail`, `active`, `created_at`, `updated_at`)

Пример:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\StaffsClient;
use Palach\Omnidesk\UseCases\V1\StoreStaff\Payload as StoreStaffPayload;

/** @var StaffsClient $staff */
$staff = Omnidesk::staff();

$payload = new StoreStaffPayload(
    staffEmail: 'staff@domain.ru',
    staffFullName: 'Staff full name',
    staffSignature: 'Staff signature for email cases',
    staffSignatureChat: 'Staff signature for chats'
);

$response = $staff->store($payload);
$employee = $response->staff; // StaffData
```

---

## Update Staff (редактирование сотрудника)

**Payload:** `Palach\Omnidesk\UseCases\V1\UpdateStaff\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\UpdateStaff\Response` (содержит `StaffData`).

**StaffUpdateData** (поле `staff` в payload):

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| staff_email | string | нет | Новый email-адрес сотрудника |
| staff_full_name | string | нет | Полное имя сотрудника |
| staff_signature | string | нет | Подпись сотрудника для email-обращений |
| staff_signature_chat | string | нет | Подпись сотрудника для чатов |

**Параметры метода:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| staff_id | int | да | ID сотрудника |
| payload | UpdateStaffPayload | да | Данные для обновления |

Пример:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\StaffsClient;
use Palach\Omnidesk\UseCases\V1\UpdateStaff\Payload as UpdateStaffPayload;
use Palach\Omnidesk\UseCases\V1\UpdateStaff\StaffUpdateData;

/** @var StaffsClient $staff */
$staff = Omnidesk::staff();

$payload = new UpdateStaffPayload(
    staff: new StaffUpdateData(
        staffFullName: "Измененное полное имя сотрудника",
        staffSignature: 'Обновленная подпись для email-обращений',
        staffSignatureChat: 'Обновленная подпись для чатов'
    )
);

$response = $staff->update(200, $payload);
$employee = $response->staff; // StaffData
```

---

## Disable Staff (отключение сотрудника)

**Payload:** `Palach\Omnidesk\UseCases\V1\DisabledStaff\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\DisabledStaff\Response` (содержит `StaffData`).

Отключение сотрудника.

**Параметры метода:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| replace_staff_id | int | да | ID сотрудника, который заменит отключаемого в настройках правил, общих шаблонов и параметрах обращений со статусом «открытое» и «в ожидании» |

**Пример использования:**

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\StaffsClient;
use Palach\Omnidesk\UseCases\V1\DisabledStaff\Payload as DisabledStaffPayload;
use Palach\Omnidesk\UseCases\V1\DisabledStaff\DisabledStaffData;

/** @var StaffsClient $staff */
$staff = Omnidesk::staff();

$payload = new DisabledStaffPayload(
    staff: new DisabledStaffData(
        replaceStaffId: 300
    )
);

$response = $staff->disableStaff(200, $payload);
$disabledStaff = $response->staff; // StaffData
```

---

## Enable Staff (включение сотрудника)

**Response:** `Palach\Omnidesk\UseCases\V1\EnabledStaff\Response` (содержит `StaffData`).

Включение сотрудника.

**Параметры метода:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| staff_id | int | да | ID сотрудника |

**Пример использования:**

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\StaffsClient;

/** @var StaffsClient $staff */
$staff = Omnidesk::staff();

$response = $staff->enableStaff(200);
$enabledStaff = $response->staff; // StaffData
```

---

## Delete Staff (удаление сотрудника)

**Payload:** `Palach\Omnidesk\UseCases\V1\DeleteStaff\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\DeleteStaff\Response` (содержит `StaffData`).

**Параметры Payload:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| replace_staff_id | int | да | ID сотрудника, который заменит удаляемого в настройках правил, общих шаблонов и параметрах всех обращений (с любым статусом) |

**Параметры метода:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| staff_id | int | да | ID сотрудника (из URL) - который будет удалён |
| payload | DeleteStaffPayload | да | Данные для удаления |

Удаление сотрудника.

Пример:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\StaffsClient;
use Palach\Omnidesk\UseCases\V1\DeleteStaff\Payload as DeleteStaffPayload;

/** @var StaffsClient $staff */
$staff = Omnidesk::staff();

$payload = new DeleteStaffPayload(
    replaceStaffId: 300
);

$response = $staff->deleteStaff(100, $payload);
$deletedStaff = $response->staff; // StaffData с полем deleted = true
```

---

## Fetch Staff (просмотр сотрудника)

**Payload:** `Palach\Omnidesk\UseCases\V1\FetchStaff\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\FetchStaff\Response` (поля: `staff` — `StaffData`).

Просмотр данных конкретного сотрудника.

Параметры запроса:

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| staff_id | int | да | ID сотрудника |
| language_id | string | нет | ID языка для локализованных данных сотрудника |

Для GET-запросов используйте метод `Payload::toQuery()`.

Пример:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\StaffsClient;
use Palach\Omnidesk\UseCases\V1\FetchStaff\Payload as FetchStaffPayload;

/** @var StaffsClient $staff */
$staff = Omnidesk::staff();

$payload = new FetchStaffPayload(
    staffId: 200,
    languageId: 'en',
);
// Или без языка:
// $payload = new FetchStaffPayload(staffId: 200);

$response = $staff->fetchStaff($payload);
$employee = $response->staff; // StaffData

echo "ID сотрудника: " . $employee->staffId . "\n";
echo "Email: " . $employee->staffEmail . "\n";
echo "Активен: " . ($employee->active ? 'Да' : 'Нет') . "\n";
echo "Статус: " . $employee->status . "\n";
```

---

## Fetch Staff List (получение списка сотрудников)

**Payload:** `Palach\Omnidesk\UseCases\V1\FetchStaffList\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\FetchStaffList\Response` (поля: `staff` — коллекция `StaffData`, `total` — общее количество).

Получение списка сотрудников с пагинацией.

Параметры запроса:

| Поле | Тип | Ограничения | Описание |
|------|-----|-------------|----------|
| page | int | 1–500 | Номер страницы (по умолчанию: 1) |
| limit | int | 1–100 | Лимит сотрудников на странице (по умолчанию: 100) |
| language_id | string | — | ID языка для локализации данных сотрудника |

Для GET-запроса используется метод `Payload::toQuery()`.

Пример:

```php
use Palach\Omnidesk\Clients\StaffClient;
use Palach\Omnidesk\Omnidesk;
use Palach\Omnidesk\UseCases\V1\FetchStaffList\Payload as FetchStaffListPayload;

/** @var Omnidesk $http */
$http = app(Omnidesk::class);

/** @var StaffClient $staff */
$staff = $http->staff();
$payload = new FetchStaffListPayload(
    page: 1,
    limit: 20,
    languageId: 'ru',
);
// Или с параметрами по умолчанию:
// $payload = new FetchStaffListPayload();
$response = $staff->fetchStaffList($payload);
$staffMembers = $response->staff;
$total = $response->total;

// Перебор сотрудников
foreach ($staffMembers as $member) {
    echo "ID сотрудника: " . $member->staffId . "\n";
    echo "Email: " . $member->staffEmail . "\n";
    echo "Активен: " . ($member->active ? 'Да' : 'Нет') . "\n";
    echo "Статус: " . $member->status . "\n";
}
```

---

## Fetch Group (просмотр группы)

**Payload:** `Palach\Omnidesk\UseCases\V1\FetchGroup\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\FetchGroup\Response` (поля: `group` — `GroupData`).

Просмотр конкретной группы по ID.

Параметры запроса:

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| group_id | int | да | ID группы |

Пример:

```php
use Palach\Omnidesk\Clients\GroupsClient;
use Palach\Omnidesk\Omnidesk;
use Palach\Omnidesk\UseCases\V1\FetchGroup\Payload as FetchGroupPayload;

/** @var Omnidesk $http */
$http = app(Omnidesk::class);

/** @var GroupsClient $groups */
$groups = $http->groups();
$payload = new FetchGroupPayload(
    groupId: 200,
);

$response = $groups->getGroup($payload);
$group = $response->group; // GroupData
```

---

## Store Group (создание группы)

**Payload:** `Palach\Omnidesk\UseCases\V1\StoreGroup\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\StoreGroup\Response` (содержит `GroupData`).

**Параметры Payload:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| group_title | string | да | Название группы |
| group_from_name | string|null | нет | Групповое имя отправителя для использования в ответах сотрудников |
| group_signature | string|null | нет | Групповая подпись для использования в ответах сотрудников |

**GroupData** (поле `group` в Response):
- `group_id` — ID группы
- Все поля из запроса плюс служебные поля (`active`, `created_at`, `updated_at`)

Пример:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\GroupsClient;
use Palach\Omnidesk\UseCases\V1\StoreGroup\Payload as StoreGroupPayload;

/** @var GroupsClient $groups */
$groups = Omnidesk::groups();

$payload = new StoreGroupPayload(
    groupTitle: 'Test group',
    groupFromName: 'Test group from name',
    groupSignature: 'Test group signature'
);

$response = $groups->store($payload);
$group = $response->group; // GroupData
```

---

## Fetch Group List (получение списка групп)

**Payload:** `Palach\Omnidesk\UseCases\V1\FetchGroupList\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\FetchGroupList\Response` (поля: `groups` — коллекция `GroupData`, `total` — общее количество).

Получение списка групп.

Параметры запроса:

| Поле | Тип | Ограничения | Описание |
|------|-----|-------------|----------|
| page | int | 1–500 | Номер страницы (по умолчанию: 1) |
| limit | int | 1–100 | Лимит групп на странице (по умолчанию: 100) |

Для GET-запроса используется метод `Payload::toQuery()`.

Пример:

```php
use Palach\Omnidesk\Clients\GroupsClient;
use Palach\Omnidesk\Omnidesk;
use Palach\Omnidesk\UseCases\V1\FetchGroupList\Payload as FetchGroupListPayload;

/** @var Omnidesk $http */
$http = app(Omnidesk::class);

/** @var GroupsClient $groups */
$groups = $http->groups();
$payload = new FetchGroupListPayload(
    page: 1,
    limit: 20,
);
// Или с параметрами по умолчанию:
// $payload = new FetchGroupListPayload();
$response = $groups->fetchList($payload);
$groups = $response->groups;
$total = $response->total;

// Перебор групп
foreach ($groups as $group) {
    echo "ID группы: " . $group->groupId . "\n";
    echo "Название группы: " . $group->groupTitle . "\n";
    echo "Имя отправителя: " . $group->groupFromName . "\n";
    echo "Подпись: " . $group->groupSignature . "\n";
    echo "Активна: " . ($group->active ? 'Да' : 'Нет') . "\n";
}
```

---

## Update Company (редактирование компании)

**Payload:** `Palach\Omnidesk\UseCases\V1\UpdateCompany\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\UpdateCompany\Response` (содержит `CompanyData`).

**CompanyUpdateData** (поле `company` в Payload):

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| company_name | string | нет | Название компании |
| add_company_domains | string | нет | Добавление доменов для автоматической привязки пользователей (можно указывать несколько доменов через запятую) |
| remove_company_domains | string | нет | Удаление доменов для автоматической привязки пользователей (можно указывать несколько доменов через запятую) |
| company_default_group | string | нет | ID группы |
| company_address | string | нет | Адрес компании |
| company_note | string | нет | Заметка |
| add_company_users | string | нет | Через запятую ID пользователей, которых нужно добавить в компанию |
| remove_company_users | string | нет | Через запятую ID пользователей, которых нужно удалить из компании |

**Параметры метода:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| company_id | int | да | ID компании |
| payload | UpdateCompanyPayload | да | Данные для обновления |

Пример:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\CompaniesClient;
use Palach\Omnidesk\UseCases\V1\UpdateCompany\CompanyUpdateData;
use Palach\Omnidesk\UseCases\V1\UpdateCompany\Payload as UpdateCompanyPayload;

/** @var CompaniesClient $companies */
$companies = Omnidesk::companies();

$payload = new UpdateCompanyPayload(
    company: new CompanyUpdateData(
        companyName: "Company's full name changed",
        companyNote: 'New note',
        addCompanyDomains: 'newcompany.ru',
        removeCompanyDomains: 'company.ru'
    )
);

$response = $companies->update(200, $payload);
$company = $response->company; // CompanyData
```

---

## Update Group (редактирование группы)

**Payload:** `Palach\Omnidesk\UseCases\V1\UpdateGroup\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\UpdateGroup\Response` (содержит `GroupData`).

**GroupUpdateData** (поле `group` в Payload):

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| group_title | string | нет | Название группы |
| group_from_name | string | нет | Групповое имя отправителя для использования в ответах сотрудников |
| group_signature | string | нет | Групповая подпись для использования в ответах сотрудников |

**Параметры метода:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| group_id | int | да | ID группы |
| payload | UpdateGroupPayload | да | Данные для обновления |

Пример:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\GroupsClient;
use Palach\Omnidesk\UseCases\V1\UpdateGroup\GroupUpdateData;
use Palach\Omnidesk\UseCases\V1\UpdateGroup\Payload as UpdateGroupPayload;

/** @var GroupsClient $groups */
$groups = Omnidesk::groups();

$payload = new UpdateGroupPayload(
    group: new GroupUpdateData(
        groupTitle: 'Test group 2',
        groupFromName: 'Test group 2 from name'
    )
);

$response = $groups->update(200, $payload);
$group = $response->group; // GroupData
```

---

## Fetch Company List (получение списка компаний)

**Payload:** `Palach\Omnidesk\UseCases\V1\FetchCompanyList\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\FetchCompanyList\Response` (поля: `companies` — коллекция `CompanyData`, `totalCount` — общее количество).

Получение списка компаний с пагинацией и фильтрами.

Параметры запроса (все опциональны):

| Поле | Тип | Ограничения | Описание |
|------|-----|-------------|----------|
| page | int\|Optional | 1–500 | Номер страницы (по умолчанию в API Omnidesk: 1) |
| limit | int\|Optional | 1–100 | Лимит компаний на странице (по умолчанию в API: 100) |
| company_name | string\|Optional | — | Поиск компаний по названию (не менее 3-х символов) |
| company_domains | string\|Optional | — | Поиск компаний по домену (не менее 3-х символов) |
| company_address | string\|Optional | — | Поиск компаний по адресу (не менее 3-х символов) |
| company_note | string\|Optional | — | Поиск компаний по заметке (не менее 3-х символов) |
| amount_of_users | bool\|Optional | — | Количество пользователей компании (по умолчанию: false) |
| amount_of_cases | bool\|Optional | — | Количество обращений компании (по умолчанию: false) |

Для GET-запроса используется метод `Payload::toQuery()`.

Пример:

```php
use Palach\Omnidesk\Clients\CompaniesClient;
use Palach\Omnidesk\Omnidesk;
use Palach\Omnidesk\UseCases\V1\FetchCompanyList\Payload as FetchCompanyListPayload;

/** @var Omnidesk $http */
$http = app(Omnidesk::class);

/** @var CompaniesClient $companies */
$companies = $http->companies();
$payload = new FetchCompanyListPayload(
    page: 1,
    limit: 20,
    companyName: 'Test Company',
    amountOfUsers: true,
);
// Или с параметрами по умолчанию:
// $payload = new FetchCompanyListPayload();
$response = $companies->fetchCompanyList($payload);
$companies = $response->companies;
$totalCount = $response->totalCount;

// Перебор компаний
foreach ($companies as $company) {
    echo "ID компании: " . $company->companyId . "\n";
    echo "Название компании: " . $company->companyName . "\n";
    echo "Количество пользователей: " . $company->amountOfUsers . "\n";
}
```

---

## Fetch Staff Role List (получение списка ролей сотрудников)

**Response:** `Palach\Omnidesk\UseCases\V1\FetchStaffRoleList\Response` (поля: `staffRoles` — коллекция `StaffRoleData`, `count` — общее количество).

Получение списка ролей сотрудников.

Пример:

```php
use Palach\Omnidesk\Clients\StaffsClient;
use Palach\Omnidesk\Omnidesk;

/** @var Omnidesk $http */
$http = app(Omnidesk::class);

/** @var StaffsClient $staff */
$staff = $http->staffs();
$response = $staff->fetchStaffRoleList();
$staffRoles = $response->staffRoles;
$count = $response->count;

// Перебор ролей
foreach ($staffRoles as $role) {
    echo "ID роли: " . $role->roleId . "\n";
    echo "Название роли: " . $role->role . "\n";
}
```

---

## Fetch Staff Status List (получение списка статусов сотрудников)

**Response:** `Palach\Omnidesk\UseCases\V1\FetchStaffStatusList\Response` (поля: `staffStatuses` — коллекция `StaffStatusData`, `count` — общее количество).

Получение списка статусов сотрудников.

Пример:

```php
use Palach\Omnidesk\Clients\StaffsClient;
use Palach\Omnidesk\Omnidesk;

/** @var Omnidesk $http */
$http = app(Omnidesk::class);

/** @var StaffsClient $staff */
$staff = $http->staffs();
$response = $staff->fetchStaffStatusList();
$staffStatuses = $response->staffStatuses;
$count = $response->count;

// Перебор статусов
foreach ($staffStatuses as $status) {
    echo "ID статуса: " . $status->statusId . "\n";
    echo "Название статуса: " . $status->status . "\n";
    echo "Активен: " . ($status->active ? 'Да' : 'Нет') . "\n";
}
```

---

## Fetch Company (просмотр компании)

**Payload:** `Palach\Omnidesk\UseCases\V1\FetchCompany\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\FetchCompany\Response` (содержит `CompanyData`).

Просмотр данных конкретной компании.

**Поля Payload:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| company_id | int | да | ID компании |

**CompanyData** (поле `company` в Response):
- `company_id` — ID компании
- `company_name` — Название компании
- `company_domains` — Домены компании
- `company_default_group` — ID группы по умолчанию
- `company_address` — Адрес компании
- `company_note` — Заметка
- `active` — Активна ли компания
- `deleted` — Удалена ли компания
- `created_at` — Дата создания
- `updated_at` — Дата обновления

Пример:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\CompaniesClient;
use Palach\Omnidesk\UseCases\V1\FetchCompany\Payload as FetchCompanyPayload;

/** @var CompaniesClient $companies */
$companies = Omnidesk::companies();

$payload = new FetchCompanyPayload(
    companyId: 200
);

$response = $companies->getCompany($payload);
$company = $response->company; // CompanyData

echo "ID компании: " . $company->companyId . "\n";
echo "Название компании: " . $company->companyName . "\n";
echo "Домены: " . $company->companyDomains . "\n";
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

## Fetch Custom Field List (получение списка кастомных полей)

**Response:** `Palach\Omnidesk\UseCases\V1\FetchCustomFieldList\Response` (поля: `customFields` — коллекция `CustomFieldData`, `totalCount` — общее количество).

Получает все кастомные поля, настроенные в системе. Принадлежность поля (к обращению или пользователю) указана в параметре `fieldLevel`.

Пример:

```php
use Palach\Omnidesk\Clients\CustomFieldsClient;
use Palach\Omnidesk\Omnidesk;

/** @var Omnidesk $http */
$http = app(Omnidesk::class);

/** @var CustomFieldsClient $customFields */
$customFields = $http->customFields();
$response = $customFields->fetchList();
$customFields = $response->customFields;
$totalCount = $response->totalCount;

// Перебор кастомных полей
foreach ($customFields as $customField) {
    echo "ID поля: " . $customField->fieldId . "\n";
    echo "Название: " . $customField->title . "\n";
    echo "Тип: " . $customField->fieldType . "\n";
    echo "Уровень: " . $customField->fieldLevel . "\n";
    echo "Активен: " . ($customField->active ? 'Да' : 'Нет') . "\n";
}
```

**Свойства CustomFieldData:**

| Поле | Тип | Описание |
|------|-----|----------|
| fieldId | int | Идентификатор поля |
| title | string | Название поля |
| fieldType | string | Тип поля (text, textarea, checkbox, select, date и др.) |
| fieldLevel | string | Уровень поля (user - для пользователя, case - для обращения) |
| active | bool | Активно ли поле |
| fieldData | array|string | Данные поля (для select - массив вариантов, для других - пустая строка) |

---

## Получение списка кастомных каналов (Fetch Custom Channel List)

**Response:** `Palach\Omnidesk\UseCases\V1\FetchCustomChannelList\Response` (поля: `customChannels` — коллекция `CustomChannelData`, `totalCount` — общее количество).

Получение всех кастомных каналов, настроенных в системе.

Пример:

```php
use Palach\Omnidesk\Clients\CustomChannelsClient;
use Palach\Omnidesk\Omnidesk;

/** @var Omnidesk $http */
$http = app(Omnidesk::class);

/** @var CustomChannelsClient $customChannels */
$customChannels = $http->customChannels();
$response = $customChannels->fetchList();
$customChannels = $response->customChannels;
$totalCount = $response->totalCount;

// Перебор кастомных каналов
foreach ($customChannels as $customChannel) {
    echo "ID канала: " . $customChannel->channelId . "\n";
    echo "Название: " . $customChannel->title . "\n";
    echo "Тип: " . $customChannel->channelType . "\n";
    echo "API ключ: " . $customChannel->channelApiKey . "\n";
    echo "Активен: " . ($customChannel->active ? 'Да' : 'Нет') . "\n";
}
```

**Свойства CustomChannelData:**

| Поле | Тип | Описание |
|------|-----|-------------|
| channelId | int | Идентификатор канала |
| channelApiKey | string | API ключ канала |
| title | string | Название канала |
| channelType | string | Тип канала (async, sync) |
| icon | string | Иконка канала (класс FontAwesome) |
| webhookUrl | string | URL вебхука для канала |
| active | bool | Активен ли канал |

---

## Fetch Client Email List (получение списка email-адресов клиента)

**Response:** `Palach\Omnidesk\UseCases\V1\FetchClientEmailList\Response` (поля: `clientEmails` — коллекция `ClientEmailData`, `totalCount` — общее количество).

Получает все email-адреса клиента.

Пример:

```php
use Palach\Omnidesk\Clients\ClientEmailsClient;
use Palach\Omnidesk\Omnidesk;

/** @var Omnidesk $http */
$http = app(Omnidesk::class);

/** @var ClientEmailsClient $clientEmails */
$clientEmails = $http->clientEmails();
$response = $clientEmails->fetchList();
$clientEmails = $response->clientEmails;
$totalCount = $response->totalCount;

// Перебор email-адресов
foreach ($clientEmails as $clientEmail) {
    echo "ID email: " . $clientEmail->emailId . "\n";
    echo "Email: " . $clientEmail->email . "\n";
    echo "Активен: " . ($clientEmail->active ? 'Да' : 'Нет') . "\n";
}
```

**Свойства ClientEmailData:**

| Поле | Тип | Описание |
|------|-----|----------|
| emailId | int | Идентификатор email-адреса |
| email | string | Email-адрес |
| active | bool | Активен ли email-адрес |

---

## Fetch Language List (получение списка языков)

**Response:** `Palach\Omnidesk\UseCases\V1\FetchLanguageList\Response` (поля: `languages` — коллекция `LanguageData`, `totalCount` — общее количество).

Получает все языки для поддержки, настроенные в аккаунте администратора.

Пример:

```php
use Palach\Omnidesk\Clients\LanguagesClient;
use Palach\Omnidesk\Omnidesk;

/** @var Omnidesk $http */
$http = app(Omnidesk::class);

/** @var LanguagesClient $languages */
$languages = $http->languages();
$response = $languages->fetchList();
$languages = $response->languages;
$totalCount = $response->totalCount;

// Перебор языков
foreach ($languages as $language) {
    echo "ID языка: " . $language->languageId . "\n";
    echo "Код: " . $language->code . "\n";
    echo "Название: " . $language->title . "\n";
    echo "Активен: " . ($language->active ? 'Да' : 'Нет') . "\n";
}
```

**Свойства LanguageData:**

| Поле | Тип | Описание |
|------|-----|----------|
| languageId | int | Идентификатор языка |
| code | string | Код языка (например, 'РУС', 'ENG') |
| title | string | Название языка (например, 'Русский', 'English') |
| active | bool | Активен ли язык |

---

## Fetch Macro List (получение списка шаблонов)

**Response:** `Palach\Omnidesk\UseCases\V1\FetchMacroList\Response` (поля: `common` — коллекция общих шаблонов `MacroCategoryData`, `personal` — коллекция личных шаблонов `MacroCategoryData`).

Получает список шаблонов из аккаунта администратора. Выводятся общие и личные шаблоны, разделённые на категории.

Пример:

```php
use Palach\Omnidesk\Clients\MacrosClient;
use Palach\Omnidesk\Omnidesk;

/** @var Omnidesk $http */
$http = app(Omnidesk::class);

/** @var MacrosClient $macros */
$macros = $http->macros();
$response = $macros->fetchList();

$commonMacros = $response->common;
$personalMacros = $response->personal;

// Перебор общих шаблонов
foreach ($commonMacros as $category) {
    echo "Категория: " . $category->title . "\n";
    foreach ($category->data as $macro) {
        echo "Шаблон: " . $macro->title . "\n";
        echo "Группа: " . $macro->groupName . "\n";
        foreach ($macro->actions as $action) {
            echo "Действие: " . $action->actionDisplayName . "\n";
            echo "Тип: " . $action->actionType . "\n";
        }
    }
}
```

**Свойства MacroCategoryData:**

| Поле | Тип | Описание |
|------|-----|----------|
| title | string | Название категории |
| sort | int | Порядок сортировки |
| macrosCategoryId | int | ID категории шаблонов |
| data | Collection<int, MacroData> | Коллекция шаблонов в категории |

**Свойства MacroData:**

| Поле | Тип | Описание |
|------|-----|----------|
| title | string | Название шаблона |
| position | int | Позиция шаблона |
| groupName | string | Название группы |
| actions | Collection<int, MacroActionData> | Коллекция действий шаблона |

**Свойства MacroActionData:**

| Поле | Тип | Описание |
|------|-----|----------|
| macroActionId | int | ID действия шаблона |
| actionType | string | Тип действия (add_note, email_to_user, group_id, status и т.д.) |
| actionDisplayName | string | Отображаемое название действия |
| actionDestination | string|array | Назначение действия (зависит от типа) |
| content | string|null | Содержимое действия |
| subject | string|null | Тема действия |
| position | int | Позиция действия |

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

## Unlink User (отвязывание профилей пользователей)

**Payload:** `Palach\Omnidesk\UseCases\V1\UnlinkUser\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\UnlinkUser\Response` (содержит `UserData`).

**Поля Payload:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| user_id | int | да | ID пользователя для отвязывания от пользователя указанного в URL |

**Параметры метода:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| user_id | int | да | ID пользователя (из URL) - от которого будет отвязываться другой пользователь |
| payload | UnlinkUserPayload | да | Данные для отвязывания |

Пример:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\UsersClient;
use Palach\Omnidesk\UseCases\V1\UnlinkUser\Payload as UnlinkUserPayload;

/** @var UsersClient $users */
$users = Omnidesk::users();

$payload = new UnlinkUserPayload(
    userId: 25830712,
);

$response = $users->unlinkUser(1307386, $payload);
$user = $response->user; // UserData с обновленным массивом linked_users
```

## Disable User (удаление пользователя)

**Response:** `Palach\Omnidesk\UseCases\V1\DisableUser\Response` (содержит `UserData`).

**Параметры метода:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| user_id | int | да | ID пользователя (из URL) - который будет удалён |

Пример:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\UsersClient;

/** @var UsersClient $users */
$users = Omnidesk::users();

$response = $users->disableUser(200);
$user = $response->user; // UserData с полем deleted = true
```

---

## Disable Company (удаление компании)

**Response:** `Palach\Omnidesk\UseCases\V1\DisabledCompany\Response` (содержит `CompanyData`).

**Параметры метода:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| company_id | int | да | ID компании (из URL) - которая будет удалена |

Пример:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\CompaniesClient;

/** @var CompaniesClient $companies */
$companies = Omnidesk::companies();

$response = $companies->disableCompany(200);
$company = $response->company; // CompanyData с полем deleted = true
```

#### Отключение группы

```php
/** @var GroupsClient $groups */
$groups = Omnidesk::groups();

$response = $groups->disableGroup(200, 300);
$group = $response->group; // GroupData с полем active = false
```

---

## Enable Group (включение группы)

**Response:** `Palach\Omnidesk\UseCases\V1\EnabledGroup\Response` (содержит `GroupData`).

**Параметры метода:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| group_id | int | да | ID группы (из URL) - которая будет включена |

Включение группы.

Ответ:
```json
{
  "group" : {
    "group_id" : 200,
    "group_title" : "Test group 2",
    "group_from_name" : "Test group 2 from name",
    "group_signature" : "Test group 2 signature",
    "active" : true,
    "created_at" : "Mon, 05 May 2014 00:15:17 +0300",
    "updated_at" : "Tue, 23 Dec 2014 10:55:23 +0200"
  }
}
```

Пример:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\GroupsClient;

/** @var GroupsClient $groups */
$groups = Omnidesk::groups();

$response = $groups->enableGroup(200);
$group = $response->group; // GroupData с полем active = true
```

---

## Delete Group (удаление группы)

**Payload:** `Palach\Omnidesk\UseCases\V1\DeleteGroup\Payload`  
**Response:** void (без тела ответа).

**DeleteGroupData** (поле `group` в Payload):

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| replace_group_id | int | да | ID группы, которая заменит удаляемую. Необходима в случае, если отключаемая группа где-то (в правилах, обращениях, шаблонах и т.д.) задействована |

**Параметры метода:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| group_id | int | да | ID группы (из URL) - которая будет удалена |
| payload | DeleteGroupPayload | да | Данные для удаления группы |

Удаление группы.

Пример:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\GroupsClient;
use Palach\Omnidesk\UseCases\V1\DeleteGroup\Payload as DeleteGroupPayload;

/** @var GroupsClient $groups */
$groups = Omnidesk::groups();

$payload = new DeleteGroupPayload(
    replaceGroupId: 300
);

$groups->deleteGroup(200, $payload);
```

---

## Block User (блокирование пользователя)

**Response:** `Palach\Omnidesk\UseCases\V1\BlockUser\Response` (содержит `UserData`).

**Параметры метода:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| user_id | int | да | ID пользователя (из URL) - который будет заблокирован |

Пример:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\UsersClient;

/** @var UsersClient $users */
$users = Omnidesk::users();

$response = $users->blockUser(200);
$user = $response->user; // UserData с полем active = false
```

---

## Block Company (блокирование компании)

**Response:** `Palach\Omnidesk\UseCases\V1\BlockCompany\Response` (содержит `CompanyData`).

**Параметры метода:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| company_id | int | да | ID компании (из URL) - которая будет заблокирована |

Блокирование компании. Все последующие обращения компании будут помечаться как спам.

Пример:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\CompaniesClient;

/** @var CompaniesClient $companies */
$companies = Omnidesk::companies();

$response = $companies->blockCompany(200);
$company = $response->company; // CompanyData с полем active = false
```

---

## Delete Company (удаление компании)

**Response:** `Palach\Omnidesk\UseCases\V1\DeleteCompany\Response` (содержит `CompanyData`).

**Параметры метода:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| company_id | int | да | ID компании (из URL) - которая будет удалена |

Удаление компании. В этом случае компания переносится в список удалённых и при необходимости её можно восстановить.

Пример:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\CompaniesClient;

/** @var CompaniesClient $companies */
$companies = Omnidesk::companies();

$response = $companies->deleteCompany(200);
$company = $response->company; // CompanyData с полем deleted = true
```

---

## Delete User (полное удаление пользователя)

**Response:** `Palach\Omnidesk\UseCases\V1\DeleteUser\Response` (содержит `UserData`).

**Параметры метода:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| user_id | int | да | ID пользователя (из URL) - который будет полностью удалён |

Полное удаление пользователя. Доступно только для сотрудников с полным доступом.

Пример:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\UsersClient;

/** @var UsersClient $users */
$users = Omnidesk::users();

$response = $users->deleteUser(200);
$user = $response->user; // UserData с полем deleted = true
```

---

## Recovery User (восстановление пользователя)

**Response:** `Palach\Omnidesk\UseCases\V1\RecoveryUser\Response` (содержит `UserData`).

**Параметры метода:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| user_id | int | да | ID пользователя |

Включение пользователя после блокировки или восстановление после удаления.

Пример:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\UsersClient;

/** @var UsersClient $users */
$users = Omnidesk::users();

$response = $users->recoveryUser(200);
$user = $response->user; // UserData с полями active = true и deleted = false
```

---

## Recovery Company (восстановление компании)

**Response:** `Palach\Omnidesk\UseCases\V1\RecoveryCompany\Response` (содержит `CompanyData`).

**Параметры метода:**

| Поле | Тип | Обязательное | Описание |
|------|-----|--------------|----------|
| company_id | int | да | ID компании |

Включение компании после блокировки или восстановление после удаления.

Пример:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\CompaniesClient;

/** @var CompaniesClient $companies */
$companies = Omnidesk::companies();

$response = $companies->recoveryCompany(200);
$company = $response->company; // CompanyData с полями active = true и deleted = false
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
