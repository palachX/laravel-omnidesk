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
                'case_id' => 123,
                'message_id' => 911,
                'message' => [
                    'content' => 'I need help!',
                    'content_html' => '<p>I need help</p>',
                ],
            ],

            'expected' => new UpdateMessagePayload(
                message: new MessageUpdateData(
                    content: 'I need help!',
                    contentHtml: '<p>I need help</p>',
                ),
                caseId: 123,
                messageId: 911
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
