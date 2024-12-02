<?php

namespace App\Service\Asset;

use App\Entity\User;
use App\Service\ExchangeRate\ListServiceInterface as ExchangeRateListServiceInterface;

class CalculateValuesService implements CalculateValuesServiceInterface
{
    private ListServiceInterface $listService;
    private ExchangeRateListServiceInterface $exchangeRateListService;

    public function __construct(
        ListServiceInterface $listService,
        ExchangeRateListServiceInterface $exchangeRateListService,
    ) {
        $this->listService = $listService;
        $this->exchangeRateListService = $exchangeRateListService;
    }

    public function calculateAssetValues(User $user): array
    {
        $assets = $this->listService->getAssetsByUser($user);
        $rates = $this->exchangeRateListService->getExchangeRates();

        $totalValueUSD = 0;
        $assetValues = [];

        foreach ($assets as $asset) {
            $currency = $asset->getCurrency();
            $valueInUSD = $asset->getValue() * ($rates[$currency] ?? 0);
            $totalValueUSD += $valueInUSD;

            $assetValues[] = [
                'id' => $asset->getId(),
                'label' => $asset->getLabel(),
                'currency' => $currency,
                'value' => $asset->getValue(),
                'value_in_usd' => $valueInUSD,
            ];
        }

        return [
            'assets' => $assetValues,
            'total_value_usd' => $totalValueUSD,
        ];
    }
}
