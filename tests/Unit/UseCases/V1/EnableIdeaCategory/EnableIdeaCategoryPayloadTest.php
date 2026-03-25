<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\EnableIdeaCategory;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\EnableCategory\Payload as EnableCategoryPayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class EnableIdeaCategoryPayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'single digit id' => [
            'data' => ['category_id' => 1],
            'expected' => new EnableCategoryPayload(categoryId: 1),
        ];

        yield 'medium id' => [
            'data' => ['category_id' => 12345],
            'expected' => new EnableCategoryPayload(categoryId: 12345),
        ];

        yield 'large id' => [
            'data' => ['category_id' => 999999999],
            'expected' => new EnableCategoryPayload(categoryId: 999999999),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, EnableCategoryPayload $expected): void
    {
        $actual = EnableCategoryPayload::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
