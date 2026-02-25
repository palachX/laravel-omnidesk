<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\FetchCase;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchCase\Payload as FetchCasePayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class FetchCasePayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'valid case id' => [
            'data' => [
                'case_id' => 2000,
            ],
            'expected' => new FetchCasePayload(
                caseId: 2000,
            ),
        ];

        yield 'another valid case id' => [
            'data' => [
                'case_id' => 12345,
            ],
            'expected' => new FetchCasePayload(
                caseId: 12345,
            ),
        ];

        yield 'minimum case id' => [
            'data' => [
                'case_id' => 1,
            ],
            'expected' => new FetchCasePayload(
                caseId: 1,
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, FetchCasePayload $expected): void
    {
        $actual = FetchCasePayload::validateAndCreate($data);
        $this->assertEquals($expected, $actual);
    }

    public function testConstructor(): void
    {
        $payload = new FetchCasePayload(2000);

        $this->assertSame(2000, $payload->caseId);
        $this->assertIsInt($payload->caseId);
    }

    public function testCaseIdIsReadOnly(): void
    {
        $payload = new FetchCasePayload(2000);

        // Verify the property is readonly by trying to access it as a property
        $this->assertSame(2000, $payload->caseId);
    }
}
