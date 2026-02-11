<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\StoreMessage;

use InvalidArgumentException;
use LogicException;
use Palach\Omnidesk\Interfaces\InteractsWithFiles;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
final class Payload extends Data implements InteractsWithFiles
{
    public function __construct(
        public readonly MessageStoreData $message
    ) {}

    public function isAttachment(): bool
    {
        return ! ($this->message->attachments instanceof Optional) && ! empty($this->message->attachments);
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
        $multipart = [];

        foreach ($this->message->toArray() as $key => $value) {
            if ($key === 'attachments') {
                continue;
            }

            if (is_scalar($value) || $value === null) {
                $multipart[] = [
                    'name' => "message[$key]",
                    'contents' => (string) $value,
                ];
            }
        }

        $attachments = $this->message->attachments;

        if (is_array($attachments)) {
            foreach ($attachments as $index => $attachment) {
                $multipart[] = [
                    'name' => "message[attachments][$index]",
                    'contents' => $attachment->content,
                    'filename' => $attachment->name,
                    'headers' => [
                        'Content-Type' => $attachment->mimeType,
                    ],
                ];
            }
        }

        return $multipart;
    }

    public function getCaseIdentifier(): int|string
    {
        return match (true) {
            $this->message->caseId instanceof Optional && $this->message->caseNumber instanceof Optional => throw new InvalidArgumentException('Not set caseId or CaseNumber'),
            ! ($this->message->caseId instanceof Optional) => $this->message->caseId,
            ! ($this->message->caseNumber instanceof Optional) => $this->message->caseNumber,
            default => throw new LogicException('Both caseId and caseNumber are set'),
        };
    }
}
