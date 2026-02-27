<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\DeleteIdeaOfficialResponse;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\DeleteIdeaOfficialResponse\Payload as DeleteIdeaOfficialResponsePayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class DeleteIdeaOfficialResponsePayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full data' => [
            'data' => [
                'case_id' => 123,
            ],

            'expected' => new DeleteIdeaOfficialResponsePayload(
                caseId: 123,
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, DeleteIdeaOfficialResponsePayload $expected): void
    {
        $actual = DeleteIdeaOfficialResponsePayload::validateAndCreate($data);
        $this->assertEquals($expected->toArray(), $actual->toArray());
    }
}
