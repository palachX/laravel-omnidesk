<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Palach\Omnidesk\Models\OmniWebhook;

/**
 * @extends Factory<OmniWebhook>
 */
class OmniWebhookFactory extends Factory
{
    protected $model = OmniWebhook::class;

    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid,
            'name' => $this->faker->name(),
            'channel' => $this->faker->word(),
            'api_key' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
