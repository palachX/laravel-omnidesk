# Changelog

All notable changes to `laravel-omnidesk` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.2.0] - 2026-03-05

### Added

#### New API Methods - UsersClient
- `fetch()` - Fetch a single user by ID
- `store()` - Create a new user
- `update()` - Update an existing user
- `fetchList()` - Fetch paginated list of users
- `fetchUserIdentification()` - Fetch user identification code
- `linkUser()` - Link a user
- `unlinkUser()` - Unlink a user
- `disableUser()` - Disable a user
- `blockUser()` - Block a user
- `recoveryUser()` - Restore/recover a user
- `deleteUser()` - Delete a user

#### New API Methods - LabelsClient
- `store()` - Create a new label
- `fetchList()` - Fetch list of labels
- `updateLabel()` - Update an existing label
- `deleteLabel()` - Delete a label

#### New Client Access
- `Omnidesk::users()` - Access to UsersClient for user operations
- `Omnidesk::labels()` - Access to LabelsClient for label operations

#### New DTOs
- `UserData` - User data transfer object
- `LabelData` - Label data transfer object

#### New Use Cases
- Complete user management use cases (V1 namespace)
- Complete label management use cases (V1 namespace)

#### Complete User Management System
- Full CRUD operations for users with `UsersClient`
- User lifecycle management: disable, block, recover, delete
- User linking/unlinking functionality
- User identification code fetching
- Comprehensive user data handling with `UserData` DTO

#### Complete Label Management System  
- Full CRUD operations for labels with `LabelsClient`
- Label creation, listing, updating, and deletion
- Comprehensive label data handling with `LabelData` DTO

#### Enhanced API Client Access
- `Omnidesk::users()` - Access to complete user management API
- `Omnidesk::labels()` - Access to complete label management API

#### Architecture Improvements
- Structured use cases for all user and label operations (V1 namespace)
- Consistent payload/response patterns across all new endpoints
- Proper error handling and type safety for all new operations

## [1.1.0] - 2026-02-27

### Added

#### File Attachments Support
- Ability to send file attachments when creating a Case (support for `attachments` and `attachmentUrls` fields)
- Ability to send file attachments when creating a Message (support for `attachments` and `attachmentUrls` fields)
- Ability to send file attachments when creating a Note (support for `attachments` and `attachmentUrls` fields)

#### New API Methods - CasesClient
- `getCase()` - Fetch a single case by ID
- `getChangelog()` - Fetch case changelog/history
- `rateCase()` - Rate a case
- `fetchList()` - Fetch paginated list of cases
- `trashCase()` - Move case to trash
- `trashBulk()` - Move multiple cases to trash
- `restoreCase()` - Restore case from trash
- `restoreBulk()` - Restore multiple cases from trash
- `deleteCase()` - Permanently delete a case
- `deleteBulk()` - Permanently delete multiple cases
- `spamCase()` - Mark case as spam
- `spamBulk()` - Mark multiple cases as spam
- `updateIdea()` - Update idea details
- `updateIdeaOfficialResponse()` - Update idea official response
- `deleteIdeaOfficialResponse()` - Delete idea official response

#### New API Methods - MessagesClient
- `update()` - Update an existing message
- `rate()` - Rate a message
- `deleteMessage()` - Delete a message
- `fetchMessages()` - Fetch paginated list of messages for a case

#### New API Methods - NotesClient
- `store()` - Create a new note
- `update()` - Update an existing note
- `deleteNote()` - Delete a note

#### New API Methods - FiltersClient
- `fetchList()` - Fetch list of available filters

#### New Client Access
- `Omnidesk::notes()` - Access to NotesClient for note operations
- `Omnidesk::filters()` - Access to FiltersClient for filter operations

#### Documentation
- Bilingual documentation (Russian and English) in `docs/ru/` and `docs/en/`
- Main documentation index at `docs/README.md` with language selection
- Documentation sections: Installation, Configuration, Webhooks, API, Artisan commands




## [1.0.0] - Initial release

### Added

- REST API client (`Omnidesk\Services\HttpClient`) for Omnidesk API
- Use cases: Store Case, Fetch Case List, Store Message, Update Message
- Webhook endpoint with configurable handlers per event type (`WebhookHandlerInterface`)
- Multi-tenant webhooks: table `omni_webhooks`, UUID-based webhook URLs, optional API key (encrypted)
- Artisan commands: `omnidesk:webhooks:create`, `omnidesk:webhooks:list`
- Config file `config/omnidesk.php` and env variables (`OMNI_HOST`, `OMNI_EMAIL`, `OMNI_API_KEY`, `OMNI_WEBHOOK_URL`)
- Migrations for `omni_webhooks` table
- Optional config and translation publishing via package tags
- DTOs and Payloads/Responses built with Spatie Laravel Data
- PHP 8.3+ support, Laravel package auto-discovery
