<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\UpdateIdeaCategory;

use Palach\Omnidesk\DTO\IdeaCategoryData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\UpdateIdeaCategory\Response as UpdateIdeaCategoryResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class UpdateIdeaCategoryResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full data' => [
            'data' => [
                'ideas_category' => [
                    'category_id' => 100,
                    'category_title' => 'Test Category Updated',
                    'active' => true,
                    'category_default_group' => 43983,
                ],
            ],

            'expected' => new UpdateIdeaCategoryResponse(
                ideasCategory: new IdeaCategoryData(
                    categoryId: 100,
                    categoryTitle: 'Test Category Updated',
                    active: true,
                    categoryDefaultGroup: 43983
                )
            ),
        ];

        yield 'minimal data' => [
            'data' => [
                'ideas_category' => [
                    'category_id' => 101,
                    'category_title' => 'Minimal Category',
                    'active' => true,
                ],
            ],

            'expected' => new UpdateIdeaCategoryResponse(
                ideasCategory: new IdeaCategoryData(
                    categoryId: 101,
                    categoryTitle: 'Minimal Category',
                    active: true
                )
            ),
        ];

        yield 'inactive category' => [
            'data' => [
                'ideas_category' => [
                    'category_id' => 102,
                    'category_title' => 'Inactive Category',
                    'active' => false,
                    'category_default_group' => 43985,
                ],
            ],

            'expected' => new UpdateIdeaCategoryResponse(
                ideasCategory: new IdeaCategoryData(
                    categoryId: 102,
                    categoryTitle: 'Inactive Category',
                    active: false,
                    categoryDefaultGroup: 43985
                )
            ),
        ];

        yield 'category without default group' => [
            'data' => [
                'ideas_category' => [
                    'category_id' => 103,
                    'category_title' => 'No Default Group Category',
                    'active' => true,
                ],
            ],

            'expected' => new UpdateIdeaCategoryResponse(
                ideasCategory: new IdeaCategoryData(
                    categoryId: 103,
                    categoryTitle: 'No Default Group Category',
                    active: true
                )
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, UpdateIdeaCategoryResponse $expected): void
    {
        $actual = UpdateIdeaCategoryResponse::from($data);

        $this->assertEquals($expected, $actual);
    }
}
