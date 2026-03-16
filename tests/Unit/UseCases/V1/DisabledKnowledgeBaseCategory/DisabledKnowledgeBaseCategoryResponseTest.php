<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\DisabledKnowledgeBaseCategory;

use Palach\Omnidesk\DTO\KnowledgeBaseCategoryData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\DisabledKnowledgeBaseCategory\Response as DisabledKnowledgeBaseCategoryResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class DisabledKnowledgeBaseCategoryResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'disable knowledge base category response' => [
            'data' => [
                'kb_category' => [
                    'category_id' => 234,
                    'category_title' => 'Test category 2',
                    'active' => false,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],
            'expected' => new DisabledKnowledgeBaseCategoryResponse(
                kbCategory: new KnowledgeBaseCategoryData(
                    categoryId: 234,
                    categoryTitle: 'Test category 2',
                    active: false,
                    createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                    updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200',
                ),
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, DisabledKnowledgeBaseCategoryResponse $expected): void
    {
        $actual = DisabledKnowledgeBaseCategoryResponse::validateAndCreate($data);
        $this->assertEquals($expected, $actual);
    }
}
