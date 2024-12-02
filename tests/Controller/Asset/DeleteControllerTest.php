<?php

namespace App\Tests\Controller\Asset;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class DeleteControllerTest extends WebTestCase
{
    use TestAuthenticationTrait;

    public function testDeleteAssetSuccess(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request(
            'POST',
            '/api/assets',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'label' => 'binance',
                'value' => 0.123,
                'currency' => 'bitcoin',
            ])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertJson($client->getResponse()->getContent());

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('id', $responseData);

        $assetId = $responseData['id'];

        $client->request('DELETE', "/api/assets/{$assetId}");

        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

    public function testDeleteAssetNotFound(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('DELETE', '/api/assets/999999');

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testDeleteAssetUnauthorized(): void
    {
        $client = static::createClient();

        $client->request('DELETE', '/api/assets/1');

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
        $this->assertJson($client->getResponse()->getContent());
    }
}
