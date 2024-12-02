<?php

namespace App\Provider;

interface ExchangeRateProviderInterface
{
    /**
     * Fetches exchange rates from an external API.
     *
     * @return array<string, float> an associative array of exchange rates
     */
    public function fetchExchangeRates(): array;
}
