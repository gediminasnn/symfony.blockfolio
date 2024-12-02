<?php

namespace App\Tests\Controller\Asset;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ShowControllerTest extends WebTestCase
{
    use TestAuthenticationTrait;

    public function testShowAssetSuccess(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request(
            'POST',
            '/api/assets',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'label' => 'usb stick',
                'value' => 0.123,
                'currency' => 'bitcoin',
            ])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertJson($client->getResponse()->getContent());

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('id', $responseData);

        $assetId = $responseData['id'];

        $client->request('GET', "/api/assets/{$assetId}");

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJson($client->getResponse()->getContent());

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('id', $responseData);
        $this->assertEquals($responseData['label'], 'usb stick');
        $this->assertEquals($responseData['value'], 0.123);
        $this->assertEquals($responseData['currency'], 'bitcoin');
    }

    public function testShowAssetNotFound(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request('GET', '/api/assets/999999');

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testShowAssetUnauthorized(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/assets/1');

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
        $this->assertJson($client->getResponse()->getContent());
    }
}
