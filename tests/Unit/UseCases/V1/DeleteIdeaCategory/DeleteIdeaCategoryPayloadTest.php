<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\DeleteIdeaCategory;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\DeleteIdeaCategory\Payload as DeleteIdeaCategoryPayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class DeleteIdeaCategoryPayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'single digit id' => [
            'data' => ['idea_category_id' => 1],
            'expected' => new DeleteIdeaCategoryPayload(ideaCategoryId: 1),
        ];

        yield 'medium id' => [
            'data' => ['idea_category_id' => 12345],
            'expected' => new DeleteIdeaCategoryPayload(ideaCategoryId: 12345),
        ];

        yield 'large id' => [
            'data' => ['idea_category_id' => 999999999],
            'expected' => new DeleteIdeaCategoryPayload(ideaCategoryId: 999999999),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, DeleteIdeaCategoryPayload $expected): void
    {
        $actual = DeleteIdeaCategoryPayload::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
