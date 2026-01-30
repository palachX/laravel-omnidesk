<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use InvalidArgumentException;
use Palach\Omnidesk\Database\Factories\OmniWebhookFactory;

/**
 * Omnidesk webhook table
 *
 * @property string $id
 * @property string|null $name
 * @property string $channel
 * @property string|null $api_key
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class OmniWebhook extends Model
{
    /** @use HasFactory<OmniWebhookFactory> */
    use HasFactory;

    use HasUuids;

    protected $fillable = [
        'name',
        'channel',
        'api_key',
    ];

    protected function casts(): array
    {
        return [
            'id' => 'string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * @return OmniWebhookFactory
     */
    protected static function newFactory(): Factory
    {
        return OmniWebhookFactory::new();
    }

    protected function apiKey(): Attribute
    {
        return Attribute::make(
            get: static function (mixed $value) {
                if (is_null($value)) {
                    return null;
                }

                if (! is_string($value)) {
                    throw new InvalidArgumentException('The given value for property api_key is not string');
                }

                return Crypt::decryptString($value);
            },
            set: static function (mixed $value) {
                if (is_null($value)) {
                    return null;
                }

                if (is_string($value)) {
                    return Crypt::encryptString($value);
                }

                throw new InvalidArgumentException('The given value for property api_key is not string');
            },
        );
    }
}
