<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\DTO\ChangelogData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchCaseChangelog\Payload as FetchCaseChangelogPayload;
use Palach\Omnidesk\UseCases\V1\FetchCaseChangelog\Response as FetchCaseChangelogResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class FetchCaseChangelogTest extends AbstractTestCase
{
    private const string API_URL_CHANGELOG = '/api/cases/%s/changelog.json';

    public static function dataProvider(): iterable
    {
        yield 'single changelog entry' => [
            'caseId' => 2000,
            'payload' => [
                'case_id' => 2000,
            ],
            'response' => [
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
        ];

        yield 'multiple changelog entries' => [
            'caseId' => 2001,
            'payload' => [
                'case_id' => 2001,
            ],
            'response' => [
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
                ],
            ],
        ];

        yield 'changelog with filters' => [
            'caseId' => 2002,
            'payload' => [
                'case_id' => 2002,
                'staff' => '330',
                'status' => 'waiting',
                'show_chat_activation' => true,
                'rules' => [101945, 106581],
            ],
            'response' => [
                'changelog' => [
                    [
                        'created_at' => 'Fri, 13 Aug 2021 12:58:07 +0300',
                        'event' => 'status',
                        'done_by' => 'staff_330',
                        'old_value' => 'open',
                        'value' => 'waiting',
                    ],
                ],
            ],
        ];

        yield 'empty changelog' => [
            'caseId' => 2003,
            'payload' => [
                'case_id' => 2003,
            ],
            'response' => [
                'changelog' => [],
            ],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testHttp(int $caseId, array $payload, array $response): void
    {
        $payload = FetchCaseChangelogPayload::from($payload);

        $url = sprintf(self::API_URL_CHANGELOG, $caseId);
        $query = http_build_query($payload->toQuery());
        $fullUrl = $this->host.$url;
        if (strlen($query) > 0) {
            $fullUrl .= '?'.$query;
        }

        Http::fake([
            $fullUrl => Http::response($response),
        ]);

        $changelog = $this->makeHttpClient()->cases()->getChangelog($payload);

        Http::assertSent(function (Request $request) use ($fullUrl) {
            $this->assertFalse($request->isJson());
            $this->assertFalse($request->isMultipart());

            return str_starts_with($request->url(), $fullUrl) && $request->method() === SymfonyRequest::METHOD_GET;
        });

        $changelogItems = collect($response['changelog'])
            ->map(fn ($item) => ChangelogData::from($item));

        $this->assertEquals(new FetchCaseChangelogResponse(
            changelog: $changelogItems
        ), $changelog);
    }
}
