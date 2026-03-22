<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\DisableGroup;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\DisableGroup\Payload as DisableGroupPayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class DisableGroupPayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'single digit ids' => [
            'data' => ['group_id' => 1, 'replace_group_id' => 2],
            'expected' => new DisableGroupPayload(groupId: 1, replaceGroupId: 2),
        ];

        yield 'medium ids' => [
            'data' => ['group_id' => 12345, 'replace_group_id' => 67890],
            'expected' => new DisableGroupPayload(groupId: 12345, replaceGroupId: 67890),
        ];

        yield 'large ids' => [
            'data' => ['group_id' => 999999999, 'replace_group_id' => 888888888],
            'expected' => new DisableGroupPayload(groupId: 999999999, replaceGroupId: 888888888),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, DisableGroupPayload $expected): void
    {
        $actual = DisableGroupPayload::validateAndCreate($data);

        $this->assertEquals($expected, $actual);
    }
}
