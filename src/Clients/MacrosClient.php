<?php

declare(strict_types=1);

namespace Palach\Omnidesk\Clients;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Collection;
use Palach\Omnidesk\DTO\MacroCategoryData;
use Palach\Omnidesk\Traits\ExtractsResponseData;
use Palach\Omnidesk\Transport\OmnideskTransport;
use Palach\Omnidesk\UseCases\V1\FetchMacroList\Response as FetchMacroListResponse;

final readonly class MacrosClient
{
    use ExtractsResponseData;

    private const string API_URL = '/api/macros.json';

    public function __construct(
        private OmnideskTransport $transport,
    ) {}

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function fetchList(): FetchMacroListResponse
    {
        $response = $this->transport->get(self::API_URL);

        if (! is_array($response)) {
            throw new ConnectionException('Invalid response format');
        }

        $extractedCommon = $this->extractArray('common', $response);
        $extractedPersonal = $this->extractArray('personal', $response);

        $common = $this->parseMacroCategories($extractedCommon);
        $personal = $this->parseMacroCategories($extractedPersonal);

        return new FetchMacroListResponse(
            common: $common,
            personal: $personal,
        );
    }

    /**
     * @param  array<string, mixed>  $categories
     * @return Collection<int, MacroCategoryData>
     */
    private function parseMacroCategories(array $categories): Collection
    {
        $categoryCollection = new Collection;

        if ($categories == []) {
            return $categoryCollection;
        }

        foreach ($categories as $category) {
            if ($category == []) {
                continue;
            }
            $categoryCollection->add(MacroCategoryData::from($category));
        }

        return $categoryCollection;
    }
}
