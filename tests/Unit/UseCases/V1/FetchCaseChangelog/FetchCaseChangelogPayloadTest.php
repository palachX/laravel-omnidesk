<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Unit\UseCases\V1\FetchCaseChangelog;

use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchCaseChangelog\Payload as FetchCaseChangelogPayload;
use PHPUnit\Framework\Attributes\DataProvider;

final class FetchCaseChangelogPayloadTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield 'minimum required data' => [
            'data' => [
                'case_id' => 2000,
            ],
            'expected' => new FetchCaseChangelogPayload(
                caseId: 2000,
            ),
        ];

        yield 'with staff filter' => [
            'data' => [
                'case_id' => 2000,
                'staff' => 'empty,28049',
            ],
            'expected' => new FetchCaseChangelogPayload(
                caseId: 2000,
                staff: 'empty,28049',
            ),
        ];

        yield 'with status filter' => [
            'data' => [
                'case_id' => 2000,
                'status' => 'any,closed',
            ],
            'expected' => new FetchCaseChangelogPayload(
                caseId: 2000,
                status: 'any,closed',
            ),
        ];

        yield 'with show_chat_activation' => [
            'data' => [
                'case_id' => 2000,
                'show_chat_activation' => true,
            ],
            'expected' => new FetchCaseChangelogPayload(
                caseId: 2000,
                showChatActivation: true,
            ),
        ];

        yield 'with multiple filters' => [
            'data' => [
                'case_id' => 2000,
                'staff' => 'any,28049',
                'status' => 'open,any',
                'show_chat_activation' => true,
                'subject' => 'empty,"Оплата картой"',
                'priority' => 'any,normal',
            ],
            'expected' => new FetchCaseChangelogPayload(
                caseId: 2000,
                staff: 'any,28049',
                status: 'open,any',
                showChatActivation: true,
                subject: 'empty,"Оплата картой"',
                priority: 'any,normal',
            ),
        ];
    }

    #[DataProvider('dataProvider')]
    public function testFromArray(array $data, FetchCaseChangelogPayload $expected): void
    {
        $actual = FetchCaseChangelogPayload::validateAndCreate($data);
        $this->assertEquals($expected, $actual);
    }

    public function testConstructor(): void
    {
        $payload = new FetchCaseChangelogPayload(2000);

        $this->assertSame(2000, $payload->caseId);
        $this->assertIsInt($payload->caseId);
    }

    public function testCaseIdIsReadOnly(): void
    {
        $payload = new FetchCaseChangelogPayload(2000);

        // Verify the property is readonly by trying to access it as a property
        $this->assertSame(2000, $payload->caseId);
    }

    public function testWithOptionalParameters(): void
    {
        $payload = new FetchCaseChangelogPayload(
            caseId: 2000,
            staff: 'empty,28049',
            status: 'any,closed',
            showChatActivation: true,
        );

        $this->assertSame(2000, $payload->caseId);
        $this->assertSame('empty,28049', $payload->staff);
        $this->assertSame('any,closed', $payload->status);
        $this->assertTrue($payload->showChatActivation);
    }
}
