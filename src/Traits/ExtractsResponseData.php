<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Traits;

use Symfony\Component\Mailer\Exception\UnexpectedResponseException;

trait ExtractsResponseData
{
    /**
     * @return array<mixed>
     */
    private function extract(string $key, mixed $response): array
    {
        if (! is_array($response) || ! isset($response[$key])) {
            throw new UnexpectedResponseException("$key not found in response");
        }

        return $response[$key];
    }
}
