# Omnidesk API (HttpClient, Clients and Use Cases)

The package provides an HTTP client facade for the Omnidesk API, transport layer, and typed use cases for common operations.

## HttpClient and clients

Class `Palach\Omnidesk\Facade\HttpClient` is registered in the container as a singleton using configuration (host, email, api_key) from `config/omnidesk.php`.  
It exposes two typed clients:

- `Palach\Omnidesk\Clients\CasesClient` — operations with cases
- `Palach\Omnidesk\Clients\MessagesClient` — operations with messages

Usage (inject via constructor or `app(HttpClient::class)`):

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

### Transport and authentication

Internally, both clients use `Palach\Omnidesk\Transport\OmnideskTransport`, which sends requests with HTTP Basic Auth (`email` and `api_key` from config) and headers: `Accept: application/json`.  
On network errors or unexpected response format, methods throw (`RequestException`, `ConnectionException`, `UnexpectedResponseException`).

### Methods

- **CasesClient::store(StoreCasePayload $payload): StoreCaseResponse** — create a case.
- **CasesClient::fetchList(FetchCaseListPayload $payload): FetchCaseListResponse** — list cases with pagination and filters.
- **MessagesClient::store(StoreMessagePayload $payload): StoreMessageResponse** — create a message in a case.
- **MessagesClient::update(UpdateMessagePayload $payload): UpdateMessageResponse** — update a message.

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
        content: 'Updated text',
        caseId: 98765,
    )
);
$response = $messages->update($payload);
$message = $response->message;
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
- `POST /api/cases/{caseIdOrNumber}/messages.json` — create message.
- `POST /api/cases/{caseIdOrNumber}/messages/{messageId}.json` — update message.

`caseIdOrNumber` is either `case_id` or `case_number` from the payload (the client picks one internally).
