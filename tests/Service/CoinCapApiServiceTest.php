<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\CoinCapApiService;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class CoinCapApiServiceTest extends TestCase
{
    private CoinCapApiService $coinCapApiService;
    private HttpClientInterface $httpClientMock;

    protected function setUp(): void
    {
        $this->httpClientMock = $this->createMock(HttpClientInterface::class);

        $this->coinCapApiService = new CoinCapApiService($this->httpClientMock);
    }

    public function testFetchAssetDataWithValidAsset(): void
    {
        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('toArray')->willReturn([
            'data' => [
                'id' => 'bitcoin',
                'name' => 'Bitcoin',
                'symbol' => 'BTC',
                'priceUsd' => '50000',
            ]
        ]);

        $this->httpClientMock->method('request')
            ->with('GET', 'https://api.coincap.io/v2/assets/bitcoin')
            ->willReturn($responseMock);

        $result = $this->coinCapApiService->fetchAssetData('BTC');

        $this->assertSame('Bitcoin', $result['data']['name']);
        $this->assertSame('50000', $result['data']['priceUsd']);
    }

    public function testFetchAssetDataWithInvalidAsset(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid asset name: ABC');

        $this->coinCapApiService->fetchAssetData('ABC');
    }

    public function testFetchAssetDataWithIota(): void
    {
        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('toArray')->willReturn([
            'data' => [
                'id' => 'iota',
                'name' => 'IOTA',
                'symbol' => 'IOTA',
                'priceUsd' => '1.25',
            ]
        ]);

        $this->httpClientMock->method('request')
            ->with('GET', 'https://api.coincap.io/v2/assets/iota')
            ->willReturn($responseMock);

        $result = $this->coinCapApiService->fetchAssetData('IOTA');

        $this->assertSame('IOTA', $result['data']['name']);
        $this->assertSame('1.25', $result['data']['priceUsd']);
    }
}