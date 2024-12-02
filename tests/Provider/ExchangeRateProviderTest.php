<?php

namespace App\Tests\Service\ExchangeRate;

use App\Provider\ExchangeRateProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ExchangeRateProviderTest extends TestCase
{
    public function testFetchExchangeRatesSuccess(): void
    {
        $supportedCurrencies = ['bitcoin', 'ethereum', 'iota'];
        $apiToken = 'test_api_token';

        $expectedResponseData = [
            'bitcoin' => ['usd' => 97793.62],
            'ethereum' => ['usd' => 3726.80],
            'iota' => ['usd' => 0.36],
        ];

        $httpClientMock = $this->createMock(HttpClientInterface::class);
        $responseMock = $this->createMock(ResponseInterface::class);

        $httpClientMock->expects($this->once())
            ->method('request')
            ->willReturn($responseMock);

        $responseMock->expects($this->once())
            ->method('toArray')
            ->willReturn($expectedResponseData);

        $provider = new ExchangeRateProvider(
            $httpClientMock,
            $apiToken,
            $supportedCurrencies
        );

        $rates = $provider->fetchExchangeRates();

        $this->assertEquals([
            'bitcoin' => 97793.62,
            'ethereum' => 3726.80,
            'iota' => 0.36,
        ], $rates);
    }

    public function testFetchExchangeRatesFailure(): void
    {
        $supportedCurrencies = ['bitcoin', 'ethereum', 'iota'];
        $apiToken = 'test_api_token';

        $httpClientMock = $this->createMock(HttpClientInterface::class);

        $httpClientMock->expects($this->once())
            ->method('request')
            ->willThrowException(new \Exception('API error'));

        $provider = new ExchangeRateProvider(
            $httpClientMock,
            $apiToken,
            $supportedCurrencies
        );

        $rates = $provider->fetchExchangeRates();

        $this->assertEquals([
            'bitcoin' => 0,
            'ethereum' => 0,
            'iota' => 0,
        ], $rates);
    }
}
