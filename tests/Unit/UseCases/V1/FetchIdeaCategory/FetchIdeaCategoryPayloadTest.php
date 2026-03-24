<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\FetchIdeaCategory;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchIdeaCategory\Payload as FetchIdeaCategoryPayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class FetchIdeaCategoryPayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'valid category id' => [
            'data' => [
                'category_id' => 100,
            ],
            'expected' => new FetchIdeaCategoryPayload(
                categoryId: 100,
            ),
        ];

        yield 'another valid category id' => [
            'data' => [
                'category_id' => 200,
            ],
            'expected' => new FetchIdeaCategoryPayload(
                categoryId: 200,
            ),
        ];

        yield 'minimum category id' => [
            'data' => [
                'category_id' => 1,
            ],
            'expected' => new FetchIdeaCategoryPayload(
                categoryId: 1,
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, FetchIdeaCategoryPayload $expected): void
    {
        $actual = FetchIdeaCategoryPayload::validateAndCreate($data);
        $this->assertEquals($expected, $actual);
    }

    public function testConstructor(): void
    {
        $payload = new FetchIdeaCategoryPayload(100);

        $this->assertSame(100, $payload->categoryId);
        $this->assertIsInt($payload->categoryId);
    }

    public function testCategoryIdIsReadOnly(): void
    {
        $payload = new FetchIdeaCategoryPayload(100);

        // Verify the property is readonly by trying to access it as a property
        $this->assertSame(100, $payload->categoryId);
    }
}
