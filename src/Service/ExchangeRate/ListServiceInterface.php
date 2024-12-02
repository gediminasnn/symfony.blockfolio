<?php

namespace App\Service\ExchangeRate;

interface ListServiceInterface
{
    /**
     * Gets exchange rates, possibly from cache.
     *
     * @return array<string, float> an associative array of exchange rates
     */
    public function getExchangeRates(): array;
}
