<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\DisableIdeaCategory;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\DisableIdeaCategory\Payload;
use PHPUnit\Framework\Attributes\DataProvider;

final class DisableIdeaCategoryPayloadTest extends AbstractTestCase
{
    public static function payloadDataProvider(): iterable
    {
        yield 'valid payload' => [
            'categoryId' => 234,
            'expected' => [
                'category_id' => 234,
            ],
        ];
    }

    #[DataProvider('payloadDataProvider')]
    public function testPayload(int $categoryId, array $expected): void
    {
        $payload = new Payload($categoryId);

        $this->assertEquals($categoryId, $payload->categoryId);
        $this->assertEquals($expected, $payload->toArray());
    }
}
