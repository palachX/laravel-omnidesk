<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\EnableGroup;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\EnableGroup\Payload as EnableGroupPayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class EnableGroupPayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'single digit id' => [
            'data' => ['group_id' => 1],
            'expected' => new EnableGroupPayload(groupId: 1),
        ];

        yield 'medium id' => [
            'data' => ['group_id' => 12345],
            'expected' => new EnableGroupPayload(groupId: 12345),
        ];

        yield 'large id' => [
            'data' => ['group_id' => 999999999],
            'expected' => new EnableGroupPayload(groupId: 999999999),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, EnableGroupPayload $expected): void
    {
        $actual = EnableGroupPayload::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
