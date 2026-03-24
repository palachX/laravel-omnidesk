<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\StoreIdeaCategory;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\StoreIdeaCategory\Payload as StoreIdeaCategoryPayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class StoreIdeaCategoryPayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full payload' => [
            'payload' => new StoreIdeaCategoryPayload(
                categoryTitle: 'Test group',
                categoryDefaultGroup: 1
            ),
            'expected' => [
                'category_title' => 'Test group',
                'category_default_group' => 1,
            ],
        ];

        yield 'minimal payload' => [
            'payload' => new StoreIdeaCategoryPayload(
                categoryTitle: 'Test group'
            ),
            'expected' => [
                'category_title' => 'Test group',
            ],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testToArray(StoreIdeaCategoryPayload $payload, array $expected): void
    {
        $this->assertEquals($expected, $payload->toArray());
    }
}
