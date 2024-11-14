<?php

declare(strict_types=1);

namespace App\Service;

class AssetService
{
    public function __construct(
        private readonly CoinCapApiService $coinCapApiService,
    ) {
    }

    public function getAssetValueInUsd(string $name): float
    {
        return (float) $this->coinCapApiService->fetchAssetData($name)['data']['priceUsd'];
    }
}