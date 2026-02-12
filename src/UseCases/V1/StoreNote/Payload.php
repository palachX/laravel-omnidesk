<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\StoreNote;

use Palach\Omnidesk\Interfaces\InteractsWithFiles;
use Palach\Omnidesk\Traits\HasMultipartConversion;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
final class Payload extends Data implements InteractsWithFiles
{
    use HasMultipartConversion;

    public function __construct(
        public readonly int $caseId,
        public readonly NoteStoreData $note,
    ) {}

    public function isAttachment(): bool
    {
        return ! ($this->note->attachments instanceof Optional) && ! empty($this->note->attachments);
    }

    /**
     * @return list<array{
     *     name: string,
     *     contents: string,
     *     filename?: string,
     *     headers?: array<string, string>
     * }>
     */
    public function toMultipart(): array
    {
        return $this->convertToMultipart($this->note, 'note');
    }
}
