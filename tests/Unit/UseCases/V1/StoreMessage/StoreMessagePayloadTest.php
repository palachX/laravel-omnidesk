<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\StoreMessage;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\StoreMessage\MessageStoreData;
use Palach\Omnidesk\UseCases\V1\StoreMessage\Payload as StoreMessagePayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class StoreMessagePayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full data id' => [
            'data' => [
                'message' => [
                    'case_id' => 123,
                    'content' => 'I need help!',
                    'user_id' => 321,
                ],
            ],

            'expected' => new StoreMessagePayload(
                message: new MessageStoreData(
                    userId: 321,
                    content: 'I need help!',
                    caseId: 123,
                )
            ),
        ];
        yield 'full data number' => [
            'data' => [
                'message' => [
                    'case_number' => '664-245651',
                    'content' => 'I need help!',
                    'user_id' => 321,
                ],
            ],

            'expected' => new StoreMessagePayload(
                message: new MessageStoreData(
                    userId: 321,
                    content: 'I need help!',
                    caseNumber: '664-245651',
                )
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, StoreMessagePayload $expected): void
    {
        $actual = StoreMessagePayload::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
