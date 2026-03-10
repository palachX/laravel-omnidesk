<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\FetchGroup;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchGroup\Payload as FetchGroupPayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class FetchGroupPayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'valid group id' => [
            'data' => [
                'group_id' => 200,
            ],
            'expected' => new FetchGroupPayload(
                groupId: 200,
            ),
        ];

        yield 'another valid group id' => [
            'data' => [
                'group_id' => 12345,
            ],
            'expected' => new FetchGroupPayload(
                groupId: 12345,
            ),
        ];

        yield 'minimum group id' => [
            'data' => [
                'group_id' => 1,
            ],
            'expected' => new FetchGroupPayload(
                groupId: 1,
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, FetchGroupPayload $expected): void
    {
        $actual = FetchGroupPayload::validateAndCreate($data);
        $this->assertEquals($expected, $actual);
    }
}
