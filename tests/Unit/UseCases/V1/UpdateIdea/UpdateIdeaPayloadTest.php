<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\UpdateIdea;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\UpdateIdea\IdeaUpdateData;
use Palach\Omnidesk\UseCases\V1\UpdateIdea\Payload as UpdateIdeaPayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class UpdateIdeaPayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full data' => [
            'data' => [
                'case_id' => 123,
                'message' => [
                    'content' => 'New content',
                    'stage' => 'planned',
                    'category_id' => 319,
                ],
            ],

            'expected' => new UpdateIdeaPayload(
                caseId: 123,
                message: new IdeaUpdateData(
                    content: 'New content',
                    stage: 'planned',
                    categoryId: 319,
                )
            ),
        ];

        yield 'partial data' => [
            'data' => [
                'case_id' => 123,
                'message' => [
                    'content' => 'Updated content only',
                ],
            ],

            'expected' => new UpdateIdeaPayload(
                caseId: 123,
                message: new IdeaUpdateData(
                    content: 'Updated content only',
                )
            ),
        ];

        yield 'stage only' => [
            'data' => [
                'case_id' => 123,
                'message' => [
                    'stage' => 'in_progress',
                ],
            ],

            'expected' => new UpdateIdeaPayload(
                caseId: 123,
                message: new IdeaUpdateData(
                    stage: 'in_progress',
                )
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, UpdateIdeaPayload $expected): void
    {
        $actual = UpdateIdeaPayload::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
