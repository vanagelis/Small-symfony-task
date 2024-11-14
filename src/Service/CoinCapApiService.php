<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class CoinCapApiService
{
    private const API_URL = 'https://api.coincap.io/v2/assets/';

    public function __construct(
        private readonly HttpClientInterface $httpClient
    ) {
    }

    public function fetchAssetData(string $name): array
    {
        $nameMap = [
            'BTC' => 'bitcoin',
            'ETH' => 'ethereum',
            'IOTA' => 'iota',
        ];

        if (!isset($nameMap[$name])) {
            throw new \InvalidArgumentException("Invalid asset name: $name");
        }

        $url = self::API_URL . $nameMap[$name];
        $response = $this->httpClient->request('GET', $url);

        return $response->toArray();
    }
}