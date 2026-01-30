<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\UpdateMessage;

use Palach\Omnidesk\DTO\MessageData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\UpdateMessage\Response as UpdateMessageResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class UpdateMessageResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full data' => [
            'data' => [
                'message' => [
                    'message_id' => 2000,
                    'user_id' => 123,
                    'staff_id' => 321,
                    'content' => 'I need help',
                ],
            ],

            'expected' => new UpdateMessageResponse(
                message: new MessageData(
                    messageId: 2000,
                    userId: 123,
                    staffId: 321,
                    content: 'I need help'
                )
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, UpdateMessageResponse $expected): void
    {
        $actual = UpdateMessageResponse::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
