<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\StoreGroup\Payload as StoreGroupPayload;
use Palach\Omnidesk\UseCases\V1\StoreGroup\Response as StoreGroupResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class StoreGroupTest extends AbstractTestCase
{
    public static function dataArrayProvider(): iterable
    {
        yield 'store group' => [
            'payload' => new StoreGroupPayload(
                groupTitle: 'Test group',
                groupFromName: 'Test group from name',
                groupSignature: 'Test group signature'
            ),
            'response' => [
                'group' => [
                    'group_id' => 200,
                    'group_title' => 'Test group',
                    'group_from_name' => 'Test group from name',
                    'group_signature' => 'Test group signature',
                    'active' => true,
                    'created_at' => 'Mon, 05 May 2014 00:15:17 +0300',
                    'updated_at' => 'Tue, 23 Dec 2014 10:55:23 +0200',
                ],
            ],
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testHttp(StoreGroupPayload $payload, array $response): void
    {
        $url = $this->host.'/api/groups.json';

        Http::fake([
            $url => Http::response($response),
        ]);

        $responseData = $this->makeHttpClient()->groups()->store($payload);

        Http::assertSent(function (Request $request) use ($url, $payload) {
            return $request->url() === $url
                && $request->isJson()
                && $request->method() === SymfonyRequest::METHOD_POST
                && $request->body() === json_encode(['group' => $payload->toArray()]);
        });

        $this->assertEquals(StoreGroupResponse::from($response), $responseData);
    }
}
