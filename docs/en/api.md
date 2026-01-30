# Omnidesk API (HttpClient and Use Cases)

The package provides an HTTP client for the Omnidesk API and typed use cases for common operations.

## HttpClient

Class `Omnidesk\Services\HttpClient` is registered in the container as a singleton and uses configuration (host, email, api_key) from `config/omnidesk.php`.

Usage (inject via constructor or `app(HttpClient::class)`):

```php
use Omnidesk\Services\HttpClient;
```

### Authentication

All requests use HTTP Basic Auth with `email` and `api_key` from config. Headers: `Content-Type: application/json`, `Accept: application/json`.

### Methods

- **storeCase(StoreCasePayload $payload): StoreCaseResponse** — create a case.
- **fetchCaseList(FetchCaseListPayload $payload): FetchCaseListResponse** — list cases with pagination and filters.
- **storeMessage(StoreMessagePayload $payload): StoreMessageResponse** — create a message in a case.
- **updateMessage(UpdateMessagePayload $payload): UpdateMessageResponse** — update a message.

On network errors or unexpected response format, methods throw (`RequestException`, `ConnectionException`, `UnexpectedResponseException`).

---

## Store Case (create case)

**Payload:** `Omnidesk\UseCases\V1\StoreCase\Payload`  
**Response:** `Omnidesk\UseCases\V1\StoreCase\Response` (contains `CaseData`).

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

\* One of `user_email` or `user_phone` is required (`RequiredWithout` attribute).

Example:

```php
use Omnidesk\Services\HttpClient;
use Omnidesk\UseCases\V1\StoreCase\CaseStoreData;
use Omnidesk\UseCases\V1\StoreCase\Payload as StoreCasePayload;

$client = app(HttpClient::class);
$payload = new StoreCasePayload(
    case: new CaseStoreData(
        userCustomId: 'ext-123',
        subject: 'Subject',
        content: 'Text',
        contentHtml: '<p>Text</p>',
        channel: 'email',
        userEmail: 'user@example.com',
    )
);
$response = $client->storeCase($payload);
$case = $response->case; // CaseData
```

---

## Fetch Case List (list cases)

**Payload:** `Omnidesk\UseCases\V1\FetchCaseList\Payload`  
**Response:** `Omnidesk\UseCases\V1\FetchCaseList\Response` (fields: `cases` — collection of `CaseData`, `total` — total count).

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

## Store Message (create message)

**Payload:** `Omnidesk\UseCases\V1\StoreMessage\Payload`  
**Response:** `Omnidesk\UseCases\V1\StoreMessage\Response` (field `message` — `MessageData`).

**MessageStoreData** (payload `message` field):

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| user_id | int | yes | Omnidesk user ID |
| content | string | yes | Message text |
| case_id | int | no* | Case ID |
| case_number | string | no* | Case number |

\* One of `case_id` or `case_number` is required (`RequiredWithout`).

Example:

```php
use Omnidesk\Services\HttpClient;
use Omnidesk\UseCases\V1\StoreMessage\MessageStoreData;
use Omnidesk\UseCases\V1\StoreMessage\Payload as StoreMessagePayload;

$client = app(HttpClient::class);
$payload = new StoreMessagePayload(
    message: new MessageStoreData(
        userId: 12345,
        content: 'Reply text',
        caseId: 98765,
    )
);
$response = $client->storeMessage($payload);
$message = $response->message; // MessageData
```

---

## Update Message (update message)

**Payload:** `Omnidesk\UseCases\V1\UpdateMessage\Payload`  
**Response:** `Omnidesk\UseCases\V1\UpdateMessage\Response` (field `message` — `MessageData`).

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
use Omnidesk\Services\HttpClient;
use Omnidesk\UseCases\V1\UpdateMessage\MessageUpdateData;
use Omnidesk\UseCases\V1\UpdateMessage\Payload as UpdateMessagePayload;

$client = app(HttpClient::class);
$payload = new UpdateMessagePayload(
    message: new MessageUpdateData(
        messageId: 111222,
        content: 'Updated text',
        caseId: 98765,
    )
);
$response = $client->updateMessage($payload);
$message = $response->message;
```

---

## Response DTOs

- **CaseData** (`Omnidesk\DTO\CaseData`) — case structure from API responses.
- **MessageData** (`Omnidesk\DTO\MessageData`) — message structure from API responses.

Fields match Omnidesk API responses and are mapped via Spatie Laravel Data (snake_case → camelCase).

## Omnidesk API endpoints

The client uses these paths relative to `host`:

- `POST /api/cases.json` — create case.
- `GET /api/cases.json` — list cases (query from `FetchCaseListPayload::toQuery()`).
- `POST /api/cases/{caseIdOrNumber}/messages.json` — create message.
- `POST /api/cases/{caseIdOrNumber}/messages/{messageId}.json` — update message.

`caseIdOrNumber` is either `case_id` or `case_number` from the payload (the client picks one internally).
