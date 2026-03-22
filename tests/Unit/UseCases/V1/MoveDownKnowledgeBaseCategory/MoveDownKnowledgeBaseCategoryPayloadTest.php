<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\MoveDownKnowledgeBaseCategory;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\MoveDownKnowledgeBaseCategory\Payload as MoveDownKnowledgeBaseCategoryPayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class MoveDownKnowledgeBaseCategoryPayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'single digit id' => [
            'data' => ['category_id' => 1],
            'expected' => new MoveDownKnowledgeBaseCategoryPayload(categoryId: 1),
        ];

        yield 'medium id' => [
            'data' => ['category_id' => 12345],
            'expected' => new MoveDownKnowledgeBaseCategoryPayload(categoryId: 12345),
        ];

        yield 'large id' => [
            'data' => ['category_id' => 999999999],
            'expected' => new MoveDownKnowledgeBaseCategoryPayload(categoryId: 999999999),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, MoveDownKnowledgeBaseCategoryPayload $expected): void
    {
        $actual = MoveDownKnowledgeBaseCategoryPayload::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
