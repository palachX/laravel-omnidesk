## Omnidesk API (Clients and Use Cases)

The package provides a main `Palach\Omnidesk\Omnidesk` class for accessing the Omnidesk API, transport layer, and typed use cases for common operations.

## Omnidesk and clients

Class `Palach\Omnidesk\Omnidesk` is registered in the container as a singleton using configuration (host, email, api_key) from `config/omnidesk.php`.  
You can access it through the convenient `Palach\Omnidesk\Facades\Omnidesk` facade.

The main class exposes four typed clients:

- `Palach\Omnidesk\Clients\CasesClient` — operations with cases
- `Palach\Omnidesk\Clients\FiltersClient` — operations with filters
- `Palach\Omnidesk\Clients\MessagesClient` — operations with messages
- `Palach\Omnidesk\Clients\NotesClient` — operations with notes

Usage (inject via constructor or `app()`):

```php
// Recommended: Using Omnidesk facade
use Palach\Omnidesk\Facades\Omnidesk;

/** @var CasesClient $cases */
$cases = Omnidesk::cases();

/** @var FiltersClient $filters */
$filters = Omnidesk::filters();

/** @var MessagesClient $messages */
$messages = Omnidesk::messages();

/** @var NotesClient $notes */
$notes = Omnidesk::notes();

// Alternative: Direct class injection
use Palach\Omnidesk\Omnidesk;

/** @var Omnidesk $omnidesk */
$omnidesk = app(Omnidesk::class);
$cases = $omnidesk->cases();
$filters = $omnidesk->filters();
$messages = $omnidesk->messages();
$notes = $omnidesk->notes();
```

### Transport and authentication

Internally, both clients use `Palach\Omnidesk\Transport\OmnideskTransport`, which sends requests with HTTP Basic Auth (`email` and `api_key` from config) and headers: `Accept: application/json`.  
On network errors or unexpected response format, methods throw (`RequestException`, `ConnectionException`, `UnexpectedResponseException`).

### Methods

- **`$casesClient->store(StoreCasePayload $payload): StoreCaseResponse`** — create a case.
- **`$casesClient->fetchList(FetchCaseListPayload $payload): FetchCaseListResponse`** — list cases with pagination and filters.
- **`$casesClient->rate(RateCasePayload $payload): RateCaseResponse`** — rate a case.
- **`$casesClient->trashCase(TrashCasePayload $payload): TrashCaseResponse`** — move a case to trash.
- **`$casesClient->trashBulk(TrashCaseBulkPayload $payload): TrashCaseBulkResponse`** — move multiple cases to trash.
- **`$casesClient->restoreCase(RestoreCasePayload $payload): RestoreCaseResponse`** — restore a case from trash.
- **`$casesClient->restoreBulk(RestoreCaseBulkPayload $payload): RestoreCaseBulkResponse`** — restore multiple cases from trash.
- **`$casesClient->spamCase(SpamCasePayload $payload): SpamCaseResponse`** — mark a case as spam.
- **`$casesClient->spamBulk(SpamCaseBulkPayload $payload): SpamCaseBulkResponse`** — mark multiple cases as spam.
- **`$casesClient->deleteCase(DeleteCasePayload $payload): DeleteCaseResponse`** — permanently delete a case.
- **`$casesClient->deleteBulk(DeleteCaseBulkPayload $payload): DeleteCaseBulkResponse`** — permanently delete multiple cases.
- **`$casesClient->updateIdea(UpdateIdeaPayload $payload): UpdateIdeaResponse`** — update an idea (proposal).
- **`$casesClient->updateIdeaOfficialResponse(UpdateIdeaOfficialResponsePayload $payload): UpdateIdeaOfficialResponseResponse`** — update idea official response.
- **`$filtersClient->fetchList(FetchFilterListPayload $payload): FetchFilterListResponse`** — list filters for the authenticated employee.
- **`$messagesClient->store(StoreMessagePayload $payload): StoreMessageResponse`** — create a message in a case.
- **`$messagesClient->fetchMessages(FetchCaseMessagesPayload $payload): FetchCaseMessagesResponse`** — list messages for a specific case with pagination and sorting.
- **`$messagesClient->update(UpdateMessagePayload $payload): UpdateMessageResponse`** — update a message.
- **`$messagesClient->rate(RateMessagePayload $payload): RateMessageResponse`** — rate a message.
- **`$messagesClient->deleteMessage(DeleteMessagePayload $payload): DeleteMessageResponse`** — delete a message.
- **`$notesClient->deleteNote(DeleteNotePayload $payload): void`** — delete a note.

---

## Store Case (create case)

**Payload:** `Palach\Omnidesk\UseCases\V1\StoreCase\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\StoreCase\Response` (contains `CaseData`).

**CaseStoreData** (payload `case` field):

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| user_custom_id | string | yes | External user identifier |
| subject | string | yes | Case subject |
| content | string | yes | Plain text body |
| content_html | string | yes | HTML body |
| channel | string | yes | Channel |
| user_email | string | no* | User email |
| user_phone | string | no* | User phone |
| attachments | AttachmentData[] | no | Array of `AttachmentData` DTOs (binary file content as base64) |
| attachmentUrls | string[] | no | Array of URLs to files that should be attached to the case |

\* One of `user_email` or `user_phone` is required (`RequiredWithout` attribute).

Example:

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
        subject: 'Subject',
        content: 'Text',
        contentHtml: '<p>Text</p>',
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

## Fetch Case List (list cases)

**Payload:** `Palach\Omnidesk\UseCases\V1\FetchCaseList\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\FetchCaseList\Response` (fields: `cases` — collection of `CaseData`, `total` — total count).

Query parameters (all optional):

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| status | array\|Optional | — | Filter by statuses |
| channel | array\|Optional | — | Filter by channels |
| user_custom_id | array\|Optional | — | Filter by user_custom_id |
| page | int\|Optional | 1–500 | Page (default in Omnidesk API: 1) |
| limit | int\|Optional | 1–100 | Page size (default in API: 100) |
| show_active_chats | bool\|Optional | — | Include active chats |

GET request uses `Payload::toQuery()`.

Example:

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

## Fetch Filter List (list filters)

**Payload:** `Palach\Omnidesk\UseCases\V1\FetchFilterList\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\FetchFilterList\Response` (fields: `filters` — collection of `FilterData`, `totalCount` — total count).

Retrieves all filters for the authenticated employee.

Query parameters (all optional):

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| page | int\|Optional | 1–500 | Page (default in Omnidesk API: 1) |
| limit | int\|Optional | 1–100 | Page size (default in API: 100) |

GET request uses `Payload::toQuery()`.

Example:

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

// Iterate through filters
foreach ($filters as $filter) {
    echo "Filter ID: " . $filter->filterId . "\n";
    echo "Filter Name: " . $filter->filterName . "\n";
    echo "Is Selected: " . ($filter->isSelected ? 'Yes' : 'No') . "\n";
    echo "Is Custom: " . ($filter->isCustom ? 'Yes' : 'No') . "\n";
}
```

**FilterData properties:**

| Field | Type | Description |
|-------|------|-------------|
| filterId | int\|null | Filter identifier (numeric ID or null) |
| filterName | string | Filter name |
| isSelected | bool | Whether the filter is currently selected |
| isCustom | bool | Whether this is a custom filter |

---

## Store Message (create message)

**Payload:** `Palach\Omnidesk\UseCases\V1\StoreMessage\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\StoreMessage\Response` (field `message` — `MessageData`).

**MessageStoreData** (payload `message` field):

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| user_id | int | yes | Omnidesk user ID |
| content | string | yes | Message text |
| case_id | int | no* | Case ID |
| case_number | string | no* | Case number |
| attachments | AttachmentData[] | no | Array of `AttachmentData` DTOs (binary file content as base64) |
| attachmentUrls | string[] | no | Array of URLs to files that should be attached to the message |

\* One of `case_id` or `case_number` is required (`RequiredWithout`).

Example:

```php
use Palach\Omnidesk\Clients\MessagesClient;use Palach\Omnidesk\DTO\AttachmentData;use Palach\Omnidesk\Omnidesk;use Palach\Omnidesk\UseCases\V1\StoreMessage\MessageStoreData;use Palach\Omnidesk\UseCases\V1\StoreMessage\Payload as StoreMessagePayload;

/** @var Omnidesk $http */
$http = app(Omnidesk::class);

/** @var MessagesClient $messages */
$messages = $http->messages();
$payload = new StoreMessagePayload(
    message: new MessageStoreData(
        userId: 12345,
        content: 'Reply text',
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
$payload = new StoreMessagePayload(
    message: new MessageStoreData(
        userId: 12345,
        content: 'Reply text',
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

## Fetch Case Messages (list messages for a case)

**Payload:** `Palach\Omnidesk\UseCases\V1\FetchCaseMessages\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\FetchCaseMessages\Response` (fields: `messages` — collection of `MessageData`, `totalCount` — total count).

Retrieves messages for a specific case with pagination and sorting options.

Query parameters:

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| case_id | int | yes | Case ID (required) |
| page | int\|Optional | 1+ | Page number (default in Omnidesk API: shows last 100 messages if no page/limit) |
| limit | int\|Optional | 1–100 | Messages per page (default in API: 100) |
| order | string\|Optional | "asc", "desc" | Sort order (default in API: "asc" - from old to new) |

GET request uses `Payload::toQuery()`.

Example:

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

// Iterate through messages
foreach ($messages as $message) {
    echo "Message ID: " . $message->messageId . "\n";
    echo "Content: " . $message->content . "\n";
    echo "Created At: " . $message->createdAt . "\n";
}
```

---

## Update Message (update message)

**Payload:** `Palach\Omnidesk\UseCases\V1\UpdateMessage\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\UpdateMessage\Response` (field `message` — `MessageData`).

**MessageUpdateData** (payload `message` field):

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| message_id | int | yes | Message ID |
| content | string | yes | New text |
| case_id | int | no* | Case ID |
| case_number | string | no* | Case number |

\* One of `case_id` or `case_number`.

Example:

```php
use Palach\Omnidesk\Clients\MessagesClient;use Palach\Omnidesk\Omnidesk;use Palach\Omnidesk\UseCases\V1\UpdateMessage\MessageUpdateData;use Palach\Omnidesk\UseCases\V1\UpdateMessage\Payload as UpdateMessagePayload;

/** @var Omnidesk $http */
$http = app(Omnidesk::class);

/** @var MessagesClient $messages */
$messages = $http->messages();
$payload = new UpdateMessagePayload(
    message: new MessageUpdateData(
        messageId: 111222,
        content: 'Updated text',
        caseId: 98765,
    )
);
$response = $messages->update($payload);
$message = $response->message;
```

---

## Rate Message (rate message)

**Payload:** `Palach\Omnidesk\UseCases\V1\RateMessage\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\RateMessage\Response` (field `message` — `MessageData`).

**Payload fields:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| case_id | int | yes | Case ID |
| message_id | int | yes | Message ID |
| rate | RateMessageData | yes | Rating data |

**RateMessageData** (payload `rate` field):

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| rating | string | yes | Rating value (e.g., "positive", "negative") |
| rating_comment | string|Optional | no | Optional rating comment |

Example:

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
        ratingComment: 'Great support!',
    )
);
$response = $messages->rate($payload);
$message = $response->message; // MessageData
```

---

## Delete Message (delete message)

**Payload:** `Palach\Omnidesk\UseCases\V1\DeleteMessage\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\DeleteMessage\Response` (field `success` — boolean).

**Payload fields:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| case_id | int | yes | Case ID |
| message_id | int | yes | Message ID |

Example:

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

## Delete Note (delete note)

**Payload:** `Palach\Omnidesk\UseCases\V1\DeleteNote\Payload`  
**Response:** void (no response body).

**Payload fields:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| case_id | int | yes | Case ID |
| message_id | int | yes | Message ID |

Example:

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

## Trash Case (move case to trash)

**Payload:** `Palach\Omnidesk\UseCases\V1\TrashCase\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\TrashCase\Response` (field `case` — `CaseData`).

**Payload fields:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| case_id | int | yes | Case ID |

Example:

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

## Trash Case Bulk (move multiple cases to trash)

**Payload:** `Palach\Omnidesk\UseCases\V1\TrashCase\BulkPayload`  
**Response:** `Palach\Omnidesk\UseCases\V1\TrashCase\BulkResponse` (field `caseSuccessId` — array of successfully processed case IDs).

**Payload fields:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| case_ids | int[] | yes | Array of case IDs (max 10 per request) |

Example:

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
$successIds = $response->caseSuccessId; // array of successful case IDs
```

---

## Restore Case (restore case from trash)

**Payload:** `Palach\Omnidesk\UseCases\V1\RestoreCase\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\RestoreCase\Response` (field `case` — `CaseData`).

**Payload fields:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| case_id | int | yes | Case ID |

Example:

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

## Restore Case Bulk (restore multiple cases from trash)

**Payload:** `Palach\Omnidesk\UseCases\V1\RestoreCase\BulkPayload`  
**Response:** `Palach\Omnidesk\UseCases\V1\RestoreCase\BulkResponse` (field `caseSuccessId` — array of successfully processed case IDs).

**Payload fields:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| case_ids | int[] | yes | Array of case IDs (max 10 per request) |

Example:

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
$successIds = $response->caseSuccessId; // array of successful case IDs
```

---

## Rate Case (rate case)

**Payload:** `Palach\Omnidesk\UseCases\V1\RateCase\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\RateCase\Response` (field `case` — `CaseData`).

**Payload fields:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| case_id | int | yes | Case ID |
| rate | RateData | yes | Rating data |

**RateData** (payload `rate` field):

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| rating | string | yes | Rating value (e.g., "positive", "negative") |
| rating_comment | string|Optional | no | Optional rating comment |
| rated_staff_id | int|Optional | no | Optional ID of rated staff member |

Example:

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
        ratingComment: 'Excellent support!',
        ratedStaffId: 12345,
    )
);
$response = $cases->rate($payload);
$case = $response->case; // CaseData
```

---

## Spam Case (mark case as spam)

**Payload:** `Palach\Omnidesk\UseCases\V1\SpamCase\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\SpamCase\Response` (field `case` — `CaseData`).

**Payload fields:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| case_id | int | yes | Case ID |

Example:

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
$case = $response->case; // CaseData with spam: true
```

---

## Spam Case Bulk (mark multiple cases as spam)

**Payload:** `Palach\Omnidesk\UseCases\V1\SpamCase\BulkPayload`  
**Response:** `Palach\Omnidesk\UseCases\V1\SpamCase\BulkResponse` (field `caseSuccessId` — array of successfully processed case IDs).

**Payload fields:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| case_ids | int[] | yes | Array of case IDs (max 10 per request) |

Example:

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
$successIds = $response->caseSuccessId; // array of successful case IDs
```

---

## Update Idea (update idea/proposal)

**Payload:** `Palach\Omnidesk\UseCases\V1\UpdateIdea\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\UpdateIdea\Response` (contains `CaseData`).

**IdeaUpdateData** (payload `message` field):

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| content | string | no | Idea content |
| stage | string | no | Implementation stage (waiting, planned, in_progress, finished) |
| category_id | int | no | Category ID |

**Payload fields:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| case_id | int | yes | Case ID |
| message | IdeaUpdateData | yes | Idea update data |

Example:

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
        content: 'New content',
        stage: 'planned',
        categoryId: 319,
    )
);
$response = $cases->updateIdea($payload);
$case = $response->case; // CaseData with updated idea
```

---

## Update Idea Official Response (update idea official response)

**Payload:** `Palach\Omnidesk\UseCases\V1\UpdateIdeaOfficialResponse\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\UpdateIdeaOfficialResponse\Response` (contains `CaseData`).

**IdeaOfficialResponseUpdateData** (payload `message` field):

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| content | string | yes | Official response content |

**Payload fields:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| case_id | int | yes | Case ID |
| message | IdeaOfficialResponseUpdateData | yes | Official response update data |

Example:

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
        content: 'New official response',
    )
);
$response = $cases->updateIdeaOfficialResponse($payload);
$case = $response->case; // CaseData with updated official response
```

---

## Delete Case (permanently delete case)

**Payload:** `Palach\Omnidesk\UseCases\V1\DeleteCase\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\DeleteCase\Response` (field `case` — `CaseData`).

**Payload fields:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| case_id | int | yes | Case ID |

Example:

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

## Delete Case Bulk (permanently delete multiple cases)

**Payload:** `Palach\Omnidesk\UseCases\V1\DeleteCase\BulkPayload`  
**Response:** `Palach\Omnidesk\UseCases\V1\DeleteCase\BulkResponse` (field `caseSuccessId` — array of successfully processed case IDs).

**Payload fields:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| case_ids | int[] | yes | Array of case IDs (max 10 per request) |

Example:

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
$successIds = $response->caseSuccessId; // array of successful case IDs
```

---

## Response DTOs

- **CaseData** (`Omnidesk\DTO\CaseData`) — case structure from API responses. Contains optional `$attachments` field with an array of `FileData` objects.
- **MessageData** (`Omnidesk\DTO\MessageData`) — message structure from API responses. Contains optional `$attachments` field with an array of `FileData` objects.
- **AttachmentData** (`Omnidesk\DTO\AttachmentData`) — outgoing attachment DTO used in Store Case/Store Message payloads: `name`, `mimeType`, `content` (base64-encoded file body).
- **FileData** (`Omnidesk\DTO\FileData`) — attachment DTO in responses: `fileId`, `fileName`, `fileSize`, `mimeType`, `url`.

Fields match Omnidesk API responses and are mapped via Spatie Laravel Data (snake_case → camelCase).

## Omnidesk API endpoints

The client uses these paths relative to `host`:

- `POST /api/cases.json` — create case.
- `GET /api/cases.json` — list cases (query from `FetchCaseListPayload::toQuery()`).
- `POST /api/cases/{caseId}/rate.json` — rate case.
- `POST /api/cases/{caseIdOrNumber}/messages.json` — create message.
- `GET /api/cases/{caseId}/messages.json` — list case messages (query from `FetchCaseMessagesPayload::toQuery()`).
- `POST /api/cases/{caseIdOrNumber}/messages/{messageId}.json` — update message.
- `POST /api/cases/{caseId}/messages/{messageId}/rate.json` — rate message.
- `PUT /api/cases/{caseId}/trash.json` — move case to trash.
- `PUT /api/cases/{caseIds}/trash.json` — move multiple cases to trash.
- `PUT /api/cases/{caseId}/restore.json` — restore case from trash.
- `PUT /api/cases/{caseIds}/restore.json` — restore multiple cases from trash.
- `PUT /api/cases/{caseId}/spam.json` — mark case as spam.
- `PUT /api/cases/{caseIds}/spam.json` — mark multiple cases as spam.
- `PUT /api/cases/{caseId}/idea.json` — update idea/proposal.
- `PUT /api/cases/{caseId}/idea_official_response.json` — update idea official response.
- `DELETE /api/cases/{caseId}.json` — permanently delete case.
- `DELETE /api/cases/{caseIds}.json` — permanently delete multiple cases.
- `DELETE /api/cases/{caseId}/note/{messageId}.json` — delete note.

`caseIdOrNumber` is either `case_id` or `case_number` from the payload (the client picks one internally).
