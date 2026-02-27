<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\DeleteNote;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\DeleteNote\Payload as DeleteNotePayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class DeleteNotePayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full data' => [
            'data' => [
                'case_id' => 123,
                'message_id' => 456,
            ],

            'expected' => new DeleteNotePayload(
                caseId: 123,
                messageId: 456,
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, DeleteNotePayload $expected): void
    {
        $actual = DeleteNotePayload::validateAndCreate($data);
        $this->assertEquals($expected->toArray(), $actual->toArray());
    }
}
