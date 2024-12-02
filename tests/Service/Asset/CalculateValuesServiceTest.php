<?php

namespace App\Tests\Service\Asset;

use App\Entity\Asset;
use App\Entity\User;
use App\Service\Asset\CalculateValuesService;
use App\Service\Asset\ListServiceInterface as AssetListServiceInterface;
use App\Service\ExchangeRate\ListServiceInterface as ExchangeRateListServiceInterface;
use PHPUnit\Framework\TestCase;

class CalculateValuesServiceTest extends TestCase
{
    private $listServiceMock;
    private $exchangeRateListServiceMock;
    private $calculateValuesService;

    protected function setUp(): void
    {
        $this->listServiceMock = $this->createMock(AssetListServiceInterface::class);
        $this->exchangeRateListServiceMock = $this->createMock(ExchangeRateListServiceInterface::class);
        $this->calculateValuesService = new CalculateValuesService(
            $this->listServiceMock,
            $this->exchangeRateListServiceMock
        );
    }

    public function testCalculateAssetValuesSuccess(): void
    {
        $user = new User();

        $asset1 = new Asset();
        $asset1->setId(1);
        $asset1->setLabel('binance');
        $asset1->setValue(100);
        $asset1->setCurrency('bitcoin');

        $asset2 = new Asset();
        $asset2->setId(2);
        $asset2->setLabel('usb stick');
        $asset2->setValue(200);
        $asset2->setCurrency('ethereum');

        $assets = [$asset1, $asset2];

        $this->listServiceMock
            ->expects($this->once())
            ->method('getAssetsByUser')
            ->with($user)
            ->willReturn($assets);

        $this->exchangeRateListServiceMock
            ->expects($this->once())
            ->method('getExchangeRates')
            ->willReturn([
                'bitcoin' => 97793.62,
                'ethereum' => 3726.80,
            ]);

        $result = $this->calculateValuesService->calculateAssetValues($user);

        $expected = [
            'assets' => [
                [
                    'id' => 1,
                    'label' => 'binance',
                    'currency' => 'bitcoin',
                    'value' => 100,
                    'value_in_usd' => 9779362.00,
                ],
                [
                    'id' => 2,
                    'label' => 'usb stick',
                    'currency' => 'ethereum',
                    'value' => 200,
                    'value_in_usd' => 745360.0,
                ],
            ],
            'total_value_usd' => 10524722.0,
        ];

        $this->assertEqualsWithDelta($expected, $result, 0.0001);
    }
}
