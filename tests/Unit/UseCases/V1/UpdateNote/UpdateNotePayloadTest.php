<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\UpdateNote;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\UpdateNote\NoteUpdateData;
use Palach\Omnidesk\UseCases\V1\UpdateNote\Payload as UpdateNotePayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class UpdateNotePayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full data' => [
            'data' => [
                'case_id' => 123,
                'message_id' => 911,
                'note' => [
                    'content' => 'Updated note content',
                    'content_html' => '<p>Updated note content</p>',
                ],
            ],

            'expected' => new UpdateNotePayload(
                caseId: 123,
                messageId: 911,
                note: new NoteUpdateData(
                    content: 'Updated note content',
                    contentHtml: '<p>Updated note content</p>',
                )
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, UpdateNotePayload $expected): void
    {
        $actual = UpdateNotePayload::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
