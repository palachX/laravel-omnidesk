<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\StoreIdeaCategory;

use Palach\Omnidesk\DTO\IdeaCategoryData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\StoreIdeaCategory\Response as StoreIdeaCategoryResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class StoreIdeaCategoryResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full response' => [
            'response' => new StoreIdeaCategoryResponse(
                ideasCategory: new IdeaCategoryData(
                    categoryId: 234,
                    categoryTitle: 'Test group',
                    active: true,
                    categoryDefaultGroup: 1
                )
            ),
            'expected' => [
                'ideas_category' => [
                    'category_title' => 'Test group',
                    'category_default_group' => 1,
                    'active' => true,
                    'category_id' => 234,
                ],
            ],
        ];

        yield 'minimal response' => [
            'response' => new StoreIdeaCategoryResponse(
                ideasCategory: new IdeaCategoryData(
                    categoryId: 235,
                    categoryTitle: 'Test group',
                    active: true,
                )
            ),
            'expected' => [
                'ideas_category' => [
                    'category_title' => 'Test group',
                    'active' => true,
                    'category_id' => 235,
                ],
            ],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testToArray(StoreIdeaCategoryResponse $response, array $expected): void
    {
        $this->assertEquals($expected, $response->toArray());
    }
}
