<?php

namespace App\Tests\Controller\Asset;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Zenstruck\Foundry\Test\ResetDatabase;

class CalculateValuesControllerTest extends WebTestCase
{
    use TestAuthenticationTrait;
    use ResetDatabase;

    public function testCalculateValuesSuccess(): void
    {
        $client = $this->createAuthenticatedClient();

        $this->createTestAsset($client, [
            'label' => 'binance',
            'currency' => 'bitcoin',
            'value' => 0.123,
        ]);

        $this->createTestAsset($client, [
            'label' => 'usb stick',
            'currency' => 'ethereum',
            'value' => 0.321,
        ]);

        $client->request('GET', '/api/assets/values');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJson($client->getResponse()->getContent());

        $responseData = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('assets', $responseData);
        $this->assertArrayHasKey('total_value_usd', $responseData);
        $this->assertCount(2, $responseData['assets']);
        $this->assertEquals(7434.0, $responseData['total_value_usd']);

        $expectedAssets = [
            [
                'id' => $responseData['assets'][0]['id'],
                'label' => 'binance',
                'currency' => 'bitcoin',
                'value' => 0.123,
                'value_in_usd' => 6150.0,
            ],
            [
                'id' => $responseData['assets'][1]['id'],
                'label' => 'usb stick',
                'currency' => 'ethereum',
                'value' => 0.321,
                'value_in_usd' => 1284.0,
            ],
        ];

        $this->assertEquals($expectedAssets, $responseData['assets']);
    }

    private function createTestAsset($client, array $assetData): void
    {
        $client->request(
            'POST',
            '/api/assets',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($assetData)
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertJson($client->getResponse()->getContent());
    }
}
