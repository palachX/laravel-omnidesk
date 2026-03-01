<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Tests\Feature\UseCases\V1;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Palach\Omnidesk\Tests\AbstractTestCase;
use Palach\Omnidesk\UseCases\V1\UpdateLabel\Payload as UpdateLabelPayload;
use Palach\Omnidesk\UseCases\V1\UpdateLabel\Response as UpdateLabelResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

final class UpdateLabelTest extends AbstractTestCase
{
    public static function dataProvider(): iterable
    {
        yield [
            'payload' => [
                'label_id' => 200,
                'label' => [
                    'label_title' => 'New label title',
                ],
            ],
            'response' => [
                'label' => [
                    'label_id' => 200,
                    'label_title' => 'New label title',
                ],
            ],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testHttp(array $payload, array $response): void
    {
        $payload = UpdateLabelPayload::from($payload);
        $labelId = $payload->labelId;

        $url = $this->host."/api/labels/$labelId.json";

        Http::fake([
            $url => Http::response($response, 200),
        ]);

        $actual = $this->makeHttpClient()->labels()->updateLabel($payload);

        Http::assertSent(function (Request $request) use ($url, $payload) {
            return $request->url() === $url
                && $request->isJson()
                && $request->method() === SymfonyRequest::METHOD_PUT
                && $request->body() === json_encode($payload->toArray());
        });

        $expected = UpdateLabelResponse::from($response);

        $this->assertEquals($expected, $actual);
    }
}
