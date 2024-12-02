<?php

namespace App\Tests\Mock;

use App\Service\ExchangeRate\ListServiceInterface;

class MockedExchangeRateListService implements ListServiceInterface
{
    public function getExchangeRates(): array
    {
        return [
            'bitcoin' => 50000.0,
            'ethereum' => 4000.0,
            'iota' => 1.5,
        ];
    }
}
