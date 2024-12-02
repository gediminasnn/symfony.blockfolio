<?php

namespace App\Tests\Service\ExchangeRate;

use App\Provider\ExchangeRateProviderInterface;
use App\Service\ExchangeRate\ListService;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

class ListServiceTest extends TestCase
{
    public function testGetExchangeRatesFromCache(): void
    {
        $cacheItemMock = $this->createMock(CacheItemInterface::class);
        $cacheItemMock->method('isHit')->willReturn(true);
        $cacheItemMock->method('get')->willReturn([
            'bitcoin' => 97793.62,
            'ethereum' => 3727.81,
            'iota' => 0.36,
        ]);

        $cacheMock = $this->createMock(CacheItemPoolInterface::class);
        $cacheMock->method('getItem')->willReturn($cacheItemMock);

        $providerMock = $this->createMock(ExchangeRateProviderInterface::class);

        $listService = new ListService(
            $cacheMock,
            $providerMock,
            2
        );

        $rates = $listService->getExchangeRates();

        $this->assertEquals([
            'bitcoin' => 97793.62,
            'ethereum' => 3727.81,
            'iota' => 0.36,
        ], $rates);
    }

    public function testGetExchangeRatesFetchesAndCaches(): void
    {
        $expectedRates = [
            'bitcoin' => 97793.62,
            'ethereum' => 3727.81,
            'iota' => 0.36,
        ];

        $cacheItemMock = $this->createMock(CacheItemInterface::class);
        $cacheItemMock->method('isHit')->willReturn(false);

        $cacheMock = $this->createMock(CacheItemPoolInterface::class);
        $cacheMock->method('getItem')->willReturn($cacheItemMock);

        $providerMock = $this->createMock(ExchangeRateProviderInterface::class);
        $providerMock->method('fetchExchangeRates')
            ->willReturn($expectedRates);

        $cacheItemMock->expects($this->once())
            ->method('set')
            ->with($expectedRates);
        $cacheItemMock->expects($this->once())
            ->method('expiresAfter')
            ->with(2);
        $cacheMock->expects($this->once())
            ->method('save')
            ->with($cacheItemMock);

        $listService = new ListService(
            $cacheMock,
            $providerMock,
            2
        );

        $rates = $listService->getExchangeRates();

        $this->assertEquals($expectedRates, $rates);
    }
}
