<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Traits;

use Palach\Omnidesk\DTO\AttachmentData;
use Spatie\LaravelData\Data;

trait HasMultipartConversion
{
    /**
     * Convert data object to multipart format with specified prefix.
     *
     * @param  Data  $data  The data object to convert
     * @param  string  $prefix  The prefix for field names (e.g., 'message', 'case')
     * @return list<array{
     *     name: string,
     *     contents: string,
     *     filename?: string,
     *     headers?: array<string, string>
     * }>
     */
    protected function convertToMultipart(Data $data, string $prefix): array
    {
        $multipart = [];

        foreach ($data->toArray() as $key => $value) {
            if ($key === 'attachments') {
                continue;
            }

            if (is_scalar($value) || $value === null) {
                $multipart[] = [
                    'name' => "{$prefix}[$key]",
                    'contents' => (string) $value,
                ];
            }
        }

        /** @var AttachmentData[]|null $attachments */
        $attachments = $data->attachments ?? null;

        if (is_array($attachments)) {
            foreach ($attachments as $index => $attachment) {
                if (! ($attachment instanceof AttachmentData)) {
                    continue;
                }

                $multipart[] = [
                    'name' => "{$prefix}[attachments][$index]",
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
