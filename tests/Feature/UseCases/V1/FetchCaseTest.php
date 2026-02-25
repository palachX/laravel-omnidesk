<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\DTO\CaseData;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\FetchCase\Payload as FetchCasePayload;
use Palach\Omnidesk\UseCases\V1\FetchCase\Response as FetchCaseResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class FetchCaseTest extends AbstractTestCase
{
    private const string API_URL_CASE = '/api/cases/2000.json';

    public static function dataProvider(): iterable
    {
        yield 'full case data' => [
            'caseId' => 2000,
            'response' => [
                'case' => [
                    'case_id' => 2000,
                    'case_number' => '664-245651',
                    'subject' => 'I need help',
                    'user_id' => 123,
                    'staff_id' => 22,
                    'group_id' => 44,
                    'status' => 'closed',
                    'priority' => 'normal',
                    'channel' => 'web',
                    'recipient' => 'user@domain.ru',
                    'cc_emails' => 'user_cc@domain.ru,user_cc2@domain.ru',
                    'bcc_emails' => 'user_bcc@domain.ru',
                    'deleted' => false,
                    'spam' => false,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'closed_at' => '-',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                    'last_response_at' => 'Tue, 23 Dec 2014 09:53:14 +0200',
                    'closing_speed' => 15,
                    'language_id' => 2,
                    'custom_fields' => [
                        'cf_25' => 'some text',
                        'cf_30' => 'another field',
                    ],
                    'labels' => [101, 102, 103, 104, 105],
                    'locked_labels' => [104, 105],
                    'rating' => 'high',
                    'rating_comment' => 'cool123',
                    'rated_staff_id' => 193,
                ],
            ],
        ];
        yield 'minimal case data' => [
            'caseId' => 2001,
            'response' => [
                'case' => [
                    'case_id' => 2001,
                    'case_number' => '664-245652',
                    'subject' => 'Simple case',
                    'user_id' => 456,
                    'staff_id' => 33,
                    'group_id' => 55,
                    'status' => 'open',
                    'priority' => 'normal',
                    'channel' => 'email',
                    'deleted' => false,
                    'spam' => false,
                ],
            ],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testHttp(int $caseId, array $response): void
    {
        $payload = new FetchCasePayload($caseId);

        $url = sprintf('/api/cases/%d.json', $caseId);
        $fullUrl = $this->host.$url;

        Http::fake([
            $fullUrl => Http::response($response),
        ]);

        $case = $this->makeHttpClient()->cases()->getCase($payload);

        Http::assertSent(function (Request $request) use ($fullUrl) {
            $this->assertFalse($request->isJson());
            $this->assertFalse($request->isMultipart());

            return $request->url() === $fullUrl && $request->method() === SymfonyRequest::METHOD_GET;
        });

        $this->assertEquals(new FetchCaseResponse(
            case: CaseData::from($response['case'])
        ), $case);
    }
}
