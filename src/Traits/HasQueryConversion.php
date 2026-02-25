<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Traits;

use Spatie\LaravelData\Optional;

trait HasQueryConversion
{
    /**
     * @param  array<string, mixed>  $query
     * @param  array<mixed>|Optional  $value
     */
    private function serializeList(array &$query, string $key, array|Optional $value): void
    {
        if ($value instanceof Optional) {
            return;
        }

        if (count($value) === 1) {
            $query[$key] = $value[0];

            return;
        }

        $query[$key] = $value;
    }
}
