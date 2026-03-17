<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\MoveUpKnowledgeBaseCategory;

use Palach\Omnidesk\DTO\KnowledgeBaseCategoryData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\MoveUpKnowledgeBaseCategory\Response as MoveUpKnowledgeBaseCategoryResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class MoveUpKnowledgeBaseCategoryResponseTest extends AbstractTestCase
{
    public static function dataArrayProvider(): iterable
    {
        yield 'move up knowledge base category response' => [
            'data' => [
                'kb_category' => [
                    'category_id' => 234,
                    'category_title' => 'Test category 2',
                    'active' => true,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],
            'expected' => [
                'kb_category' => [
                    'category_id' => 234,
                    'category_title' => 'Test category 2',
                    'active' => true,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testResponse(array $data, array $expected): void
    {
        $response = MoveUpKnowledgeBaseCategoryResponse::from($data);

        $this->assertInstanceOf(KnowledgeBaseCategoryData::class, $response->kbCategory);
        $this->assertEquals($expected, $response->toArray());
    }
}
