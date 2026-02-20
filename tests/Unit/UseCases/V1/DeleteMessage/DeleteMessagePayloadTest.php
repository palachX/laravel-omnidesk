<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\DeleteMessage;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\DeleteMessage\Payload as DeleteMessagePayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class DeleteMessagePayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full data' => [
            'data' => [
                'case_id' => 123,
                'message_id' => 456,
            ],

            'expected' => new DeleteMessagePayload(
                caseId: 123,
                messageId: 456,
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, DeleteMessagePayload $expected): void
    {
        $actual = DeleteMessagePayload::validateAndCreate($data);
        $this->assertEquals($expected->toArray(), $actual->toArray());
    }
}
