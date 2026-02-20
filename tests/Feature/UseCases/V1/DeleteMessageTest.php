<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\DeleteMessage\Payload;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class DeleteMessageTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield [
            'payload' => [
                'case_id' => 123,
                'message_id' => 456,
            ],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testHttp(array $payload): void
    {
        $payload = Payload::from($payload);
        $caseId = $payload->caseId;
        $messageId = $payload->messageId;

        $url = $this->host."/api/cases/$caseId/messages/$messageId.json";

        Http::fake([
            $url => Http::response([], 200),
        ]);

        $this->makeHttpClient()->messages()->deleteMessage($payload);

        Http::assertSent(function (Request $request) use ($url) {
            return $request->url() === $url
                && $request->method() === SymfonyRequest::METHOD_DELETE;
        });
    }
}
