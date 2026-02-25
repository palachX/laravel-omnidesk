<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\FetchCaseChangelog;

use Illuminate\Support\Collection;
use Palach\Omnidesk\DTO\ChangelogData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchCaseChangelog\Response as FetchCaseChangelogResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class FetchCaseChangelogResponseTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'single changelog entry' => [
            'data' => [
                'changelog' => [
                    [
                        'created_at' => 'Fri, 13 Aug 2021 12:57:54 +0300',
                        'event' => 'staff',
                        'done_by' => 'rule_1332',
                        'old_value' => '0',
                        'value' => '330',
                    ],
                ],
            ],
            'expected' => new FetchCaseChangelogResponse(
                changelog: new Collection([
                    new ChangelogData(
                        createdAt: 'Fri, 13 Aug 2021 12:57:54 +0300',
                        event: 'staff',
                        doneBy: 'rule_1332',
                        oldValue: '0',
                        value: '330',
                    ),
                ]),
            ),
        ];

        yield 'multiple changelog entries' => [
            'data' => [
                'changelog' => [
                    [
                        'created_at' => 'Fri, 13 Aug 2021 12:57:54 +0300',
                        'event' => 'rules',
                        'done_by' => 'rule_1332',
                        'old_value' => 'Slack Notification (ID - 1332)',
                    ],
                    [
                        'created_at' => 'Fri, 13 Aug 2021 12:57:54 +0300',
                        'event' => 'staff',
                        'done_by' => 'rule_1332',
                        'old_value' => '0',
                        'value' => '330',
                    ],
                    [
                        'created_at' => 'Fri, 13 Aug 2021 12:58:07 +0300',
                        'event' => 'status',
                        'done_by' => 'staff_330',
                        'old_value' => 'open',
                        'value' => 'waiting',
                    ],
                    [
                        'created_at' => 'Fri, 13 Aug 2021 12:58:13 +0300',
                        'event' => 'custom_field_94',
                        'done_by' => 'staff_330',
                        'old_value' => '',
                        'value' => '1',
                    ],
                ],
            ],
            'expected' => new FetchCaseChangelogResponse(
                changelog: new Collection([
                    new ChangelogData(
                        createdAt: 'Fri, 13 Aug 2021 12:57:54 +0300',
                        event: 'rules',
                        doneBy: 'rule_1332',
                        oldValue: 'Slack Notification (ID - 1332)',
                    ),
                    new ChangelogData(
                        createdAt: 'Fri, 13 Aug 2021 12:57:54 +0300',
                        event: 'staff',
                        doneBy: 'rule_1332',
                        oldValue: '0',
                        value: '330',
                    ),
                    new ChangelogData(
                        createdAt: 'Fri, 13 Aug 2021 12:58:07 +0300',
                        event: 'status',
                        doneBy: 'staff_330',
                        oldValue: 'open',
                        value: 'waiting',
                    ),
                    new ChangelogData(
                        createdAt: 'Fri, 13 Aug 2021 12:58:13 +0300',
                        event: 'custom_field_94',
                        doneBy: 'staff_330',
                        oldValue: '',
                        value: '1',
                    ),
                ]),
            ),
        ];

        yield 'empty changelog' => [
            'data' => [
                'changelog' => [],
            ],
            'expected' => new FetchCaseChangelogResponse(
                changelog: new Collection([]),
            ),
        ];

        yield 'changelog with missing optional values' => [
            'data' => [
                'changelog' => [
                    [
                        'created_at' => 'Fri, 13 Aug 2021 12:57:54 +0300',
                        'event' => 'rules',
                        'done_by' => 'rule_1332',
                    ],
                    [
                        'created_at' => 'Fri, 13 Aug 2021 12:58:07 +0300',
                        'event' => 'status',
                        'done_by' => 'staff_330',
                        'value' => 'waiting',
                    ],
                ],
            ],
            'expected' => new FetchCaseChangelogResponse(
                changelog: new Collection([
                    new ChangelogData(
                        createdAt: 'Fri, 13 Aug 2021 12:57:54 +0300',
                        event: 'rules',
                        doneBy: 'rule_1332',
                    ),
                    new ChangelogData(
                        createdAt: 'Fri, 13 Aug 2021 12:58:07 +0300',
                        event: 'status',
                        doneBy: 'staff_330',
                        value: 'waiting',
                    ),
                ]),
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, FetchCaseChangelogResponse $expected): void
    {
        $actual = FetchCaseChangelogResponse::validateAndCreate($data);
        $this->assertEquals($expected, $actual);
    }

    public function testConstructor(): void
    {
        $changelogItems = new Collection([
            new ChangelogData(
                createdAt: 'Fri, 13 Aug 2021 12:57:54 +0300',
                event: 'staff',
                doneBy: 'rule_1332',
                oldValue: '0',
                value: '330',
            ),
        ]);

        $response = new FetchCaseChangelogResponse(
            changelog: $changelogItems,
        );

        $this->assertSame($changelogItems, $response->changelog);
        $this->assertInstanceOf(Collection::class, $response->changelog);
        $this->assertCount(1, $response->changelog);
    }

    public function testChangelogContainsCorrectData(): void
    {
        $changelogItems = new Collection([
            new ChangelogData(
                createdAt: 'Fri, 13 Aug 2021 12:57:54 +0300',
                event: 'staff',
                doneBy: 'rule_1332',
                oldValue: '0',
                value: '330',
            ),
        ]);

        $response = new FetchCaseChangelogResponse(
            changelog: $changelogItems,
        );

        $item = $response->changelog->first();
        $this->assertSame('Fri, 13 Aug 2021 12:57:54 +0300', $item->createdAt);
        $this->assertSame('staff', $item->event);
        $this->assertSame('rule_1332', $item->doneBy);
        $this->assertSame('0', $item->oldValue);
        $this->assertSame('330', $item->value);
    }

    public function testEmptyChangelog(): void
    {
        $response = new FetchCaseChangelogResponse(
            changelog: new Collection([]),
        );

        $this->assertTrue($response->changelog->isEmpty());
        $this->assertInstanceOf(Collection::class, $response->changelog);
    }
}
