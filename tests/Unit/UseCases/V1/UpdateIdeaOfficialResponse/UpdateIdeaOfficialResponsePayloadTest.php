<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\UpdateIdeaOfficialResponse;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\UpdateIdeaOfficialResponse\IdeaOfficialResponseUpdateData;
use Palach\Omnidesk\UseCases\V1\UpdateIdeaOfficialResponse\Payload as UpdateIdeaOfficialResponsePayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class UpdateIdeaOfficialResponsePayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full data' => [
            'data' => [
                'case_id' => 123,
                'message' => [
                    'content' => 'New official response',
                ],
            ],

            'expected' => new UpdateIdeaOfficialResponsePayload(
                caseId: 123,
                message: new IdeaOfficialResponseUpdateData(
                    content: 'New official response',
                )
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, UpdateIdeaOfficialResponsePayload $expected): void
    {
        $actual = UpdateIdeaOfficialResponsePayload::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
