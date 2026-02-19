<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\UpdateNote\Payload as UpdateNotePayload;
use Palach\Omnidesk\UseCases\V1\UpdateNote\Response as UpdateNoteResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class UpdateNoteTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield [
            'payload' => [
                'case_id' => 123,
                'message_id' => 2000,
                'note' => [
                    'content' => 'Updated note content',
                    'content_html' => '<p>Updated note content</p>',
                ],
            ],
            'response' => [
                'message' => [
                    'message_id' => 2000,
                    'user_id' => 123,
                    'staff_id' => 321,
                    'content' => 'Updated note content',
                    'content_html' => '<p>Updated note content</p>',
                    'note' => true,
                    'created_at' => 'Mon, 06 May 2014 00:15:17 +0300',
                ],
            ],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testHttp(array $payload, array $response): void
    {
        $payload = UpdateNotePayload::from($payload);
        $caseId = $payload->caseId;
        $messageId = $payload->messageId;

        $url = $this->host."/api/cases/$caseId/note/$messageId.json";

        Http::fake([
            $url => Http::response($response, 200),
        ]);

        $actual = $this->makeHttpClient()->notes()->update($payload);

        Http::assertSent(function (Request $request) use ($url, $payload) {
            return $request->url() === $url
                && $request->isJson()
                && $request->method() === SymfonyRequest::METHOD_PUT
                && $request->body() === json_encode($payload->toArray());
        });

        $expected = UpdateNoteResponse::from($response);

        $this->assertEquals($expected, $actual);
    }
}
