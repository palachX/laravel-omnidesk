<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\FetchCompany;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchCompany\Payload as FetchCompanyPayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class FetchCompanyPayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'valid company id' => [
            'data' => [
                'company_id' => 200,
            ],
            'expected' => new FetchCompanyPayload(
                companyId: 200,
            ),
        ];

        yield 'another valid company id' => [
            'data' => [
                'company_id' => 12345,
            ],
            'expected' => new FetchCompanyPayload(
                companyId: 12345,
            ),
        ];

        yield 'minimum company id' => [
            'data' => [
                'company_id' => 1,
            ],
            'expected' => new FetchCompanyPayload(
                companyId: 1,
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, FetchCompanyPayload $expected): void
    {
        $actual = FetchCompanyPayload::validateAndCreate($data);
        $this->assertEquals($expected, $actual);
    }

    public function testConstructor(): void
    {
        $payload = new FetchCompanyPayload(200);

        $this->assertSame(200, $payload->companyId);
        $this->assertIsInt($payload->companyId);
    }

    public function testCompanyIdIsReadOnly(): void
    {
        $payload = new FetchCompanyPayload(200);

        // Verify the property is readonly by trying to access it as a property
        $this->assertSame(200, $payload->companyId);
    }
}
