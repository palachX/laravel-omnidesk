# Changelog

All notable changes to `laravel-omnidesk` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

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
