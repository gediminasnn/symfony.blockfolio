<?php

namespace App\Tests\Controller\Asset;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ListControllerTest extends WebTestCase
{
    use TestAuthenticationTrait;

    public function testListAssetsSuccess(): void
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

        $client->request('GET', '/api/assets');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertJson($client->getResponse()->getContent());

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertIsArray($responseData);
        $this->assertNotEmpty($responseData);
    }

    public function testListAssetsUnauthorized(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/assets');

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
        $this->assertJson($client->getResponse()->getContent());
    }
}
