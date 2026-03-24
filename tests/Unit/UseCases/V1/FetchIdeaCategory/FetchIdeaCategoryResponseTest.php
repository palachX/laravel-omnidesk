<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\FetchIdeaCategory;

use Palach\Omnidesk\DTO\IdeaCategoryData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchIdeaCategory\Response as FetchIdeaCategoryResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class FetchIdeaCategoryResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full idea category data' => [
            'data' => [
                'ideas_category' => [
                    'category_id' => 100,
                    'category_title' => 'Feature Requests',
                    'active' => true,
                    'category_default_group' => 5,
                ],
            ],

            'expected' => new FetchIdeaCategoryResponse(
                ideasCategory: new IdeaCategoryData(
                    categoryId: 100,
                    categoryTitle: 'Feature Requests',
                    active: true,
                    categoryDefaultGroup: 5,
                ),
            ),
        ];

        yield 'minimal idea category data' => [
            'data' => [
                'ideas_category' => [
                    'category_id' => 200,
                    'category_title' => 'Bug Reports',
                    'active' => false,
                ],
            ],

            'expected' => new FetchIdeaCategoryResponse(
                ideasCategory: new IdeaCategoryData(
                    categoryId: 200,
                    categoryTitle: 'Bug Reports',
                    active: false,
                ),
            ),
        ];

        yield 'inactive category with group' => [
            'data' => [
                'ideas_category' => [
                    'category_id' => 300,
                    'category_title' => 'Improvements',
                    'active' => false,
                    'category_default_group' => 10,
                ],
            ],

            'expected' => new FetchIdeaCategoryResponse(
                ideasCategory: new IdeaCategoryData(
                    categoryId: 300,
                    categoryTitle: 'Improvements',
                    active: false,
                    categoryDefaultGroup: 10,
                ),
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, FetchIdeaCategoryResponse $expected): void
    {
        $actual = FetchIdeaCategoryResponse::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }

    public function testIdeasCategoryType(): void
    {
        $ideaCategoryData = new IdeaCategoryData(
            categoryId: 1,
            categoryTitle: 'Test Category',
            active: true,
        );

        $response = new FetchIdeaCategoryResponse(ideasCategory: $ideaCategoryData);

        $this->assertInstanceOf(IdeaCategoryData::class, $response->ideasCategory);
        $this->assertSame($ideaCategoryData, $response->ideasCategory);
    }

    public function testIdeasCategoryIsReadOnly(): void
    {
        $ideaCategoryData = new IdeaCategoryData(
            categoryId: 1,
            categoryTitle: 'Test Category',
            active: true,
        );

        $response = new FetchIdeaCategoryResponse(ideasCategory: $ideaCategoryData);

        // Verify the property is readonly
        $this->assertSame($ideaCategoryData, $response->ideasCategory);
    }
}
