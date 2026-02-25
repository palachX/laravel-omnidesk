<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\RateCase;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\RateCase\Payload as RateCasePayload;
use Palach\Omnidesk\UseCases\V1\RateCase\RateData;
use PHPUnit\Framework\Attributes\DataProvider;

final class RateCasePayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full data with string rating' => [
            'data' => [
                'case_id' => 123,
                'rate' => [
                    'rating' => 'high',
                    'rating_comment' => 'Excellent service!',
                    'rated_staff_id' => 189,
                ],
            ],

            'expected' => new RateCasePayload(
                caseId: 123,
                rate: new RateData(
                    rating: 'high',
                    ratingComment: 'Excellent service!',
                    ratedStaffId: 189,
                )
            ),
        ];

        yield 'minimal data with string rating' => [
            'data' => [
                'case_id' => 456,
                'rate' => [
                    'rating' => 'low',
                ],
            ],

            'expected' => new RateCasePayload(
                caseId: 456,
                rate: new RateData(
                    rating: 'low',
                )
            ),
        ];

        yield 'data with array rating' => [
            'data' => [
                'case_id' => 789,
                'rate' => [
                    'rating' => 'middle',
                    'rating_comment' => 'Good service',
                ],
            ],

            'expected' => new RateCasePayload(
                caseId: 789,
                rate: new RateData(
                    rating: 'middle',
                    ratingComment: 'Good service',
                )
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, RateCasePayload $expected): void
    {
        $actual = RateCasePayload::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
