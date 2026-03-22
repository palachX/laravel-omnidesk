<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\DeleteKnowledgeBaseCategory;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\DeleteKnowledgeBaseCategory\Payload as DeleteKnowledgeBaseCategoryPayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class DeleteKnowledgeBaseCategoryPayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'single digit id' => [
            'data' => ['category_id' => 1],
            'expected' => new DeleteKnowledgeBaseCategoryPayload(categoryId: 1),
        ];

        yield 'medium id' => [
            'data' => ['category_id' => 12345],
            'expected' => new DeleteKnowledgeBaseCategoryPayload(categoryId: 12345),
        ];

        yield 'large id' => [
            'data' => ['category_id' => 999999999],
            'expected' => new DeleteKnowledgeBaseCategoryPayload(categoryId: 999999999),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, DeleteKnowledgeBaseCategoryPayload $expected): void
    {
        $actual = DeleteKnowledgeBaseCategoryPayload::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
