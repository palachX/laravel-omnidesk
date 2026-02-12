<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Facade;

use Palach\Omnidesk\Clients\CasesClient;
use Palach\Omnidesk\Clients\MessagesClient;
use Palach\Omnidesk\Clients\NotesClient;

final readonly class HttpClient
{
    public function __construct(
        private CasesClient $cases,
        private MessagesClient $messages,
        private NotesClient $notes,
    ) {}

    public function cases(): CasesClient
    {
        return $this->cases;
    }

    public function messages(): MessagesClient
    {
        return $this->messages;
    }

    public function notes(): NotesClient
    {
        return $this->notes;
    }
}
