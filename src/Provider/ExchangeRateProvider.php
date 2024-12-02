<?php

namespace App\Provider;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ExchangeRateProvider implements ExchangeRateProviderInterface
{
    private HttpClientInterface $client;
    private string $apiToken;
    private array $supportedCurrencies;

    public function __construct(
        HttpClientInterface $client,
        string $coingeckoApiToken,
        array $supportedCurrencies,
    ) {
        $this->client = $client;
        $this->apiToken = $coingeckoApiToken;
        $this->supportedCurrencies = $supportedCurrencies;
    }

    public function fetchExchangeRates(): array
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
                $rates[$currency] = $data[$currency]['usd'] ?? 0;
            }

            return $rates;
        } catch (\Exception $e) {
            // Handle exceptions and set rates to 0
            $rates = [];
            foreach ($this->supportedCurrencies as $currency) {
                $rates[$currency] = 0;
            }

            return $rates;
        }
    }
}
