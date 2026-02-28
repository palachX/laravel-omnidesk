<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\FetchLabelList;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchLabelList\Payload as FetchLabelListPayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class FetchLabelListPayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'full payload' => [
            'payload' => [
                'page' => 3,
                'limit' => 25,
            ],
            'expected' => [
                'page' => 3,
                'limit' => 25,
            ],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testToQuery(array $payload, array $expected): void
    {
        $payload = FetchLabelListPayload::from($payload);

        $this->assertEquals($expected, $payload->toQuery());
    }
}
