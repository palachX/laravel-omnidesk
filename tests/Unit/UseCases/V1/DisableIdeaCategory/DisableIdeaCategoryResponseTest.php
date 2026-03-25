<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\DisableIdeaCategory;

use Palach\Omnidesk\DTO\IdeaCategoryData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\DisableIdeaCategory\Response;
use PHPUnit\Framework\Attributes\DataProvider;

final class DisableIdeaCategoryResponseTest extends AbstractTestCase
{
    public static function responseDataProvider(): iterable
    {
        yield 'valid response' => [
            'data' => [
                'ideas_category' => [
                    'category_id' => 234,
                    'category_title' => 'Test category 2',
                    'category_default_group' => 43983,
                    'active' => false,
                ],
            ],
            'expectedCategoryId' => 234,
            'expectedTitle' => 'Test category 2',
            'expectedDefaultGroup' => 43983,
            'expectedActive' => false,
        ];
    }

    #[DataProvider('responseDataProvider')]
    public function testResponse(
        array $data,
        int $expectedCategoryId,
        string $expectedTitle,
        int $expectedDefaultGroup,
        bool $expectedActive
    ): void {
        $response = Response::from($data);

        $this->assertInstanceOf(IdeaCategoryData::class, $response->ideasCategory);
        $this->assertEquals($expectedCategoryId, $response->ideasCategory->categoryId);
        $this->assertEquals($expectedTitle, $response->ideasCategory->categoryTitle);
        $this->assertEquals($expectedDefaultGroup, $response->ideasCategory->categoryDefaultGroup);
        $this->assertEquals($expectedActive, $response->ideasCategory->active);
    }

    #[DataProvider('responseDataProvider')]
    public function testResponseToArray(
        array $data,
        int $expectedCategoryId,
        string $expectedTitle,
        int $expectedDefaultGroup,
        bool $expectedActive
    ): void {
        $response = Response::from($data);

        $expectedArray = [
            'ideas_category' => [
                'category_id' => $expectedCategoryId,
                'category_title' => $expectedTitle,
                'category_default_group' => $expectedDefaultGroup,
                'active' => $expectedActive,
            ],
        ];

        $this->assertEquals($expectedArray, $response->toArray());
    }
}
