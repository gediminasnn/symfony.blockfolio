<?php

namespace App\Service\ExchangeRate;

use App\Provider\ExchangeRateProviderInterface;
use Psr\Cache\CacheItemPoolInterface;

class ListService implements ListServiceInterface
{
    private CacheItemPoolInterface $cache;
    private ExchangeRateProviderInterface $provider;
    private int $cacheTtl;

    public function __construct(
        CacheItemPoolInterface $cacheExchangeRate,
        ExchangeRateProviderInterface $provider,
        int $cacheTtl,
    ) {
        $this->cache = $cacheExchangeRate;
        $this->provider = $provider;
        $this->cacheTtl = $cacheTtl;
    }

    public function getExchangeRates(): array
    {
        $cacheItem = $this->cache->getItem('exchange_rates');

        if (!$cacheItem->isHit()) {
            $rates = $this->provider->fetchExchangeRates();
            $cacheItem->set($rates);
            $cacheItem->expiresAfter($this->cacheTtl);
            $this->cache->save($cacheItem);
        } else {
            $rates = $cacheItem->get();
        }

        return $rates;
    }
}
