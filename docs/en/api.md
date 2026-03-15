## Omnidesk API (Clients and Use Cases)

The package provides a main `Palach\Omnidesk\Omnidesk` class for accessing the Omnidesk API, transport layer, and typed use cases for common operations.

## Omnidesk and clients

Class `Palach\Omnidesk\Omnidesk` is registered in the container as a singleton using configuration (host, email, api_key) from `config/omnidesk.php`.  
You can access it through the convenient `Palach\Omnidesk\Facades\Omnidesk` facade.

The main class exposes thirteen typed clients:

- `Palach\Omnidesk\Clients\CasesClient` — operations with cases
- `Palach\Omnidesk\Clients\ClientEmailsClient` — operations with client emails
- `Palach\Omnidesk\Clients\CompaniesClient` — operations with companies
- `Palach\Omnidesk\Clients\CustomFieldsClient` — operations with custom fields
- `Palach\Omnidesk\Clients\CustomChannelsClient` — operations with custom channels
- `Palach\Omnidesk\Clients\StaffClient` — operations with staff
- `Palach\Omnidesk\Clients\FiltersClient` — operations with filters
- `Palach\Omnidesk\Clients\GroupsClient` — operations with groups
- `Palach\Omnidesk\Clients\LabelsClient` — operations with labels
- `Palach\Omnidesk\Clients\LanguagesClient` — operations with languages
- `Palach\Omnidesk\Clients\MacrosClient` — operations with macros
- `Palach\Omnidesk\Clients\MessagesClient` — operations with messages
- `Palach\Omnidesk\Clients\NotesClient` — operations with notes
- `Palach\Omnidesk\Clients\UsersClient` — operations with users

Usage (inject via constructor or `app()`):

```php
// Recommended: Using Omnidesk facade
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

// Alternative: Direct class injection
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
$labels = $omnidesk->labels();
$languages = $omnidesk->languages();
$macros = $omnidesk->macros();
$messages = $omnidesk->messages();
$notes = $omnidesk->notes();
$users = $omnidesk->users();
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
- **`$casesClient->deleteIdeaOfficialResponse(DeleteIdeaOfficialResponsePayload $payload): void`** — delete idea official response.
- **`$clientEmailsClient->fetchList(): FetchClientEmailListResponse`** — list client emails.
- **`$customFieldsClient->fetchList(): FetchCustomFieldListResponse`** — list custom fields.
- **`$customChannelsClient->fetchList(): FetchCustomChannelListResponse`** — list custom channels.
- **`$languagesClient->fetchList(): FetchLanguageListResponse`** — list languages.
- **`$filtersClient->fetchList(FetchFilterListPayload $payload): FetchFilterListResponse`** — list filters for the authenticated employee.
- **`$macrosClient->fetchList(): FetchMacroListResponse`** — list macros (common and personal).
- **`$labelsClient->store(StoreLabelPayload $payload): StoreLabelResponse`** — create a label.
- **`$labelsClient->fetchList(FetchLabelListPayload $payload): FetchLabelListResponse`** — list labels with pagination.
- **`$labelsClient->updateLabel(UpdateLabelPayload $payload): UpdateLabelResponse`** — update a label.
- **`$labelsClient->deleteLabel(DeleteLabelPayload $payload): void`** — delete a label.
- **`$messagesClient->store(StoreMessagePayload $payload): StoreMessageResponse`** — create a message in a case.
- **`$messagesClient->fetchMessages(FetchCaseMessagesPayload $payload): FetchCaseMessagesResponse`** — list messages for a specific case with pagination and sorting.
- **`$messagesClient->update(UpdateMessagePayload $payload): UpdateMessageResponse`** — update a message.
- **`$messagesClient->rate(RateMessagePayload $payload): RateMessageResponse`** — rate a message.
- **`$messagesClient->deleteMessage(DeleteMessagePayload $payload): DeleteMessageResponse`** — delete a message.
- **`$notesClient->deleteNote(DeleteNotePayload $payload): void`** — delete a note.
- **`$staffClient->store(StoreStaffPayload $payload): StoreStaffResponse`** — create a staff member.
- **`$staffClient->update(int $staffId, UpdateStaffPayload $payload): UpdateStaffResponse`** — update a staff member.
- **`$staffClient->disableStaff(int $staffId, DisabledStaffPayload $payload): DisabledStaffResponse`** — disable a staff member.
- **`$staffClient->enableStaff(int $staffId): EnabledStaffResponse`** — enable a staff member.
- **`$staffClient->deleteStaff(int $staffId, DeleteStaffPayload $payload): DeleteStaffResponse`** — delete a staff member.
- **`$staffClient->fetchStaff(FetchStaffPayload $payload): FetchStaffResponse`** — fetch a specific staff member by ID.
- **`$staffClient->fetchStaffList(?FetchStaffListPayload $payload): FetchStaffListResponse`** — list staff members with pagination and filters.
- **`$staffClient->fetchStaffRoleList(): FetchStaffRoleListResponse`** — list staff roles.
- **`$staffClient->fetchStaffStatusList(): FetchStaffStatusListResponse`** — list staff statuses.
- **`$companiesClient->store(StoreCompanyPayload $payload): StoreCompanyResponse`** — create a company.
- **`$companiesClient->update(int $companyId, UpdateCompanyPayload $payload): UpdateCompanyResponse`** — update a company.
- **`$companiesClient->fetchCompanyList(?FetchCompanyListPayload $payload): FetchCompanyListResponse`** — list companies with pagination and filters.
- **`$companiesClient->getCompany(FetchCompanyPayload $payload): FetchCompanyResponse`** — fetch a single company by ID.
- **`$companiesClient->deleteCompany(int $companyId): DeleteCompanyResponse`** — delete a company (move to deleted list).
- **`$companiesClient->blockCompany(int $companyId): BlockCompanyResponse`** — block company (all subsequent company requests will be marked as spam).
- **`$companiesClient->disableCompany(int $companyId): DisabledCompanyResponse`** — disable company (move to deleted list).
- **`$companiesClient->recoveryCompany(int $companyId): RecoveryCompanyResponse`** — recover company after blocking or deletion.
- **`$groupsClient->getGroup(FetchGroupPayload $payload): FetchGroupResponse`** — fetch a single group by ID.
- **`$groupsClient->store(StoreGroupPayload $payload): StoreGroupResponse`** — create a group.
- **`$groupsClient->update(int $groupId, UpdateGroupPayload $payload): UpdateGroupResponse`** — update a group.
- **`$groupsClient->fetchList(FetchGroupListPayload $payload): FetchGroupListResponse`** — get list of groups with pagination.
- **`$groupsClient->disableGroup(int $groupId, int $replaceGroupId): DisabledGroupResponse`** — disable a group.
- **`$groupsClient->enableGroup(int $groupId): EnabledGroupResponse`** — enable a group.
- **`$groupsClient->deleteGroup(int $groupId, DeleteGroupPayload $payload): void`** — delete a group.
- **`$usersClient->fetch(FetchUserPayload $payload): FetchUserResponse`** — fetch a single user by ID.
- **`$usersClient->store(StoreUserPayload $payload): StoreUserResponse`** — create a user.
- **`$usersClient->update(int $userId, UpdateUserPayload $payload): UpdateUserResponse`** — update a user.
- **`$usersClient->fetchList(FetchUserListPayload $payload): FetchUserListResponse`** — list users with pagination and filters.
- **`$usersClient->fetchUserIdentification(FetchUserIdentificationPayload $payload): FetchUserIdentificationResponse`** — get user identification code.
- **`$usersClient->linkUser(int $userId, LinkUserPayload $payload): LinkUserResponse`** — link user profiles.
- **`$usersClient->unlinkUser(int $userId, UnlinkUserPayload $payload): UnlinkUserResponse`** — unlink user profiles.
- **`$usersClient->disableUser(int $userId): DisableUserResponse`** — disable user (move to deleted list).
- **`$usersClient->blockUser(int $userId): BlockUserResponse`** — block user (all subsequent user requests will be marked as spam).
- **`$usersClient->deleteUser(int $userId): DeleteUserResponse`** — permanently delete user (available only for employees with full access).
- **`$usersClient->recoveryUser(int $userId): RecoveryUserResponse`** — recover user after blocking or deletion.

---

## Update User (update user)

**Payload:** `Palach\Omnidesk\UseCases\V1\UpdateUser\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\UpdateUser\Response` (contains `UserData`).

**UserUpdateData** (payload `user` field):

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| user_email | string | no | New email address (can only be changed while not confirmed) |
| user_phone | string | no | New phone number (applicable only to phone type users) |
| user_full_name | string | no | Full name |
| company_name | string | no | Company name |
| company_position | string | no | Position |
| user_note | string | no | User note |
| language_id | int | no | User language |
| custom_fields | array | no | Custom fields |

**Method parameters:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| user_id | int | yes | User ID |
| payload | UpdateUserPayload | yes | Update data |

Example:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\UsersClient;
use Palach\Omnidesk\UseCases\V1\UpdateUser\UserUpdateData;
use Palach\Omnidesk\UseCases\V1\UpdateUser\Payload as UpdateUserPayload;

/** @var UsersClient $users */
$users = Omnidesk::users();

$payload = new UpdateUserPayload(
    user: new UserUpdateData(
        userFullName: "User's full name changed",
        languageId: 1,
        customFields: [
            'cf_20' => 'some data',
            'cf_23' => true,
        ]
    )
);

$response = $users->update(200, $payload);
$user = $response->user; // UserData
```

---

## Fetch User (get user)

**Payload:** `Palach\Omnidesk\UseCases\V1\FetchUser\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\FetchUser\Response` (contains `UserData`).

**UserFetchData** (payload `user` field):

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| user_id | int | yes | User ID |

Example:

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

## Store Label (create label)

**Payload:** `Palach\Omnidesk\UseCases\V1\StoreLabel\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\StoreLabel\Response` (contains `LabelData`).

**LabelStoreData** (payload `label` field):

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| label_title | string | yes | Label title |

Example:

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

## Fetch Label List (list labels)

**Payload:** `Palach\Omnidesk\UseCases\V1\FetchLabelList\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\FetchLabelList\Response` (fields: `labels` — collection of `LabelData`, `total` — total count).

Get a list of labels.

Request parameters:

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| page | int | 1–500 | Page number (default: 1) |
| limit | int | 1–100 | Labels per page (default: 100) |

For GET requests, use the `Payload::toQuery()` method.

Example:

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
// Or with default parameters:
// $payload = new FetchLabelListPayload();
$response = $labels->fetchList($payload);
$labels = $response->labels;
$total = $response->total;

// Iterate over labels
foreach ($labels as $label) {
    echo "Label ID: " . $label->labelId . "\n";
    echo "Label title: " . $label->labelTitle . "\n";
}
```

---

## Update Label (update label)

**Payload:** `Palach\Omnidesk\UseCases\V1\UpdateLabel\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\UpdateLabel\Response` (contains `LabelData`).

**LabelUpdateData** (payload `label` field):

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| label_title | string | yes | Label title |

**Payload fields:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| label_id | int | yes | Label ID |
| label | LabelUpdateData | yes | Label update data |

Example:

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
        labelTitle: 'New label title'
    )
);
$response = $labels->updateLabel($payload);
$label = $response->label; // LabelData
```

---

## Delete Label (delete label)

**Payload:** `Palach\Omnidesk\UseCases\V1\DeleteLabel\Payload`  
**Response:** void (no response body).

**Payload fields:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id | int | yes | Label ID |

Example:

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

## Store User (create user)

**Payload:** `Palach\Omnidesk\UseCases\V1\StoreUser\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\StoreUser\Response` (contains `UserData`).

**UserStoreData** (payload `user` field):

**Required fields (one of):**
- `user_email` — user email
- `user_phone` — user phone  
- `user_whatsapp_phone` — WhatsApp phone
- `user_vkontakte` — VKontakte ID
- `user_odnoklassniki` — Odnoklassniki ID
- `user_facebook` — Facebook ID
- `user_instagram` — Instagram username
- `user_telegram` — Telegram ID
- `user_telegram_data` — phone or username for Telegram
- `user_viber` — Viber ID
- `user_skype` — Skype ID
- `user_line` — Line ID
- `user_slack` — Slack ID
- `user_mattermost` — Mattermost ID
- `user_avito` — Avito ID
- `user_custom_id` — Custom channel ID

**Optional fields:**
- `user_custom_channel` — Custom channel ID (required when using `user_custom_id`)
- `user_full_name` — User full name
- `company_name` — Company name
- `company_position` — Position
- `user_note` — User note
- `language_id` — User language
- `custom_fields` — Array of custom fields

**UserData** (response `user` field):
- `user_id` — User ID
- All request fields plus service fields (`created_at`, `updated_at`, `confirmed`, `active`, `deleted`, `password`, `type`, `thumbnail`, `linked_users`)

Example:

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

## Store Company (create company)

**Payload:** `Palach\Omnidesk\UseCases\V1\StoreCompany\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\StoreCompany\Response` (contains `CompanyData`).

**Payload Parameters:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| company_name | string | yes | Company name |
| company_domains | string|null | no | Multiple domains can be specified, separated by commas |
| company_default_group | int|null | no | Group ID |
| company_address | string|null | no | Company address |
| company_note | string|null | no | Note |
| company_users | string|null | no | Comma-separated user IDs that should be added to the company |

**CompanyData** (response `company` field):
- `company_id` — Company ID
- All request fields plus service fields (`active`, `deleted`, `created_at`, `updated_at`)

Example:

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

## Store Staff (create staff member)

**Payload:** `Palach\Omnidesk\UseCases\V1\StoreStaff\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\StoreStaff\Response` (contains `StaffData`).

**Payload Parameters:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| staff_email | string | yes | Valid email address of the staff member |
| staff_full_name | string | no | Full name of the staff member |
| staff_signature | string | no | Staff member signature for email cases |
| staff_signature_chat | string | no | Staff member signature for chats |

**StaffData** (response `staff` field):
- `staff_id` — Staff member ID
- All request fields plus service fields (`thumbnail`, `active`, `created_at`, `updated_at`)

Example:

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
$staffMember = $response->staff; // StaffData
```

---

## Update Staff (update staff member)

**Payload:** `Palach\Omnidesk\UseCases\V1\UpdateStaff\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\UpdateStaff\Response` (contains `StaffData`).

**StaffUpdateData** (payload `staff` field):

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| staff_email | string | no | New email address of the staff member |
| staff_full_name | string | no | Full name of the staff member |
| staff_signature | string | no | Staff member signature for email cases |
| staff_signature_chat | string | no | Staff member signature for chats |

**Method parameters:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| staff_id | int | yes | Staff member ID |
| payload | UpdateStaffPayload | yes | Update data |

Example:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\StaffsClient;
use Palach\Omnidesk\UseCases\V1\UpdateStaff\Payload as UpdateStaffPayload;
use Palach\Omnidesk\UseCases\V1\UpdateStaff\StaffUpdateData;

/** @var StaffsClient $staff */
$staff = Omnidesk::staff();

$payload = new UpdateStaffPayload(
    staff: new StaffUpdateData(
        staffFullName: "Staff full name changed",
        staffSignature: 'Updated signature for email cases',
        staffSignatureChat: 'Updated signature for chats'
    )
);

$response = $staff->update(200, $payload);
$staffMember = $response->staff; // StaffData
```

---

## Disable Staff (disable staff member)

**Payload:** `Palach\Omnidesk\UseCases\V1\DisabledStaff\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\DisabledStaff\Response` (contains `StaffData`).

Disable a staff member.

**Method parameters:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| replace_staff_id | int | yes | ID of the staff member who will replace the disabled staff member in rule settings, common templates, and case parameters with "open" and "waiting" status |

**Example usage:**

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

## Enable Staff (enable staff member)

**Response:** `Palach\Omnidesk\UseCases\V1\EnabledStaff\Response` (contains `StaffData`).

Enable a staff member.

**Method parameters:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| staff_id | int | yes | Staff member ID |

**Example usage:**

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\StaffsClient;

/** @var StaffsClient $staff */
$staff = Omnidesk::staff();

$response = $staff->enableStaff(200);
$enabledStaff = $response->staff; // StaffData
```

---

## Delete Staff (delete staff member)

**Payload:** `Palach\Omnidesk\UseCases\V1\DeleteStaff\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\DeleteStaff\Response` (contains `StaffData`).

**Payload Parameters:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| replace_staff_id | int | yes | ID of the staff member who will replace the deleted staff member in rule settings, common templates, and parameters of all cases (with any status) |

**Method parameters:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| staff_id | int | yes | Staff member ID (from URL) - the staff member to be deleted |
| payload | DeleteStaffPayload | yes | Delete data |

Delete a staff member.

Example:

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
$deletedStaff = $response->staff; // StaffData with deleted = true
```

---

## Fetch Staff (view staff member)

**Payload:** `Palach\Omnidesk\UseCases\V1\FetchStaff\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\FetchStaff\Response` (fields: `staff` — `StaffData`).

View data for a specific staff member.

Request parameters:

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| staff_id | int | Required | Staff member ID |
| language_id | string | Optional | Language ID for localized staff data |

For GET requests, use the `Payload::toQuery()` method.

Example:

```php
use Palach\Omnidesk\Clients\StaffClient;
use Palach\Omnidesk\Omnidesk;
use Palach\Omnidesk\UseCases\V1\FetchStaff\Payload as FetchStaffPayload;

/** @var Omnidesk $http */
$http = app(Omnidesk::class);

/** @var StaffClient $staff */
$staff = $http->staff();
$payload = new FetchStaffPayload(
    staffId: 200,
    languageId: 'en',
);
// Or without language:
// $payload = new FetchStaffPayload(staffId: 200);
$response = $staff->fetchStaff($payload);
$staffMember = $response->staff; // StaffData

echo "Staff ID: " . $staffMember->staffId . "\n";
echo "Email: " . $staffMember->staffEmail . "\n";
echo "Active: " . ($staffMember->active ? 'Yes' : 'No') . "\n";
echo "Status: " . $staffMember->status . "\n";
```

---

## Fetch Staff List (list staff members)

**Payload:** `Palach\Omnidesk\UseCases\V1\FetchStaffList\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\FetchStaffList\Response` (fields: `staff` — collection of `StaffData`, `total` — total count).

Get a list of staff members with pagination.

Request parameters:

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| page | int | 1–500 | Page number (default: 1) |
| limit | int | 1–100 | Staff members per page (default: 100) |
| language_id | string | — | Language ID for localized staff data |

For GET requests, use the `Payload::toQuery()` method.

Example:

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
    languageId: 'en',
);
// Or with default parameters:
// $payload = new FetchStaffListPayload();
$response = $staff->fetchStaffList($payload);
$staffMembers = $response->staff;
$total = $response->total;

// Iterate over staff members
foreach ($staffMembers as $member) {
    echo "Staff ID: " . $member->staffId . "\n";
    echo "Email: " . $member->staffEmail . "\n";
    echo "Active: " . ($member->active ? 'Yes' : 'No') . "\n";
    echo "Status: " . $member->status . "\n";
}
```

---

## Fetch Group (view group)

**Payload:** `Palach\Omnidesk\UseCases\V1\FetchGroup\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\FetchGroup\Response` (fields: `group` — `GroupData`).

View a specific group by ID.

Request parameters:

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| group_id | int | Required | Group ID |

Example:

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

## Store Group (create group)

**Payload:** `Palach\Omnidesk\UseCases\V1\StoreGroup\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\StoreGroup\Response` (contains `GroupData`).

**Payload Parameters:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| group_title | string | yes | Group title |
| group_from_name | string|null | no | Group sender name for use in staff replies |
| group_signature | string|null | no | Group signature for use in staff replies |

**GroupData** (response `group` field):
- `group_id` — Group ID
- All request fields plus service fields (`active`, `created_at`, `updated_at`)

Example:

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

## Fetch Group List (get group list)

**Payload:** `Palach\Omnidesk\UseCases\V1\FetchGroupList\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\FetchGroupList\Response` (fields: `groups` — collection of `GroupData`, `total` — total count).

Get list of groups.

Request parameters:

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| page | int | 1–500 | Page number (default: 1) |
| limit | int | 1–100 | Groups per page limit (default: 100) |

For GET requests, use the `Payload::toQuery()` method.

Example:

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
// Or with default parameters:
// $payload = new FetchGroupListPayload();
$response = $groups->fetchList($payload);
$groups = $response->groups;
$total = $response->total;

// Iterate through groups
foreach ($groups as $group) {
    echo "Group ID: " . $group->groupId . "\n";
    echo "Group Title: " . $group->groupTitle . "\n";
    echo "From Name: " . $group->groupFromName . "\n";
    echo "Signature: " . $group->groupSignature . "\n";
    echo "Active: " . ($group->active ? 'Yes' : 'No') . "\n";
}
```

---

## Update Company (update company)

**Payload:** `Palach\Omnidesk\UseCases\V1\UpdateCompany\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\UpdateCompany\Response` (contains `CompanyData`).

**CompanyUpdateData** (payload `company` field):

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| company_name | string | no | Company name |
| add_company_domains | string | no | Add domains for automatic user binding (multiple domains can be specified, separated by commas) |
| remove_company_domains | string | no | Remove domains for automatic user binding (multiple domains can be specified, separated by commas) |
| company_default_group | string | no | Group ID |
| company_address | string | no | Company address |
| company_note | string | no | Note |
| add_company_users | string | no | Comma-separated user IDs to add to the company |
| remove_company_users | string | no | Comma-separated user IDs to remove from the company |

**Method parameters:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| company_id | int | yes | Company ID |
| payload | UpdateCompanyPayload | yes | Update data |

Example:

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

## Update Group (update group)

**Payload:** `Palach\Omnidesk\UseCases\V1\UpdateGroup\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\UpdateGroup\Response` (contains `GroupData`).

**GroupUpdateData** (payload `group` field):

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| group_title | string | no | Group title |
| group_from_name | string | no | Group sender name for use in employee responses |
| group_signature | string | no | Group signature for use in employee responses |

**Method parameters:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| group_id | int | yes | Group ID |
| payload | UpdateGroupPayload | yes | Update data |

Example:

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

## Fetch Company List (list companies)

**Payload:** `Palach\Omnidesk\UseCases\V1\FetchCompanyList\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\FetchCompanyList\Response` (fields: `companies` — collection of `CompanyData`, `totalCount` — total count).

Get list of companies with pagination and filters.

Query parameters (all optional):

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| page | int\|Optional | 1–500 | Page number (default in Omnidesk API: 1) |
| limit | int\|Optional | 1–100 | Companies per page limit (default in API: 100) |
| company_name | string\|Optional | — | Search companies by name (min 3 characters) |
| company_domains | string\|Optional | — | Search companies by domain (min 3 characters) |
| company_address | string\|Optional | — | Search companies by address (min 3 characters) |
| company_note | string\|Optional | — | Search companies by note (min 3 characters) |
| amount_of_users | bool\|Optional | — | Company user count (default: false) |
| amount_of_cases | bool\|Optional | — | Company case count (default: false) |

For GET requests use `Payload::toQuery()` method.

Example:

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
// Or with default parameters:
// $payload = new FetchCompanyListPayload();
$response = $companies->fetchCompanyList($payload);
$companies = $response->companies;
$totalCount = $response->totalCount;

// Iterate companies
foreach ($companies as $company) {
    echo "Company ID: " . $company->companyId . "\n";
    echo "Company Name: " . $company->companyName . "\n";
    echo "User Count: " . $company->amountOfUsers . "\n";
}
```

---

## Fetch Staff Role List (list staff roles)

**Response:** `Palach\Omnidesk\UseCases\V1\FetchStaffRoleList\Response` (fields: `staffRoles` — collection of `StaffRoleData`, `count` — total count).

Get list of staff roles.

Example:

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

// Iterate staff roles
foreach ($staffRoles as $role) {
    echo "Role ID: " . $role->roleId . "\n";
    echo "Role Name: " . $role->role . "\n";
}
```

---

## Fetch Staff Status List (list staff statuses)

**Response:** `Palach\Omnidesk\UseCases\V1\FetchStaffStatusList\Response` (fields: `staffStatuses` — collection of `StaffStatusData`, `count` — total count).

Get list of staff statuses.

Example:

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

// Iterate staff statuses
foreach ($staffStatuses as $status) {
    echo "Status ID: " . $status->statusId . "\n";
    echo "Status Name: " . $status->status . "\n";
    echo "Active: " . ($status->active ? 'Yes' : 'No') . "\n";
}
```

---

## Fetch Company (view company)

**Payload:** `Palach\Omnidesk\UseCases\V1\FetchCompany\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\FetchCompany\Response` (contains `CompanyData`).

View data of a specific company.

**Payload Fields:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| company_id | int | yes | Company ID |

**CompanyData** (field `company` in Response):
- `company_id` — Company ID
- `company_name` — Company name
- `company_domains` — Company domains
- `company_default_group` — Default group ID
- `company_address` — Company address
- `company_note` — Note
- `active` — Whether company is active
- `deleted` — Whether company is deleted
- `created_at` — Creation date
- `updated_at` — Update date

Example:

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

echo "Company ID: " . $company->companyId . "\n";
echo "Company Name: " . $company->companyName . "\n";
echo "Domains: " . $company->companyDomains . "\n";
```

---

## Delete Company (delete company)

**Response:** `Palach\Omnidesk\UseCases\V1\DeleteCompany\Response` (contains `CompanyData`).

**Method parameters:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| company_id | int | yes | Company ID (from URL) - to be deleted |

Delete a company. In this case, the company is moved to the deleted list and can be restored if necessary.

Example:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\CompaniesClient;

/** @var CompaniesClient $companies */
$companies = Omnidesk::companies();

$response = $companies->deleteCompany(200);
$company = $response->company; // CompanyData with deleted = true
```

---

## Fetch User List (list users)

**Payload:** `Palach\Omnidesk\UseCases\V1\FetchUserList\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\FetchUserList\Response` (fields: `users` — collection of `UserData`, `total` — total count).

Get list of users with pagination and filters.

Query parameters (all optional):

| Field | Type | Constraints | Description |
|-------|------|-------------|-------------|
| page | int\|Optional | 1–500 | Page number (default in Omnidesk API: 1) |
| limit | int\|Optional | 1–100 | Page size (default in API: 100) |
| user_email | string\|Optional | — | Search users by email (min 3 characters) |
| user_phone | string\|Optional | — | Search users by phone (min 3 characters) |
| user_custom_id | string\|Optional | — | Search user by custom id |
| user_custom_channel | string\|Optional | — | Custom channel ID (e.g., cch101) |
| company_id | array\|Optional | — | Company ID (all users of specific company) |
| language_id | int\|Optional | — | User language |
| custom_fields | array\|Optional | — | Additional data fields |
| amount_of_cases | bool\|Optional | — | User case count |
| from_time | string\|int\|Optional | — | Start period for user creation date filter |
| to_time | string\|int\|Optional | — | End period for user creation date filter |
| from_updated_time | string\|int\|Optional | — | Start period for user update date filter |
| to_updated_time | string\|int\|Optional | — | End period for user update date filter |
| from_last_contact_time | string\|int\|Optional | — | Start period for user last contact date filter |
| to_last_contact_time | string\|int\|Optional | — | End period for user last contact date filter |

For GET requests use `Payload::toQuery()` method.

Example:

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
// Or with default parameters:
// $payload = new FetchUserListPayload();
$response = $users->fetchList($payload);
$users = $response->users;
$total = $response->total;

// Iterate users
foreach ($users as $user) {
    echo "User ID: " . $user->userId . "\n";
    echo "User Name: " . $user->userFullName . "\n";
    echo "Email: " . $user->userEmail . "\n";
}
```

---

## Fetch User Identification (get user identification code)

**Payload:** `Palach\Omnidesk\UseCases\V1\FetchUserIdentification\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\FetchUserIdentification\Response` (contains identification `code`).

**UserIdentificationData** (payload `user` field):

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| user_email | string|Optional | Required if other contact data not provided. Valid email address |
| user_phone | string|Optional | Required if other contact data not provided. Valid phone number |
| user_whatsapp_phone | string|Optional | Required if other contact data not provided. Valid phone number linked to WhatsApp |
| user_telegram_data | string|Optional | Required if other contact data not provided. Valid phone or username for Telegram |
| user_custom_id | string|Optional | Required if other contact data not provided. ID for custom channel user |
| user_custom_channel | string|Optional | Custom channel ID (e.g., cch101) |
| user_full_name | string|Optional | User's full name |
| company_name | string|Optional | User's company name |
| company_position | string|Optional | User's position |
| user_note | string|Optional | Note about user |
| language_id | int|Optional | User language ID |
| custom_fields | array|Optional | Custom fields |

At least one of the contact fields (user_email, user_phone, user_whatsapp_phone, user_telegram_data, user_custom_id) must be provided.

Example:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\UsersClient;
use Palach\Omnidesk\UseCases\V1\FetchUserIdentification\UserIdentificationData;
use Palach\Omnidesk\UseCases\V1\FetchUserIdentification\Payload as FetchUserIdentificationPayload;

/** @var UsersClient $users */
$users = Omnidesk::users();

$payload = new FetchUserIdentificationPayload(
    user: new UserIdentificationData(
        userFullName: 'Алексей Семёнов',
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

## Fetch Custom Field List (list custom fields)

**Response:** `Palach\Omnidesk\UseCases\V1\FetchCustomFieldList\Response` (fields: `customFields` — collection of `CustomFieldData`, `totalCount` — total count).

Retrieves all custom fields configured in the system. Field ownership (whether it belongs to a case or user) is specified in the `fieldLevel` parameter.

Example:

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

// Iterate through custom fields
foreach ($customFields as $customField) {
    echo "Field ID: " . $customField->fieldId . "\n";
    echo "Title: " . $customField->title . "\n";
    echo "Type: " . $customField->fieldType . "\n";
    echo "Level: " . $customField->fieldLevel . "\n";
    echo "Active: " . ($customField->active ? 'Yes' : 'No') . "\n";
}
```

**CustomFieldData properties:**

| Field | Type | Description |
|-------|------|-------------|
| fieldId | int | Field identifier |
| title | string | Field title |
| fieldType | string | Field type (text, textarea, checkbox, select, date, etc.) |
| fieldLevel | string | Field level (user - for user, case - for case) |
| active | bool | Whether the field is active |
| fieldData | array|string | Field data (for select - array of options, for others - empty string) |

---

## Fetch Custom Channel List (list custom channels)

**Response:** `Palach\Omnidesk\UseCases\V1\FetchCustomChannelList\Response` (fields: `customChannels` — collection of `CustomChannelData`, `totalCount` — total count).

Retrieves all custom channels configured in the system.

Example:

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

// Iterate through custom channels
foreach ($customChannels as $customChannel) {
    echo "Channel ID: " . $customChannel->channelId . "\n";
    echo "Title: " . $customChannel->title . "\n";
    echo "Type: " . $customChannel->channelType . "\n";
    echo "API Key: " . $customChannel->channelApiKey . "\n";
    echo "Active: " . ($customChannel->active ? 'Yes' : 'No') . "\n";
}
```

**CustomChannelData properties:**

| Field | Type | Description |
|-------|------|-------------|
| channelId | int | Channel identifier |
| channelApiKey | string | Channel API key |
| title | string | Channel title |
| channelType | string | Channel type (async, sync) |
| icon | string | Channel icon (FontAwesome class) |
| webhookUrl | string | Webhook URL for the channel |
| active | bool | Whether the channel is active |

---

## Fetch Client Email List (list client emails)

**Response:** `Palach\Omnidesk\UseCases\V1\FetchClientEmailList\Response` (fields: `clientEmails` — collection of `ClientEmailData`, `totalCount` — total count).

Retrieves all client emails.

Example:

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

// Iterate through client emails
foreach ($clientEmails as $clientEmail) {
    echo "Email ID: " . $clientEmail->emailId . "\n";
    echo "Email: " . $clientEmail->email . "\n";
    echo "Is Active: " . ($clientEmail->active ? 'Yes' : 'No') . "\n";
}
```

**ClientEmailData properties:**

| Field | Type | Description |
|-------|------|-------------|
| emailId | int | Email identifier |
| email | string | Email address |
| active | bool | Whether the email is active |

---

## Fetch Language List (list languages)

**Response:** `Palach\Omnidesk\UseCases\V1\FetchLanguageList\Response` (fields: `languages` — collection of `LanguageData`, `totalCount` — total count).

Retrieves all languages configured in the administrator account.

Example:

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

// Iterate through languages
foreach ($languages as $language) {
    echo "Language ID: " . $language->languageId . "\n";
    echo "Code: " . $language->code . "\n";
    echo "Title: " . $language->title . "\n";
    echo "Is Active: " . ($language->active ? 'Yes' : 'No') . "\n";
}
```

**LanguageData properties:**

| Field | Type | Description |
|-------|------|-------------|
| languageId | int | Language identifier |
| code | string | Language code (e.g., 'РУС', 'ENG') |
| title | string | Language title (e.g., 'Русский', 'English') |
| active | bool | Whether the language is active |

---

## Fetch Macro List (list macros)

**Response:** `Palach\Omnidesk\UseCases\V1\FetchMacroList\Response` (fields: `common` — collection of common macros `MacroCategoryData`, `personal` — collection of personal macros `MacroCategoryData`).

Retrieves macros from the administrator account. Displays common and personal macros, divided by categories.

Example:

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

// Iterate through common macros
foreach ($commonMacros as $category) {
    echo "Category: " . $category->title . "\n";
    foreach ($category->data as $macro) {
        echo "Macro: " . $macro->title . "\n";
        echo "Group: " . $macro->groupName . "\n";
        foreach ($macro->actions as $action) {
            echo "Action: " . $action->actionDisplayName . "\n";
            echo "Type: " . $action->actionType . "\n";
        }
    }
}
```

**MacroCategoryData properties:**

| Field | Type | Description |
|-------|------|-------------|
| title | string | Category title |
| sort | int | Sort order |
| macrosCategoryId | int | Macro category ID |
| data | Collection<int, MacroData> | Collection of macros in the category |

**MacroData properties:**

| Field | Type | Description |
|-------|------|-------------|
| title | string | Macro title |
| position | int | Macro position |
| groupName | string | Group name |
| actions | Collection<int, MacroActionData> | Collection of macro actions |

**MacroActionData properties:**

| Field | Type | Description |
|-------|------|-------------|
| macroActionId | int | Macro action ID |
| actionType | string | Action type (add_note, email_to_user, group_id, status, etc.) |
| actionDisplayName | string | Action display name |
| actionDestination | string|array | Action destination (depends on type) |
| content | string|null | Action content |
| subject | string|null | Action subject |
| position | int | Action position |

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

## Link User (link user profiles)

**Payload:** `Palach\Omnidesk\UseCases\V1\LinkUser\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\LinkUser\Response` (contains `UserData`).

**Payload fields:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| user_email | string|Optional | Email address of the user to link with |
| user_phone | string|Optional | Phone number of the user to link with |
| user_id | int|Optional | ID of the user to link with |

At least one of the fields (user_email, user_phone, or user_id) must be provided.

**Method parameters:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| user_id | int | yes | User ID (from URL) - the user whose profile will be linked |
| payload | LinkUserPayload | yes | Link data |

Example:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\UsersClient;
use Palach\Omnidesk\UseCases\V1\LinkUser\Payload as LinkUserPayload;

/** @var UsersClient $users */
$users = Omnidesk::users();

// Link by email
$payload = new LinkUserPayload(
    userEmail: 'user@domain.ru',
);

$response = $users->linkUser(1307386, $payload);
$user = $response->user; // UserData with updated linked_users array

// Link by user ID
$payload = new LinkUserPayload(
    userId: 25830712,
);

$response = $users->linkUser(1307386, $payload);
$user = $response->user;
```

---

## Unlink User (unlink user profiles)

**Payload:** `Palach\Omnidesk\UseCases\V1\UnlinkUser\Payload`  
**Response:** `Palach\Omnidesk\UseCases\V1\UnlinkUser\Response` (contains `UserData`).

**Payload fields:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| user_id | int | yes | ID of the user to unlink from the user specified in URL |

**Method parameters:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| user_id | int | yes | User ID (from URL) - the user from which another user will be unlinked |
| payload | UnlinkUserPayload | yes | Unlink data |

Example:

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
$user = $response->user; // UserData with updated linked_users array
```

## Disable User (disable user)

**Response:** `Palach\Omnidesk\UseCases\V1\DisableUser\Response` (contains `UserData`).

**Method parameters:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| user_id | int | yes | User ID (from URL) - the user to be disabled |

Example:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\UsersClient;

/** @var UsersClient $users */
$users = Omnidesk::users();

$response = $users->disableUser(200);
$user = $response->user; // UserData with deleted = true
```

---

## Disable Company (disable company)

**Response:** `Palach\Omnidesk\UseCases\V1\DisabledCompany\Response` (contains `CompanyData`).

**Method parameters:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| company_id | int | yes | Company ID (from URL) - the company to be disabled |

Example:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\CompaniesClient;

/** @var CompaniesClient $companies */
$companies = Omnidesk::companies();

$response = $companies->disableCompany(200);
$company = $response->company; // CompanyData with deleted = true
```

#### Disable Group

```php
/** @var GroupsClient $groups */
$groups = Omnidesk::groups();

$response = $groups->disableGroup(200, 300);
$group = $response->group; // GroupData with active = false
```

---

## Enable Group (enable group)

**Response:** `Palach\Omnidesk\UseCases\V1\EnabledGroup\Response` (contains `GroupData`).

**Method parameters:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| group_id | int | yes | Group ID (from URL) - the group to be enabled |

Enable a group.

Response:
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

Example:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\GroupsClient;

/** @var GroupsClient $groups */
$groups = Omnidesk::groups();

$response = $groups->enableGroup(200);
$group = $response->group; // GroupData with active = true
```

---

## Delete Group (delete group)

**Payload:** `Palach\Omnidesk\UseCases\V1\DeleteGroup\Payload`  
**Response:** void (no response body).

**DeleteGroupData** (payload `group` field):

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| replace_group_id | int | yes | ID of the group that will replace the deleted one. Required if the deleted group is used somewhere (in rules, cases, templates, etc.) |

Delete a group.

Example:

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

## Block User (block user)

**Response:** `Palach\Omnidesk\UseCases\V1\BlockUser\Response` (contains `UserData`).

**Method parameters:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| user_id | int | yes | User ID (from URL) - the user to be blocked |

Example:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\UsersClient;

/** @var UsersClient $users */
$users = Omnidesk::users();

$response = $users->blockUser(200);
$user = $response->user; // UserData with active = false
```

---

## Block Company (block company)

**Response:** `Palach\Omnidesk\UseCases\V1\BlockCompany\Response` (contains `CompanyData`).

**Method parameters:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| company_id | int | yes | Company ID (from URL) - the company to be blocked |

Blocks a company. All subsequent company requests will be marked as spam.

Example:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\CompaniesClient;

/** @var CompaniesClient $companies */
$companies = Omnidesk::companies();

$response = $companies->blockCompany(200);
$company = $response->company; // CompanyData with active = false
```

---

## Delete User (permanently delete user)

**Response:** `Palach\Omnidesk\UseCases\V1\DeleteUser\Response` (contains `UserData`).

**Method parameters:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| user_id | int | yes | User ID (from URL) - the user to be permanently deleted |

Permanently delete user. Available only for employees with full access.

Example:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\UsersClient;

/** @var UsersClient $users */
$users = Omnidesk::users();

$response = $users->deleteUser(200);
$user = $response->user; // UserData with deleted = true
```

---

## Recovery User (recover user)

**Response:** `Palach\Omnidesk\UseCases\V1\RecoveryUser\Response` (contains `UserData`).

**Method parameters:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| user_id | int | yes | User ID |

Enable user after blocking or recover after deletion.

Example:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\UsersClient;

/** @var UsersClient $users */
$users = Omnidesk::users();

$response = $users->recoveryUser(200);
$user = $response->user; // UserData with active = true and deleted = false
```

---

## Recovery Company (recover company)

**Response:** `Palach\Omnidesk\UseCases\V1\RecoveryCompany\Response` (contains `CompanyData`).

**Method parameters:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| company_id | int | yes | Company ID |

Enable company after blocking or recover after deletion.

Example:

```php
use Palach\Omnidesk\Facades\Omnidesk;
use Palach\Omnidesk\Clients\CompaniesClient;

/** @var CompaniesClient $companies */
$companies = Omnidesk::companies();

$response = $companies->recoveryCompany(200);
$company = $response->company; // CompanyData with active = true and deleted = false
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

## Delete Idea Official Response (delete idea official response)

**Payload:** `Palach\Omnidesk\UseCases\V1\DeleteIdeaOfficialResponse\Payload`  
**Response:** void (no response body).

**Payload fields:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| case_id | int | yes | Case ID |

Example:

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
- **LabelData** (`Omnidesk\DTO\LabelData`) — label structure from API responses.
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
- `DELETE /api/cases/{caseId}/idea_official_response.json` — delete idea official response.
- `DELETE /api/cases/{caseId}.json` — permanently delete case.
- `DELETE /api/cases/{caseIds}.json` — permanently delete multiple cases.
- `DELETE /api/cases/{caseId}/note/{messageId}.json` — delete note.
- `POST /api/labels.json` — create label.
- `GET /api/labels.json` — list labels.
- `PUT /api/labels/{labelId}.json` — update label.
- `DELETE /api/labels/{labelId}.json` — delete label.

`caseIdOrNumber` is either `case_id` or `case_number` from the payload (the client picks one internally).
