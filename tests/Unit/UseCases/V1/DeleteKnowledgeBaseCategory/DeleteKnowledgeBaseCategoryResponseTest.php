<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\DeleteKnowledgeBaseCategory;

use Palach\Omnidesk\DTO\KnowledgeBaseCategoryData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\DeleteKnowledgeBaseCategory\Response as DeleteKnowledgeBaseCategoryResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class DeleteKnowledgeBaseCategoryResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'delete knowledge base category response' => [
            'data' => [
                'kb_category' => [
                    'category_id' => 200,
                    'category_title' => 'Test Category',
                    'active' => true,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],
            'expected' => new DeleteKnowledgeBaseCategoryResponse(
                kbCategory: new KnowledgeBaseCategoryData(
                    categoryId: 200,
                    categoryTitle: 'Test Category',
                    active: true,
                    createdAt: 'Mon, 05 May 2014 00:15:17 +0300',
                    updatedAt: 'Tue, 23 Dec 2014 10:55:23 +0200',
                ),
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, DeleteKnowledgeBaseCategoryResponse $expected): void
    {
        $actual = DeleteKnowledgeBaseCategoryResponse::validateAndCreate($data);
        $this->assertEquals($expected, $actual);
    }
}
