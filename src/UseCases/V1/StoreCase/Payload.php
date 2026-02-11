<?php

declare(strict_types=1);

namespace Palach\Omnidesk\UseCases\V1\StoreCase;

use Palach\Omnidesk\Interfaces\InteractsWithFiles;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
final class Payload extends Data implements InteractsWithFiles
{
    public function __construct(
        public readonly CaseStoreData $case
    ) {}

    public function isAttachment(): bool
    {
        return ! ($this->case->attachments instanceof Optional) && ! empty($this->case->attachments);
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

        foreach ($this->case->toArray() as $key => $value) {
            if ($key === 'attachments') {
                continue;
            }

            if (is_scalar($value) || $value === null) {
                $multipart[] = [
                    'name' => "case[$key]",
                    'contents' => (string) $value,
                ];
            }
        }

        $attachments = $this->case->attachments;

        if (is_array($attachments)) {
            foreach ($attachments as $index => $attachment) {
                $multipart[] = [
                    'name' => "case[attachments][$index]",
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
}
