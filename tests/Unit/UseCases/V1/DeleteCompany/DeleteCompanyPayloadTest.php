<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\DeleteCompany;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\DeleteCompany\Payload as DeleteCompanyPayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class DeleteCompanyPayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'single digit id' => [
            'data' => ['company_id' => 1],
            'expected' => new DeleteCompanyPayload(companyId: 1),
        ];

        yield 'medium id' => [
            'data' => ['company_id' => 12345],
            'expected' => new DeleteCompanyPayload(companyId: 12345),
        ];

        yield 'large id' => [
            'data' => ['company_id' => 999999999],
            'expected' => new DeleteCompanyPayload(companyId: 999999999),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, DeleteCompanyPayload $expected): void
    {
        $actual = DeleteCompanyPayload::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
