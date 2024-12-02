<?php

namespace App\Tests\Controller\Asset;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CreateControllerTest extends WebTestCase
{
    use TestAuthenticationTrait;

    public function testCreateAssetSuccess(): void
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
        $this->assertEquals($responseData['label'], 'usb stick');
        $this->assertEquals($responseData['value'], 0.123);
        $this->assertEquals($responseData['currency'], 'bitcoin');
    }

    public function testCreateAssetValidationFailure(): void
    {
        $client = $this->createAuthenticatedClient();

        $client->request(
            'POST',
            '/api/assets',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'label' => '', // Invalid label
                'value' => -100, // Invalid value
                'currency' => 'INVALID', // Invalid currency
            ])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testCreateAssetUnauthorized(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/assets',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'label' => 'Test Asset',
                'value' => 1000,
                'currency' => 'bitcoint',
            ])
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
        $this->assertJson($client->getResponse()->getContent());
    }
}
