<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\RateMessage;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\RateMessage\Payload as RateMessagePayload;
use Palach\Omnidesk\UseCases\V1\RateMessage\RateMessageData;
use PHPUnit\Framework\Attributes\DataProvider;

final class RateMessagePayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full data' => [
            'data' => [
                'case_id' => 123,
                'message_id' => 2001,
                'rate' => [
                    'rating' => 'low',
                    'rating_comment' => 'cool',
                ],
            ],

            'expected' => new RateMessagePayload(
                caseId: 123,
                messageId: 2001,
                rate: new RateMessageData(
                    rating: 'low',
                    ratingComment: 'cool',
                )
            ),
        ];

        yield 'minimal data' => [
            'data' => [
                'case_id' => 123,
                'message_id' => 2001,
                'rate' => [
                    'rating' => 'high',
                ],
            ],

            'expected' => new RateMessagePayload(
                caseId: 123,
                messageId: 2001,
                rate: new RateMessageData(
                    rating: 'high',
                )
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, RateMessagePayload $expected): void
    {
        $actual = RateMessagePayload::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
