<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\RateMessage;

use Palach\Omnidesk\DTO\MessageData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\RateMessage\Response as RateMessageResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class RateMessageResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full data' => [
            'data' => [
                'message' => [
                    'message_id' => 2001,
                    'user_id' => 0,
                    'staff_id' => 123,
                    'content' => 'I need help',
                    'content_html' => '',
                    'note' => false,
                    'created_at' => 'Mon, 06 May 2014 00:15:17 +0300',
                    'rating' => 'low',
                    'rating_comment' => 'cool',
                    'rated_staff_id' => 0,
                ],
            ],

            'expected' => new RateMessageResponse(
                message: new MessageData(
                    messageId: 2001,
                    userId: 0,
                    staffId: 123,
                    content: 'I need help',
                    note: false,
                    createdAt: 'Mon, 06 May 2014 00:15:17 +0300',
                    contentHtml: '',
                    rating: 'low',
                    ratingComment: 'cool',
                    ratedStaffId: 0,
                )
            ),
        ];

        yield 'required data' => [
            'data' => [
                'message' => [
                    'message_id' => 2001,
                    'user_id' => 0,
                    'staff_id' => 123,
                    'content' => 'I need help',
                    'content_html' => '',
                    'note' => false,
                    'created_at' => 'Mon, 06 May 2014 00:15:17 +0300',
                ],
            ],

            'expected' => new RateMessageResponse(
                message: new MessageData(
                    messageId: 2001,
                    userId: 0,
                    staffId: 123,
                    content: 'I need help',
                    note: false,
                    createdAt: 'Mon, 06 May 2014 00:15:17 +0300',
                    contentHtml: '',
                )
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, RateMessageResponse $expected): void
    {
        $actual = RateMessageResponse::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
