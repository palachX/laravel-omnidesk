<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\DisableCompany;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\DisableCompany\Payload as DisableCompanyPayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class DisableCompanyPayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'single digit id' => [
            'data' => ['company_id' => 1],
            'expected' => new DisableCompanyPayload(companyId: 1),
        ];

        yield 'medium id' => [
            'data' => ['company_id' => 12345],
            'expected' => new DisableCompanyPayload(companyId: 12345),
        ];

        yield 'large id' => [
            'data' => ['company_id' => 999999999],
            'expected' => new DisableCompanyPayload(companyId: 999999999),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, DisableCompanyPayload $expected): void
    {
        $actual = DisableCompanyPayload::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
