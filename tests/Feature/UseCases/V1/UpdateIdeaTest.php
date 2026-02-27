<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\UpdateIdea\Payload as UpdateIdeaPayload;
use Palach\Omnidesk\UseCases\V1\UpdateIdea\Response as UpdateIdeaResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class UpdateIdeaTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield [
            'payload' => [
                'case_id' => 123,
                'message' => [
                    'content' => 'New content',
                    'stage' => 'planned',
                ],
            ],
            'response' => [
                'case' => [
                    'case_id' => 2000,
                    'case_number' => '664-245651',
                    'subject' => 'I need help',
                    'user_id' => 123,
                    'staff_id' => 22,
                    'group_id' => 44,
                    'status' => 'waiting',
                    'priority' => 'normal',
                    'channel' => 'idea',
                    'recipient' => '',
                    'cc_emails' => '',
                    'bcc_emails' => '',
                    'deleted' => false,
                    'spam' => false,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                    'idea' => [
                        'content' => 'New content',
                        'stage' => 'planned',
                        'category' => 319,
                    ],
                ],
            ],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testHttp(array $payload, array $response): void
    {
        $payload = UpdateIdeaPayload::from($payload);
        $caseId = $payload->caseId;

        $url = $this->host."/api/cases/$caseId/idea.json";

        Http::fake([
            $url => Http::response($response, 200),
        ]);

        $actual = $this->makeHttpClient()->cases()->updateIdea($payload);

        Http::assertSent(function (Request $request) use ($url, $payload) {
            return $request->url() === $url
                && $request->isJson()
                && $request->method() === SymfonyRequest::METHOD_PUT
                && $request->body() === json_encode($payload->toArray());
        });

        $expected = UpdateIdeaResponse::from($response);

        $this->assertEquals($expected, $actual);
    }
}
