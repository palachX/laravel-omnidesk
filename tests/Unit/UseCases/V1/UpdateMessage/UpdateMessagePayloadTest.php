<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\UpdateMessage;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\UpdateMessage\MessageUpdateData;
use Palach\Omnidesk\UseCases\V1\UpdateMessage\Payload as UpdateMessagePayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class UpdateMessagePayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full data id' => [
            'data' => [
                'message' => [
                    'case_id' => 123,
                    'message_id' => 911,
                    'content' => 'I need help!',
                ],
            ],

            'expected' => new UpdateMessagePayload(
                message: new MessageUpdateData(
                    messageId: 911,
                    content: 'I need help!',
                    caseId: 123,
                )
            ),
        ];
        yield 'full data number' => [
            'data' => [
                'message' => [
                    'message_id' => 911,
                    'case_number' => '664-245651',
                    'content' => 'I need help!',
                ],
            ],

            'expected' => new UpdateMessagePayload(
                message: new MessageUpdateData(
                    messageId: 911,
                    content: 'I need help!',
                    caseNumber: '664-245651',
                )
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, UpdateMessagePayload $expected): void
    {
        $actual = UpdateMessagePayload::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
