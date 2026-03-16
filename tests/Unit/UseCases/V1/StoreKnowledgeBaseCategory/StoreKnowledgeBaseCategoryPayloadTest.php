<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\StoreKnowledgeBaseCategory;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\StoreKnowledgeBaseCategory\Payload as StoreKnowledgeBaseCategoryPayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class StoreKnowledgeBaseCategoryPayloadTest extends AbstractTestCase
{
    public static function dataArrayProvider(): iterable
    {
        yield 'kb category with single language title' => [
            'data' => [
                'category_title' => 'Test category',
            ],
            'expected' => new StoreKnowledgeBaseCategoryPayload(
                categoryTitle: 'Test category'
            ),
        ];

        yield 'kb category with multilingual title' => [
            'data' => [
                'category_title' => [
                    '1' => 'Название категории',
                    '2' => 'Category name',
                ],
            ],
            'expected' => new StoreKnowledgeBaseCategoryPayload(
                categoryTitle: [
                    '1' => 'Название категории',
                    '2' => 'Category name',
                ]
            ),
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testFromArray(array $data, StoreKnowledgeBaseCategoryPayload $expected): void
    {
        $payload = StoreKnowledgeBaseCategoryPayload::from($data);

        $this->assertEquals($expected, $payload);
    }
}
