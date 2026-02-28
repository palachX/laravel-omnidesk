<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\StoreLabel\Payload as StoreLabelPayload;
use Palach\Omnidesk\UseCases\V1\StoreLabel\Response as StoreLabelResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class StoreLabelTest extends AbstractTestCase
{
    private const string API_URL_LABELS = '/api/labels.json';

    public static function dataArrayProvider(): iterable
    {
        yield [
            'payload' => [
                'label' => [
                    'label_title' => 'Test title',
                ],
            ],
            'response' => [
                'label' => [
                    'label_id' => 200,
                    'label_title' => 'Test title',
                ],
            ],
        ];
    }

    #[DataProvider('dataArrayProvider')]
    public function testHttp(array $payload, array $response): void
    {
        $url = $this->host.self::API_URL_LABELS;

        Http::fake([
            $url => Http::response($response),
        ]);

        $responseData = $this->makeHttpClient()->labels()->store(StoreLabelPayload::from($payload));

        $payload = StoreLabelPayload::from($payload);

        Http::assertSent(function (Request $request) use ($url, $payload) {
            return $request->url() === $url
                && $request->isJson()
                && $request->method() === SymfonyRequest::METHOD_POST
                && $request->body() === json_encode($payload->toArray());
        });

        $this->assertEquals(StoreLabelResponse::from($response), $responseData);
    }
}
