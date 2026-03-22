<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\MoveUpKnowledgeBaseCategory;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\MoveUpKnowledgeBaseCategory\Payload as MoveUpKnowledgeBaseCategoryPayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class MoveUpKnowledgeBaseCategoryPayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'single digit id' => [
            'data' => ['category_id' => 1],
            'expected' => new MoveUpKnowledgeBaseCategoryPayload(categoryId: 1),
        ];

        yield 'medium id' => [
            'data' => ['category_id' => 12345],
            'expected' => new MoveUpKnowledgeBaseCategoryPayload(categoryId: 12345),
        ];

        yield 'large id' => [
            'data' => ['category_id' => 999999999],
            'expected' => new MoveUpKnowledgeBaseCategoryPayload(categoryId: 999999999),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, MoveUpKnowledgeBaseCategoryPayload $expected): void
    {
        $actual = MoveUpKnowledgeBaseCategoryPayload::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
