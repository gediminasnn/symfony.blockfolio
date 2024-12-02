<?php

namespace App\Service;

use Psr\Cache\CacheItemPoolInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ExchangeRateService
{
    private HttpClientInterface $client;
    private CacheItemPoolInterface $cache;
    private array $supportedCurrencies;
    private int $cacheTtl;
    private string $apiToken;

    public function __construct(
        HttpClientInterface $client,
        CacheItemPoolInterface $cacheExchangeRate,
        int $cacheTtl,
        string $coingeckoApiToken,
        array $supportedCurrencies,
    ) {
        $this->client = $client;
        $this->cache = $cacheExchangeRate;
        $this->cacheTtl = $cacheTtl;
        $this->apiToken = $coingeckoApiToken;
        $this->supportedCurrencies = $supportedCurrencies;
    }

    public function getExchangeRates(): array
    {
        $cacheItem = $this->cache->getItem('exchange_rates');

        if (!$cacheItem->isHit()) {
            $rates = $this->fetchExchangeRates();
            $cacheItem->set($rates);
            $cacheItem->expiresAfter($this->cacheTtl);
            $this->cache->save($cacheItem);
        } else {
            $rates = $cacheItem->get();
        }

        return $rates;
    }

    private function fetchExchangeRates(): array
    {
        $idsParam = implode(',', $this->supportedCurrencies);

        try {
            $response = $this->client->request(
                'GET',
                'https://api.coingecko.com/api/v3/simple/price',
                [
                    'headers' => [
                        'accept' => 'application/json',
                        'x-cg-demo-api-key' => $this->apiToken,
                    ],
                    'query' => [
                        'ids' => $idsParam,
                        'vs_currencies' => 'usd',
                    ],
                ]
            );

            $data = $response->toArray();

            // Map the response back to the currency identifiers
            $rates = [];
            foreach ($this->supportedCurrencies as $currency) {
                $rates[strtoupper($currency)] = $data[$currency]['usd'] ?? 0;
            }

            return $rates;
        } catch (\Exception $e) {
            // Handle exceptions and set rates to 0
            $rates = [];
            foreach ($this->supportedCurrencies as $currency) {
                $rates[strtoupper($currency)] = 0;
            }

            return $rates;
        }
    }
}
